<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Badges extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('/');
        }
        $this->load->config('quiz');
        date_default_timezone_set(get_system_timezone());
    }

    public function index()
    {
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('login');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('update', 'badges_settings')) {
                    $this->session->set_flashdata('error', PERMISSION_ERROR_MSG);
                } else {
                    $data1 = $this->Badges_model->update_data();
                    if ($data1 == FALSE) {
                        $this->session->set_flashdata('error', IMAGE_ALLOW_MSG);
                    } else {
                        $this->session->set_flashdata('success', 'Badges updated successfully.!');
                    }
                }
                redirect('badges-settings', 'refresh');
            }
            $badges = [
                'dashing_debut',
                'combat_winner',
                'clash_winner',
                'most_wanted_winner',
                'ultimate_player',
                'quiz_warrior',
                'super_sonic',
                'flashback',
                'brainiac',
                'big_thing',
                'elite',
                'thirsty',
                'power_elite',
                'sharing_caring',
                'streak'
            ];
            foreach ($badges as $row) {
                $this->result[$row] = $this->db->select('badge_icon,badge_reward,badge_counter')->where('type', $row)->get('tbl_badges')->row_array();
            }
            $this->result['language'] = $this->Language_model->get_data();
            $this->load->view('badges_settings', $this->result);
        }
    }

    public function edit_data($id)
    {
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('login');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('update', 'badges_settings')) {
                    $this->session->set_flashdata('error', PERMISSION_ERROR_MSG);
                } else {
                    $data1 = $this->Badges_model->update_data();
                    if ($data1 == FALSE) {
                        $this->session->set_flashdata('error', IMAGE_ALLOW_MSG);
                    } else {
                        $this->session->set_flashdata('success', 'Badges updated successfully.!');
                    }
                }
                redirect('badges-settings/' . $id, 'refresh');
            }
            $badges = [
                'dashing_debut',
                'combat_winner',
                'clash_winner',
                'most_wanted_winner',
                'ultimate_player',
                'quiz_warrior',
                'super_sonic',
                'flashback',
                'brainiac',
                'big_thing',
                'elite',
                'thirsty',
                'power_elite',
                'sharing_caring',
                'streak'
            ];
            foreach ($badges as $row) {
                $languageWiseBadgeData = $this->db->select('badge_label,badge_note')->where('type', $row)->where('language_id', $id)->get('tbl_badges')->row_array();
                if (empty($languageWiseBadgeData)) {
                    $languageWiseBadgeData = $this->db->select('badge_label,badge_note')->where('type', $row)->where('language_id', 14)->get('tbl_badges')->row_array();
                }
                $otherBadgeData = $this->db->select('badge_icon,badge_reward,badge_counter')->where('type', $row)->get('tbl_badges')->row_array();
                $this->result[$row] = array_merge($languageWiseBadgeData, $otherBadgeData);
            }
            $this->result['edit_data'] = 1;
            $this->result['notification_title'] = $this->db->where('type', 'notification_title')->where('language_id', $id)->get('tbl_web_settings')->row_array();
            $this->result['notification_body'] = $this->db->where('type', 'notification_body')->where('language_id', $id)->get('tbl_web_settings')->row_array();
            $this->result['language'] = $this->Language_model->get_data();
            $this->load->view('badges_settings', $this->result);
        }
    }
}
