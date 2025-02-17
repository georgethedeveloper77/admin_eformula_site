<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Exam_Module extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('/');
        }
        $this->load->config('quiz');
        date_default_timezone_set(get_system_timezone());
        $this->quiz_type = 2;
        $this->result['language'] = $this->Language_model->get_data();
    }

    public function import_questions()
    {
        if (!has_permissions('read', 'exam_module')) {
            redirect('/');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('create', 'exam_module')) {
                    $this->session->set_flashdata('error', PERMISSION_ERROR_MSG);
                } else {
                    $data = $this->Exam_Module_model->import_data();
                    if ($data == "1") {
                        $this->session->set_flashdata('success', 'CSV file is successfully imported!');
                    } else if ($data == "0") {
                        $this->session->set_flashdata('error', 'Please upload data in CSV file!');
                    } else if ($data == "2") {
                        $this->session->set_flashdata('error', 'Please fill all the data in CSV file!');
                    } else {
                        $this->session->set_flashdata('error', $data);
                    }
                }
                redirect('exam-module-questions-import');
            }
            $this->load->view('exam_module_questions_import');
        }
    }

    public function exam_module_result($exam_id)
    {
        if (!has_permissions('read', 'exam_module')) {
            redirect('/');
        } else {
            $this->result['exam'] = $this->Exam_Module_model->get_exam_title($exam_id);
            $this->load->view('exam_module_result', $this->result);
        }
    }

    public function index()
    {
        if (!has_permissions('read', 'exam_module')) {
            redirect('/');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('create', 'exam_module')) {
                    $this->session->set_flashdata('error', PERMISSION_ERROR_MSG);
                } else {
                    $this->Exam_Module_model->add_data();
                    $this->session->set_flashdata('success', 'Exam Module created successfully.! ');
                }
                redirect('exam-module');
            }
            if ($this->input->post('btnupdate')) {
                if (!has_permissions('update', 'exam_module')) {
                    $this->session->set_flashdata('error', PERMISSION_ERROR_MSG);
                } else {
                    $this->Exam_Module_model->update_data();
                    $this->session->set_flashdata('success', 'Exam Module updated successfully.!');
                }
                redirect('exam-module');
            }
            if ($this->input->post('btnupdatestatus')) {
                if (!has_permissions('update', 'exam_module')) {
                    $this->session->set_flashdata('error', PERMISSION_ERROR_MSG);
                } else {
                    $contest_id = $this->input->post('update_id');
                    $res = $this->db->where('exam_module_id', $contest_id)->get('tbl_exam_module_question')->result();
                    if (empty($res)) {
                        $this->session->set_flashdata('error', 'No enough question for active exam module.!');
                    } else {
                        $this->Exam_Module_model->update_exam_module_status();
                        $this->session->set_flashdata('success', 'Exam Module updated successfully.!');
                    }
                }
                redirect('exam-module');
            }
            $this->result['language'] = $this->Language_model->get_data();
            //            $this->result['subcategory'] = $this->Subcategory_model->get_data();
            $this->load->view('exam_module', $this->result);
        }
    }

    public function delete_exam_module()
    {

        if (!has_permissions('delete', 'exam_module')) {
            echo FALSE;
        } else {
            $id = $this->input->post('id');
            $this->Exam_Module_model->delete_data($id);
            echo TRUE;
        }
    }

    public function exam_module_questions($id)
    {
        if (!has_permissions('read', 'exam_module')) {
            redirect('/');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('create', 'exam_module')) {
                    $this->session->set_flashdata('error', PERMISSION_ERROR_MSG);
                } else {
                    $this->Exam_Module_model->add_exam_module_question();
                    $this->session->set_flashdata('success', 'Question created successfully.! ');
                }
                redirect('exam-module-questions/' . $id);
            }

            $this->result['exam_module'] = $this->Exam_Module_model->get_data();
            $this->load->view('exam_module_questions', $this->result);
        }
    }

    public function exam_module_questions_list($id)
    {
        if (!has_permissions('read', 'exam_module')) {
            redirect('/');
        } else {
            $this->load->view('exam_module_questions_list');
        }
    }

    public function exam_module_questions_edit($id)
    {
        if (!has_permissions('read', 'exam_module')) {
            redirect('/');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('update', 'exam_module')) {
                    $this->session->set_flashdata('error', PERMISSION_ERROR_MSG);
                } else {
                    $this->Exam_Module_model->update_exam_module_question();
                    $this->session->set_flashdata('success', 'Question updated successfully.!');
                }
                redirect('exam-module-questions-edit/' . $id);
            }
            $this->result['data'] = $this->Exam_Module_model->get_exam_questions($id);
            $this->load->view('exam_module_questions', $this->result);
        }
    }

    public function delete_exam_module_questions()
    {
        if (!has_permissions('delete', 'exam_module')) {
            echo FALSE;
        } else {
            $id = $this->input->post('id');
            $image_url = $this->input->post('image_url');
            $this->Exam_Module_model->delete_exam_module_questions($id, $image_url);
            echo TRUE;
        }
    }
}
