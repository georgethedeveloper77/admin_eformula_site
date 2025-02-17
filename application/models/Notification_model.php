<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

require FCPATH . 'vendor/autoload.php';

defined('BASEPATH') or exit('No direct script access allowed');

class Notification_model extends CI_Model
{

    public $toDate;
    public $toDateTime;
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set(get_system_timezone());
        $this->toDate = date('Y-m-d');
        $this->toDateTime = date('Y-m-d H:i:s');
    }

    public function add_notification()
    {
        $title = $this->input->post('title');
        $message = $this->input->post('message');
        $users = $this->input->post('users');
        $type = $this->input->post('type');
        if ($type == 'category') {
            $maincat_id = $this->input->post('maincat_id');
            $res = $this->db->select('max(level)as maxlevel')->where('category', $maincat_id)->get('tbl_question')->result_array();
            $maxlevel = $res[0]['maxlevel'];

            $res1 = $this->db->select('count(id) as no_of')->where('maincat_id', $maincat_id)->where('status', 1)->get('tbl_subcategory')->result_array();
            $no_of = $res1[0]['no_of'];
        } else {
            $maxlevel = 0;
            $maincat_id = 0;
            $no_of = 0;
        }

        $frm_data = array(
            'title' => $title,
            'message' => $message,
            'users' => $users,
            'user_id' => '',
            'type' => $type,
            'type_id' => $maincat_id,
            'image' => '',
            'date_sent' => $this->toDateTime
        );
        $fcmMsg = array(
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'title' => $title,
            'body' => $message,
            'image' => null,
            'type' => $type,
            'type_id' => $maincat_id,
            'maxlevel' => $maxlevel,
            'no_of' => $no_of
        );
        if ($_FILES['file']['name'] != '') {
            // create folder 
            if (!is_dir(NOTIFICATION_IMG_PATH)) {
                mkdir(NOTIFICATION_IMG_PATH, 0777, TRUE);
            }
            $image = microtime(true) * 10000;
            $config['upload_path'] = NOTIFICATION_IMG_PATH;
            $config['allowed_types'] = IMG_ALLOWED_TYPES;
            $config['file_name'] = $image;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file')) {
                return FALSE;
            } else {
                $data = $this->upload->data();
                $img = $data['file_name'];
                //Setting values for tabel columns
                $frm_data['image'] = $img;
                $fcmMsg['image'] = base_url() . NOTIFICATION_IMG_PATH . $img;
            }
        }
        $this->db->insert('tbl_notifications', $frm_data);
        $insert_id = $this->db->insert_id();
        //notification 
        if ($users == 'all') {
            $res = $this->db->select("fcm_id")->get("tbl_users")->result_array();
            $fcm_ids = array();
            foreach ($res as $fcm_id) {
                $fcm_ids[] = $fcm_id['fcm_id'];
            }
        } elseif ($users == 'selected') {
            $selected_list = $this->input->post('selected_list');
            if (empty($selected_list)) {
                $response['error'] = true;
                $response['message'] =  lang('please_select_the_users_from_the_table');
                echo json_encode($response);
                return false;
            }
            $fcm_ids = array();
            $fcm_ids = explode(",", $selected_list);
            if ($insert_id) {
                $getID = $this->db->select('id')->where_in('fcm_id', $fcm_ids)->get("tbl_users")->result_array();
                $comma_separated_ids = '';
                if (!empty($getID)) {
                    $getIds = array_column($getID, 'id');
                    $comma_separated_ids = implode(',', $getIds);
                }
                $userID = array(
                    'user_id' => $comma_separated_ids,
                );
                $this->db->where('id', $insert_id)->update('tbl_notifications', $userID);
            }
        }

        $registrationIDs = $fcm_ids;

        $registrationIDs_chunks = array_chunk($registrationIDs, 500);
        $factory = (new Factory)->withServiceAccount('assets/firebase_config.json');
        $messaging = $factory->createMessaging();
        foreach ($registrationIDs_chunks as $registrationIDs) {
            $message = CloudMessage::new();
            $message = $message->withNotification($fcmMsg)->withData($fcmMsg);
            $messaging->sendMulticast($message, $registrationIDs);
        }
    }

    public function delete_notification($id, $image_url)
    {
        //Delete image from folder 
        if (file_exists($image_url)) {
            unlink($image_url);
        }
        $this->db->where('id', $id)->delete('tbl_notifications');
    }
}
