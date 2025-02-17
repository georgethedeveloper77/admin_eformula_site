<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contest extends CI_Controller
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

    public function contest_questions_import()
    {
        if ($this->input->post('btnadd')) {
            if (!has_permissions('update', 'import_contest_question')) {
                $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
            } else {
                $data = $this->Contest_model->import_data();
                if ($data == "1") {
                    $this->session->set_flashdata('success', lang('csv_file_successfully_imported'));
                } else if ($data == "0") {
                    $this->session->set_flashdata('error', lang('please_upload_data_in_csv_file'));
                } else if ($data == "2") {
                    $this->session->set_flashdata('error', lang('please_fill_all_the_data_in_csv_file'));
                } else {
                    $this->session->set_flashdata('error', $data);
                }
            }
            redirect('contest-questions-import', 'refresh');
        }
        $this->load->view('contest_questions_import');
    }

    public function contest_questions()
    {
        if (!has_permissions('read', 'manage_contest_question')) {
            redirect('/', 'refresh');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('create', 'manage_contest_question')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $data = $this->Contest_model->add_contest_question();
                    if ($data == FALSE) {
                        $this->session->set_flashdata('error', lang(IMAGE_ALLOW_MSG));
                    } else {
                        $this->session->set_flashdata('success', lang('question_created_successfully'));
                    }
                }
                redirect('contest-questions', 'refresh');
            }
            if ($this->input->post('btnupdate')) {
                if (!has_permissions('update', 'manage_contest_question')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $data1 = $this->Contest_model->update_contest_question();
                    if ($data1 == FALSE) {
                        $this->session->set_flashdata('error', lang(IMAGE_ALLOW_MSG));
                    } else {
                        $this->session->set_flashdata('success', lang('question_updated_successfully'));
                    }
                }
                redirect('contest-questions', 'refresh');
            }
            $this->result['language'] = $this->Language_model->get_data();
            $this->result['contest'] = $this->Contest_model->get_data();
            $this->load->view('contest_questions', $this->result);
        }
    }

    public function delete_contest_questions()
    {
        if (!has_permissions('delete', 'manage_contest_question')) {
            echo FALSE;
        } else {
            $id = $this->input->post('id');
            $image_url = $this->input->post('image_url');
            $this->Contest_model->delete_contest_questions($id, $image_url);
            echo TRUE;
        }
    }

    public function contest_prize_distribute($id)
    {
        if (!has_permissions('read', 'manage_contest')) {
            redirect('/', 'refresh');
        } else {
            $currentDate = date('Y-m-d H:i:s');
            $res = $this->db->where('end_date <=', $currentDate)->where('id', $id)->limit(1)->get('tbl_contest')->result();
            if (!empty($res)) {
                foreach ($res as $contest) {
                    $prize_status = $contest->prize_status;
                    $contest_name = $contest->name;
                    if ($prize_status == 0) {
                        $contest_id = $contest->id;
                        // $type = "Contest Winner - $contest_name ";
                        $type = "wonContest";
                        $res1 = $this->db->where('contest_id', $contest_id)->order_by('top_winner', 'ASC')->get('tbl_contest_prize')->result();
                        if (!empty($res1)) {
                            for ($j = 0; $j < count($res1); $j++) {

                                $u_rank = $res1[$j]->top_winner;
                                $winner_points = $res1[$j]->points;

                                $query2 = $this->db->query("SELECT r.*, u.firebase_id, u.coins FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, score FROM tbl_contest_leaderboard c join tbl_users u on u.id = c.user_id WHERE contest_id='" . $contest_id . "' ) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join tbl_users u on u.id = r.user_id WHERE r.user_rank='" . $u_rank . "' ORDER BY r.user_rank ASC");
                                $res2 = $query2->result();

                                for ($i = 0; $i < count($res2); $i++) {
                                    $frm_data = array(
                                        'user_id' => $res2[$i]->user_id,
                                        'uid' => $res2[$i]->firebase_id,
                                        'points' => $winner_points,
                                        'type' => $type,
                                        'status' => 0,
                                        'date' => date("Y-m-d")
                                    );
                                    $this->db->insert('tbl_tracker', $frm_data);

                                    $coins = ($res2[$i]->coins + $winner_points);
                                    $frm_data1 = array('coins' => $coins);
                                    $this->db->where('id', $res2[$i]->user_id)->update('tbl_users', $frm_data1);
                                }
                            }
                            $frm_data2 = array('prize_status' => '1');
                            $this->db->where('id', $contest_id)->update('tbl_contest', $frm_data2);

                            $this->session->set_flashdata('success', lang('successfully_prize_distributed_for') . $contest_name . '..!');
                        } else {
                            $this->session->set_flashdata('error', lang('prize_can_not_distributed_for') . $contest_name . '..!');
                        }
                    } else {
                        $this->session->set_flashdata('error', lang('prize_already_distributed_for') . $contest_name . '..!');
                    }
                }
            } else {
                $this->session->set_flashdata('error', lang('prize_distribution_is_currently_not_available_check_contest_end_date'));
            }
            redirect('contest', 'refresh');
        }
    }

    public function contest_leaderboard()
    {
        if (!has_permissions('read', 'manage_contest')) {
            redirect('/', 'refresh');
        } else {
            $this->load->view('contest_leaderboard');
        }
    }

    public function index()
    {
        if (!has_permissions('read', 'manage_contest')) {
            redirect('/', 'refresh');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('create', 'manage_contest')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $data = $this->Contest_model->add_contest();
                    if ($data == FALSE) {
                        $this->session->set_flashdata('error', lang(IMAGE_ALLOW_MSG));
                    } else {
                        $this->session->set_flashdata('success', lang('contest_created_successfully'));
                    }
                }
                redirect('contest', 'refresh');
            }
            if ($this->input->post('btnupdate')) {
                if (!has_permissions('update', 'manage_contest')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $data1 = $this->Contest_model->update_contest();
                    if ($data1 == FALSE) {
                        $this->session->set_flashdata('error', lang(IMAGE_ALLOW_MSG));
                    } else {
                        $this->session->set_flashdata('success',  lang('contest_updated_successfully'));
                    }
                }
                redirect('contest', 'refresh');
            }
            if ($this->input->post('btnupdatestatus')) {
                if (!has_permissions('update', 'manage_contest')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $contest_id = $this->input->post('update_id');
                    $res = $this->db->where('contest_id', $contest_id)->get('tbl_contest_question')->result();
                    if (empty($res)) {
                        $this->session->set_flashdata('error', lang('not_enought_question_for_active_contest'));
                    } else {
                        $this->Contest_model->update_contest_status();
                        $this->session->set_flashdata('success', lang('contest_updated_successfully'));
                    }
                }
                redirect('contest', 'refresh');
            }
            $this->result['language'] = $this->Language_model->get_data();
            $this->load->view('contest', $this->result);
        }
    }

    public function delete_contest()
    {
        if (!has_permissions('delete', 'manage_contest')) {
            echo FALSE;
        } else {
            $id = $this->input->post('id');
            $image_url = $this->input->post('image_url');
            $this->Contest_model->delete_contest($id, $image_url);
            echo TRUE;
        }
    }

    public function contest_prize($id)
    {
        if (!has_permissions('read', 'manage_contest')) {
            redirect('/', 'refresh');
        } else {
            if ($this->input->post('btnadd')) {
                if (!has_permissions('create', 'manage_contest')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $this->Contest_model->add_contest_prize();
                    $this->session->set_flashdata('success', lang('prize_created_successfully'));
                }
                redirect('contest-prize/' . $id, 'refresh');
            }
            if ($this->input->post('btnupdate')) {
                if (!has_permissions('update', 'manage_contest')) {
                    $this->session->set_flashdata('error', lang(PERMISSION_ERROR_MSG));
                } else {
                    $this->Contest_model->update_contest_prize();
                    $this->session->set_flashdata('success', lang('prize_updated_successfully'));
                }
                redirect('contest-prize/' . $id, 'refresh');
            }
            $this->result['max'] = $this->Contest_model->get_max_top_winner($id);
            $this->load->view('contest_prize', $this->result);
        }
    }

    public function delete_contest_prize()
    {
        if (!has_permissions('delete', 'manage_contest')) {
            echo FALSE;
        } else {
            $id = $this->input->post('id');
            $this->Contest_model->delete_contest_prize($id);
            echo TRUE;
        }
    }
}
