<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

require FCPATH . 'vendor/autoload.php';

defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('/');
        }
        $this->load->helper('password_helper');
        $this->load->config('quiz');
        date_default_timezone_set(get_system_timezone());

        $this->category_type = $this->config->item('category_type');

        $this->toDate = date('Y-m-d');

        $this->NO_IMAGE = base_url() . LOGO_IMG_PATH . is_settings('half_logo');
    }


    public function activity_tracker()
    {
        if (!has_permissions('read', 'activity_tracker')) {
            redirect('/', 'refresh');
        } else {
            $this->load->view('activity_tracker');
        }
    }

    public function payment_settings()
    {
        if (!has_permissions('read', 'payment_settings')) {
            redirect('/', 'refresh');
        } else {
            $settings = [
                'payment_mode', 'payment_message',
                'per_coin', 'coin_amount', 'currency_symbol', 'coin_limit', 'difference_hours'
            ];
            if ($this->input->post('btnadd')) {
                if (!has_permissions('update', 'payment_settings')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    foreach ($settings as $type) {
                        $message = $this->input->post($type);
                        $res = $this->db->where('type', $type)->get('tbl_settings')->row_array();
                        if ($res) {
                            $data = ['message' => $message];
                            $this->db->where('type', $type)->update('tbl_settings', $data);
                        } else {
                            $data = array(
                                'type' => $type,
                                'message' => $message
                            );
                            $this->db->insert('tbl_settings', $data);
                        }
                    }
                    $this->session->set_flashdata('success', lang('settings_updated_successfully'));
                    redirect('payment-settings', 'refresh');
                }
            }

            foreach ($settings as $row) {
                $data = $this->db->where('type', $row)->get('tbl_settings')->row_array();
                $this->result[$row] = $data;
            }
            $this->load->view('payment_settings', $this->result);
        }
    }

    public function payment_requests()
    {
        if (!has_permissions('read', 'payment_requests')) {
            redirect('/', 'refresh');
        } else {
            $pathToServiceAccountJsonFile = 'assets/firebase_config.json';
            if (!file_exists($pathToServiceAccountJsonFile)) {
                redirect('firebase-configurations');
            }
            if ($this->input->post('btnadd')) {
                $multiple_ids = $this->input->post('multiple_ids');
                // $multiple_ids = explode(',', $multiple_ids);
                if (!has_permissions('create', 'payment_settings')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    if ($multiple_ids == '') {
                        $this->session->set_flashdata('error', lang('please_select_some_records'));
                    } else {
                        $status = $this->input->post('status');
                        $this->db->query("UPDATE tbl_payment_request SET `status`='$status' WHERE id in ( " . $multiple_ids . " ) ");
                        $this->session->set_flashdata('success', lang('data_updated_successfully'));
                    }
                }
                redirect('payment-requests', 'refresh');
            }
            if ($this->input->post('btnupdate')) {
                if (!has_permissions('update', 'payment_settings')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $edit_id = $this->input->post('edit_id');
                    $firebase_id = $this->input->post('uid');
                    $user_id = $this->input->post('edit_user_id');
                    $status = $this->input->post('status');
                    $details = $this->input->post('details');
                    $coins = $this->input->post('coin_used');

                    $res = $this->db->where('id', $edit_id)->get('tbl_payment_request')->row_array();
                    if ($res['status'] == 2) {
                        $this->session->set_flashdata('error', lang('oops_can_not_update_status_once_its_done'));
                    } else {
                        $user_res = $this->db->where('id', $user_id)->get('tbl_users')->row_array();
                        $fcm_id = $user_res['fcm_id'];
                        if ($status == 2) {
                            $net_coins = $user_res['coins'] + $coins;
                            $data = [
                                'coins' => $net_coins
                            ];
                            $this->db->where('id', $user_id)->update('tbl_users', $data);

                            $tracker_data = [
                                'user_id' => $user_id,
                                'uid' => $firebase_id,
                                'points' => $coins,
                                'type' => 'redeemedAmount',
                                'status' => 0,
                                'date' => $this->toDate
                            ];
                            $this->db->insert('tbl_tracker', $tracker_data);
                        }
                        $data = [
                            'details' => $details,
                            'status' => $status
                        ];
                        $this->db->where('id', $edit_id)->update('tbl_payment_request', $data);

                        if ($status == 1  || $status == 2) {
                            // send notification                           
                            if ($status == 1) {
                                $title = lang('payment_request_complete');
                                $message = lang('your_payment_is_complete_you_have_used') . " " . $coins . " " . lang('points_thank_you');
                            }
                            if ($status == 2) {
                                $title = lang('payment_details_is_wrong');
                                $message = lang('your_payment_details_is_wrong_we_have_refund_your') . " " . $coins . " " . lang('points_thank_you');
                            }

                            $fcmMsg = array(
                                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                                'type' => 'payment_request',
                                'title' => $title,
                                'body' => $message,
                                'coins' => $coins
                            );
                            if ($fcm_id != '') {
                                $fcm_ids = array();
                                array_push($fcm_ids, $fcm_id);
                                $this->send_notification($fcm_ids, $fcmMsg);
                            }
                        }
                        $this->session->set_flashdata('success', lang('data_updated_successfully'));
                    }
                    redirect('payment-requests', 'refresh');
                }
            }
            $this->load->view('payment_requests');
        }
    }

    public function send_notification($registrationIDs, $fcmMsg)
    {
        $registrationIDs_chunks = array_chunk($registrationIDs, 500);
        $factory = (new Factory)->withServiceAccount('assets/firebase_config.json');
        $messaging = $factory->createMessaging();
        foreach ($registrationIDs_chunks as $registrationIDs) {
            $message = CloudMessage::new();
            $message = $message->withNotification($fcmMsg)->withData($fcmMsg);
            $messaging->sendMulticast($message, $registrationIDs);
        }
    }
}
