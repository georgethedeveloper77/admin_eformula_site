<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Languages extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('/');
        }
        $this->load->config('quiz');
    }

    public function index()
    {
        if (!has_permissions('read', 'languages')) {
            redirect('/');
        } else {
            if ($this->input->post('btncreate')) {
                if (!has_permissions('create', 'languages')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $result = $this->Language_model->add_new_data();

                    $setData = [
                        'language_name' => $this->input->post('language_name'),
                        'language_code' => $this->input->post('language_code'),
                    ];
                    if ($result['error']) {
                        $this->session->set_userdata($setData);
                        $this->session->set_flashdata('error', $result['message']);
                    } else {
                        $this->session->unset_userdata($setData);
                        $this->session->set_flashdata('success', $result['message']);
                    }
                }
                redirect('languages');
            } else if ($this->input->post('btnadd')) {
                if (!has_permissions('create', 'languages')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $this->Language_model->add_data();
                    $this->session->set_flashdata('success', lang('language_added_successfully'));
                }
                redirect('languages');
            } else if ($this->input->post('btnupdate')) {
                if (!has_permissions('update', 'languages')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $result = $this->Language_model->update_data();
                    if ($result && $result['message']) {
                        $this->session->set_flashdata('success', $result['message']);
                    }
                }
                redirect('languages');
            }
            $this->result['language'] = $this->Language_model->get_all_lang();
            $this->load->view('languages', $this->result);
        }
    }

    public function delete_language()
    {
        if (!has_permissions('delete', 'languages')) {
            echo FALSE;
        } else {
            $id = $this->input->post('id');
            $this->Language_model->delete_data($id);
            echo TRUE;
        }
    }
}
