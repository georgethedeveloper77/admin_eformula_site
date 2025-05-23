<?php

defined('BASEPATH') || exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require APPPATH . '/libraries/REST_Controller.php';

class Table extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('/');
        }
        $this->load->database();
        date_default_timezone_set(get_system_timezone());
        $this->toDate = date('Y-m-d');
        $this->toDateTime = date('Y-m-d H:i:s');

        $this->load->config('quiz');

        $this->category_type = $this->config->item('category_type');

        $this->NO_IMAGE = base_url() . LOGO_IMG_PATH . is_settings('half_logo');
    }

    public function slider_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('language') && $this->get('language') != '') {
            $language_id = $this->get('language');
            $where = " WHERE s.language_id=" . $language_id . "";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            if ($this->get('language') && $this->get('language') != '') {
                $where .= " AND (s.`id` like '%" . $search . "%' OR s.`title` like '%" . $search . "%' OR s.`description` like '%" . $search . "%' OR l.`language` like '%" . $search . "%' )";
            } else {
                $where = " WHERE (s.`id` like '%" . $search . "%' OR s.`title` like '%" . $search . "%' OR s.`description` like '%" . $search . "%' OR l.`language` like '%" . $search . "%' )";
            }
        }

        $join = " LEFT JOIN tbl_languages l on l.id = s.language_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_slider s $join $where");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }


        $query1 = $this->db->query("SELECT s.*,l.language FROM tbl_slider s $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? SLIDER_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['description'] = $row->description;
            $tempRow['title'] = $row->title;
            $tempRow['image'] = (!empty($image)) ? '<a href=' . base_url() . $image . '  data-lightbox="' . lang('slider_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : '<img src=' . $this->NO_IMAGE . ' height=30 >';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function maths_question_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('language') && $this->get('language') != '') {
            $where = " WHERE q.language_id=" . $this->get('language') . "";
            if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        } else if ($this->get('category') && $this->get('category') != '') {
            $where = ' WHERE q.category=' . $this->get('category');
            if ($this->get('subcategory') && $this->get('subcategory') != '') {
                $where .= ' AND q.subcategory=' . $this->get('subcategory');
            }
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (q.`id` like '%" . $search . "%' OR `question` like '%" . $search . "%' OR `optiona` like '%" . $search . "%' OR `optionb` like '%" . $search . "%' OR `optionc` like '%" . $search . "%' OR `optiond` like '%" . $search . "%' OR `answer` like '%" . $search . "%')";
            if ($this->get('language') && $this->get('language') != '') {
                $where .= " AND q.language_id=" . $this->get('language') . "";
                if ($this->get('category') && $this->get('category') != '') {
                    $where .= ' AND q.category=' . $this->get('category');
                    if ($this->get('subcategory') && $this->get('subcategory') != '') {
                        $where .= ' AND q.subcategory=' . $this->get('subcategory');
                    }
                }
            } else if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        }

        $join = " LEFT JOIN tbl_languages l ON l.id = q.language_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_maths_question q $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT q.*, l.language FROM tbl_maths_question q $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? MATHS_QUESTION_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" href="' . base_url() . "create-maths-questions/" . $row->id . '" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('question_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['question_type'] = $row->question_type;
            $tempRow['answer'] = $row->answer;
            $tempRow['question'] = "<textarea class='editor-questions-inline'>" . $row->question . "</textarea>";
            $tempRow['optiona'] = "<textarea class='editor-questions-inline'>" . $row->optiona . "</textarea>";
            $tempRow['optionb'] = "<textarea class='editor-questions-inline'>" . $row->optionb . "</textarea>";
            $tempRow['optionc'] = "<textarea class='editor-questions-inline'>" . $row->optionc . "</textarea>";
            $tempRow['optiond'] = "<textarea class='editor-questions-inline'>" . $row->optiond . "</textarea>";
            $tempRow['optione'] = "<textarea class='editor-questions-inline'>" . $row->optione . "</textarea>";
            $tempRow['note'] = "<textarea class='editor-questions-inline'>" . $row->note . "</textarea>";

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function payment_requests_list_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('user_id') && $this->get('user_id') != '') {
            $user_id = $this->get('user_id');
            $where = " WHERE t.user_id=" . $user_id . "";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (t.id like '%" . $search . "%' OR u.name like '%" . $search . "%' OR t.payment_type like '%" . $search . "%' )";
            if ($this->get('user_id') && $this->get('user_id') != '') {
                $user_id = $this->get('user_id');
                $where .= " AND t.user_id=" . $user_id . "";
            }
        }

        $join = " LEFT JOIN tbl_users u on u.id = t.user_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_payment_request t $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT t.*, u.name FROM tbl_payment_request t $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';

            $paymentAddress = '';
            $payment_address = json_decode($row->payment_address);
            // echo count($payment_address);
            for ($i = 0; $i < count($payment_address); $i++) {
                $paymentAddress .= $payment_address[$i] . '<br/>';
            }

            $tempRow['id'] = $row->id;
            $tempRow['uid'] = $row->uid;
            $tempRow['user_id'] = $row->user_id;
            $tempRow['name'] = $row->name;
            $tempRow['payment_type'] = $row->payment_type;
            $tempRow['payment_address'] = $paymentAddress;
            $tempRow['payment_amount'] = $row->payment_amount;
            $tempRow['coin_used'] = $row->coin_used;
            $tempRow['details'] = $row->details;
            $tempRow['status1'] = $row->status;
            $tempRow['status'] = ($row->status) ? (($row->status == 1) ? "<label class='badge badge-success'>" . lang('completed') . "</label>" : "<label class='badge badge-danger'>" . lang('invalid_details') . "</label>") : "<label class='badge badge-warning'>" . lang('pending') . "</label>";
            $tempRow['date'] = $row->date;
            $tempRow['status_date'] = $row->status_date;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function tracker_list_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('user_id') && $this->get('user_id') != '') {
            $user_id = $this->get('user_id');
            $where = " WHERE t.user_id=" . $user_id . "";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (t.id like '%" . $search . "%' OR u.name like '%" . $search . "%' OR t.points like '%" . $search . "%' OR t.date like '%" . $search . "%')";
            if ($this->get('user_id') && $this->get('user_id') != '') {
                $user_id = $this->get('user_id');
                $where .= " AND t.user_id=" . $user_id . "";
            }
        }

        $join = " LEFT JOIN tbl_users u on u.id = t.user_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_tracker t $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT t.*, u.name FROM tbl_tracker t $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;

        $type_key = array(
            "wonQuizZone" => lang('wonQuizZone'),
            "wonBattle" => lang('wonBattle'),
            "usedSkiplifeline" =>  lang('usedSkiplifeline'),
            "usedAudiencePolllifeline" => lang('usedAudiencePolllifeline'),
            "usedResetTimerlifeline" => lang('usedResetTimerlifeline'),
            "used5050lifeline" =>  lang('used5050lifeline'),
            "usedHintLifeline" => lang('usedHintLifeline'),
            "rewardByScratchingCard" => lang('rewardByScratchingCard'),
            "boughtCoins" => lang('boughtCoins'),
            "watchedRewardAd" => lang('watchedRewardAd'),
            "wonAudioQuiz" => lang('wonAudioQuiz'),
            "wonDailyQuiz" => lang('wonDailyQuiz'),
            "wonTrueFalse" => lang('wonTrueFalse'),
            "wonFunNLearn" => lang('wonFunNLearn'),
            "wonGuessTheWord" => lang('wonGuessTheWord'),
            "wonGroupBattle" => lang('wonGroupBattle'),
            "playedGroupBattle" => lang('playedGroupBattle'),
            "playedContest" => lang('playedContest'),
            "playedBattle" => lang('playedBattle'),
            "redeemedAmount" => lang('redeemedAmount'),
            "welcomeBonus" => lang('welcomeBonus'),
            "wonContest" => lang('wonContest'),
            "redeemRequest" => lang('redeemRequest'),
            "reversedByAdmin" => lang('reversedByAdmin'),
            "referredCodeToFriend" => lang('referredCodeToFriend'),
            "usedReferCode" => lang('usedReferCode'),
            "reviewAnswers" => lang('reviewAnswers'),
            "wonMathQuiz" => lang('wonMathQuiz'),
            "adminAdded" => lang('adminAdded'),
            "watchedAds" => lang('watchedAds'),
            "reviewAnswerLbl" => lang('reviewAnswerLbl'),
            "referCodeToFriend" => lang('referCodeToFriend')
        );

        foreach ($res1 as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['uid'] = $row->uid;
            $tempRow['name'] = $row->name;
            $tempRow['points'] = $row->points;
            $tempRow['type'] = (isset($type_key[$row->type])) ? $type_key[$row->type] : $row->type;
            $tempRow['date'] = $row->date;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function exam_module_result_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('exam_module_id') && $this->get('exam_module_id') != '') {
            $where = ' WHERE exam_module_id=' . $this->get('exam_module_id');
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (r.`id` like '%" . $search . "%' OR u.`name` like '%" . $search . "%' )";
        }

        $join = " LEFT JOIN tbl_users u on u.id = r.user_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_exam_module_result r $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $this->db->query("SET @rank=0;");
        $query1 = $this->db->query("
            SELECT r.*, u_name, rank
            FROM (
                SELECT r.*, u.id as u_id, u.name as u_name, @rank:=@rank+1 as rank
                FROM tbl_exam_module_result r $join $where 
                ORDER BY CAST(obtained_marks AS SIGNED) DESC, CAST(total_duration AS SIGNED) ASC
            ) r
            ORDER BY $sort $order 
            LIMIT $offset , $limit
        ");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-eye"></i></a>';
            // $operate .=($row->rules_violated) ? '<a class="edit-captured" data-toggle="modal" data-target="#editCapturedModal"><label class="badge badge-success">Yes</label></a>' : '<label class="badge badge-danger">No</label>'; 

            $tempRow['id'] = $row->id;
            $tempRow['exam_module_id'] = $row->exam_module_id;
            $tempRow['user_id'] = $row->user_id;
            $tempRow['u_name'] = $row->u_name;
            $tempRow['rank'] = $row->rank;
            $tempRow['obtained_marks'] = $row->obtained_marks;
            $tempRow['total_duration'] = $row->total_duration;
            $tempRow['statistics'] = $row->statistics;
            $tempRow['rules_violated'] = ($row->rules_violated) ? '<a class="edit-captured" data-toggle="modal" data-target="#editCapturedModal"><label class="badge badge-success">Yes</label></a>' : '<label class="badge badge-danger">No</label>';
            $tempRow['captured_question_ids'] = $row->captured_question_ids;
            $captured_res = '';
            if ($row->rules_violated == 1) {
                $captured = json_decode($row->captured_question_ids);
                if (!(empty($captured))) {
                    $captured_res = $this->db->query("SELECT id, question FROM tbl_exam_module_question WHERE id IN (" . implode(',', $captured) . ")")->result();
                }
            }
            $tempRow['captured_que'] = (!empty($captured_res)) ? json_encode($captured_res) : '';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function exam_module_questions_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('exam_module_id') && $this->get('exam_module_id') != '') {
            $where = ' WHERE tq.exam_module_id=' . $this->get('exam_module_id');
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (tq.`id` like '%" . $search . "%' OR tq.`question` like '%" . $search . "%' OR tq.`optiona` like '%" . $search . "%' OR tq.`optionb` like '%" . $search . "%' OR tq.`optionc` like '%" . $search . "%' OR tq.`optiond` like '%" . $search . "%' OR tq.`answer` like '%" . $search . "%')";
            if ($this->get('exam_module_id') && $this->get('exam_module_id') != '') {
                $where .= ' AND tq.exam_module_id=' . $this->get('exam_module_id');
            }
        }

        $join = " JOIN tbl_exam_module te ON te.id = tq.exam_module_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_exam_module_question tq $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT tq.* FROM tbl_exam_module_question tq $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? EXAM_QUESTION_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" href="' . base_url() . "exam-module-questions-edit/" . $row->id . '" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['exam_module_id'] = $row->exam_module_id;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('question_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['marks'] = $row->marks;
            $tempRow['question_type'] = $row->question_type;

            if (is_settings('exam_latex_mode')) {
                $question = "<textarea class='editor-questions-inline'>" . $row->question . "</textarea>";
                $optiona = "<textarea class='editor-questions-inline'>" . $row->optiona . "</textarea>";
                $optionb = "<textarea class='editor-questions-inline'>" . $row->optionb . "</textarea>";
                $optionc = $row->optionc ? "<textarea class='editor-questions-inline'>" . $row->optionc . "</textarea>" : "-";
                $optiond = $row->optiond ? "<textarea class='editor-questions-inline'>" . $row->optiond . "</textarea>" : "-";
                $optione = $row->optione ? "<textarea class='editor-questions-inline'>" . $row->optione . "</textarea>" : "-";
            } else {
                $question = $row->question;
                $optiona = $row->optiona;
                $optionb = $row->optionb;
                $optionc = $row->optionc ? $row->optionc : "-";
                $optiond = $row->optiond ? $row->optiond : "-";
                $optione = $row->optione ? $row->optione : "-";
            }

            $tempRow['question'] = $question;
            $tempRow['optiona'] = $optiona;
            $tempRow['optionb'] = $optionb;
            $tempRow['optionc'] = $optionc;
            $tempRow['optiond'] = $optiond;
            $tempRow['optione'] = $optione;
            $tempRow['answer'] = $row->answer;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function exam_module_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('language') && $this->get('language') != '') {
            $language_id = $this->get('language');
            $where = " WHERE c.language_id=" . $language_id . "";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (c.`id` like '%" . $search . "%' OR c.`title` like '%" . $search . "%' OR l.`language` like '%" . $search . "%' )";
            if ($this->get('language') && $this->get('language') != '') {
                $language_id = $this->get('language');
                $where .= " AND c.language_id=" . $language_id . "";
            }
        }

        $join = " LEFT JOIN tbl_languages l on l.id = c.language_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_exam_module c $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT c.*,l.language, (select count(id) from tbl_exam_module_question q where q.exam_module_id = c.id ) as no_of_que FROM tbl_exam_module c $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $operate = "<a class='btn btn-icon btn-sm btn-warning' href='" . base_url() . "exam-module-questions/" . $row->id . "' title='" . lang('add_question') . "'><i class='fas fa-plus'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-info' href='" . base_url() . "exam-module-questions-list/" . $row->id . "' title='" . lang('list_questions') . "'><i class='fas fa-list'></i></a>";
            $operate .= '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= "<a class='btn btn-icon btn-sm btn-success edit-status' data-id='" . $row->id . "' data-toggle='modal' data-target='#editStatusModal' title='" . lang('edit_status') . "'><i class='fas fa-edit'></i></a>";
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . '><i class="fa fa-trash"></i></a>';
            if ($this->toDate >= $row->date) {
                $operate .= "<a class='btn btn-icon btn-sm btn-warning' href='" . base_url() . "exam-module-result/" . $row->id . "' title='" . lang('result') . "'><i class='fas fa-list-alt'></i></a>";
            }

            $tempRow['id'] = $row->id;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['title'] = $row->title;
            $tempRow['date'] = $row->date;
            $tempRow['exam_key'] = $row->exam_key;
            $tempRow['duration'] = $row->duration;
            $tempRow['no_of_que'] = $row->no_of_que;
            $tempRow['status'] = ($row->status) ? "<label class='badge badge-success'>" . lang('active') . "</label>" : "<label class='badge badge-danger'>" . lang('deactive') . "</label>";
            $tempRow['answer_again'] = ($row->answer_again) ? "<label class='badge badge-success'>" . lang('yes') . "</label>" : "<label class='badge badge-danger'>" . lang('no') . "</label>";
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function guess_the_word_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('language') && $this->get('language') != '') {
            $where = " WHERE q.language_id=" . $this->get('language') . "";
            if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        } else if ($this->get('category') && $this->get('category') != '') {
            $where = ' WHERE q.category=' . $this->get('category');
            if ($this->get('subcategory') && $this->get('subcategory') != '') {
                $where .= ' AND q.subcategory=' . $this->get('subcategory');
            }
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (q.`id` like '%" . $search . "%' OR `question` like '%" . $search . "%' OR `answer` like '%" . $search . "%')";
            if ($this->get('language') && $this->get('language') != '') {
                $where .= " AND q.language_id=" . $this->get('language') . "";
                if ($this->get('category') && $this->get('category') != '') {
                    $where .= ' AND q.category=' . $this->get('category');
                    if ($this->get('subcategory') && $this->get('subcategory') != '') {
                        $where .= ' AND q.subcategory=' . $this->get('subcategory');
                    }
                }
            } else if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        }

        $join = " LEFT JOIN tbl_languages l ON l.id = q.language_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_guess_the_word q $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT q.*, l.language FROM tbl_guess_the_word q $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? GUESS_WORD_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('question_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['question'] = $row->question;
            $tempRow['answer'] = $row->answer;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function fun_n_learn_question_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('fun_n_learn_id') && $this->get('fun_n_learn_id') != '') {
            $where = ' WHERE tq.fun_n_learn_id=' . $this->get('fun_n_learn_id');
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (q.`id` like '%" . $search . "%' OR `question` like '%" . $search . "%' OR `optiona` like '%" . $search . "%' OR `optionb` like '%" . $search . "%' OR `optionc` like '%" . $search . "%' OR `optiond` like '%" . $search . "%' OR `answer` like '%" . $search . "%')";
            if ($this->get('fun_n_learn_id') && $this->get('fun_n_learn_id') != '') {
                $where .= ' AND tq.fun_n_learn_id=' . $this->get('fun_n_learn_id');
            }
        }

        $join = " JOIN tbl_fun_n_learn tc ON tc.id = tq.fun_n_learn_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_fun_n_learn_question tq $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT tq.* FROM tbl_fun_n_learn_question tq $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? FUN_LEARN_QUESTION_IMG_PATH . $row->image : '';

            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image=' . $image . '><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['fun_n_learn_id'] = $row->fun_n_learn_id;
            $tempRow['question'] = $row->question;
            $tempRow['question_type'] = $row->question_type;
            $tempRow['optiona'] = $row->optiona;
            $tempRow['optionb'] = $row->optionb;
            $tempRow['optionc'] = $row->optionc;
            $tempRow['optiond'] = $row->optiond;
            $tempRow['optione'] = $row->optione;
            $tempRow['answer'] = $row->answer;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('question_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function fun_n_learn_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('language') && $this->get('language') != '') {
            $language_id = $this->get('language');
            $where = " WHERE c.language_id=" . $language_id . "";
            if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND c.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND c.subcategory=' . $this->get('subcategory');
                }
            }
        } else if ($this->get('category') && $this->get('category') != '') {
            $where = ' WHERE c.category=' . $this->get('category');
            if ($this->get('subcategory') && $this->get('subcategory') != '') {
                $where .= ' AND c.subcategory=' . $this->get('subcategory');
            }
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (c.`id` like '%" . $search . "%' OR c.`title` like '%" . $search . "%' OR l.`language` like '%" . $search . "%' )";
            if ($this->get('language') && $this->get('language') != '') {
                $language_id = $this->get('language');
                $where .= " AND c.language_id=" . $language_id . "";
                if ($this->get('category') && $this->get('category') != '') {
                    $where .= ' AND c.category=' . $this->get('category');
                    if ($this->get('subcategory') && $this->get('subcategory') != '') {
                        $where .= ' AND c.subcategory=' . $this->get('subcategory');
                    }
                }
            } else if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND c.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND c.subcategory=' . $this->get('subcategory');
                }
            }
        }

        $join = " LEFT JOIN tbl_languages l on l.id = c.language_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_fun_n_learn c $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT c.*,l.language, (select count(id) from tbl_fun_n_learn_question q where q.fun_n_learn_id = c.id ) as no_of_que FROM tbl_fun_n_learn c $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $operate = "<a class='btn btn-icon btn-sm btn-warning' href='" . base_url() . "fun-n-learn-questions/" . $row->id . "' title='" . lang('add_question') . "'><i class='fas fa-plus'></i></a>";
            $operate .= '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= "<a class='btn btn-icon btn-sm btn-success edit-status' data-id='" . $row->id . "' data-toggle='modal' data-target='#editStatusModal' title='" . lang('edit_status') . "'><i class='fas fa-edit'></i></a>";
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['language'] = $row->language;
            $tempRow['title'] = $row->title;
            $tempRow['content_type'] = $row->content_type;
            $tempRow['content_data'] = $row->content_data;
            $tempRow['detail'] = $row->detail;
            $tempRow['no_of_que'] = $row->no_of_que;
            $tempRow['status'] = ($row->status) ? "<label class='badge badge-success'>" . lang('active') . "</label>" : "<label class='badge badge-danger'>" . lang('deactive') . "</label>";
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function battle_statistics_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = $where_sub = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('user_id')) {
            $user_id = $this->get('user_id');
            $where = " WHERE user_id1 = $user_id or user_id2 = $user_id";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (r.id like '%" . $search . "%' OR u.name like '%" . $search . "%'  OR u.email like '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT COUNT(id) as total FROM tbl_battle_statistics $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT *, (SELECT name FROM tbl_users u WHERE u.id = m.user_id1) AS user_1, (SELECT name FROM tbl_users u WHERE u.id = m.user_id2) AS user_2 FROM tbl_battle_statistics m $where GROUP BY date_created ORDER BY $sort $order LIMIT $offset, $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;

        foreach ($res1 as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['opponent_id'] = ($row->user_id1 == $user_id) ? $row->user_id2 : $row->user_id1;
            $tempRow['opponent_name'] = ($row->user_id1 == $user_id) ? $row->user_2 : $row->user_1;

            if ($row->is_drawn == 1) {
                $tempRow['mystatus'] = lang('draw');
            } else {
                $tempRow['mystatus'] = ($row->winner_id == $user_id) ? lang('won') : lang('lost');
            }
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function daily_leaderboard_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'r.user_rank';
        $order = 'ASC';
        $where = $where_sub = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        $where_sub = " WHERE ( DATE(date_created) = DATE('" . $this->toDate . "') ) ";

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (r.id like '%" . $search . "%' OR u.name like '%" . $search . "%'  OR u.email like '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT COUNT(r.id) AS total FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM (SELECT id,user_id, score, date_created  FROM tbl_leaderboard_daily d $where_sub) s, (SELECT @user_rank := 0) init ORDER BY score DESC) r INNER join tbl_users u on u.id = r.user_id $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT r.*,u.email,u.name,u.profile FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM (SELECT id,user_id, score, date_created  FROM tbl_leaderboard_daily d $where_sub) s, (SELECT @user_rank := 0) init ORDER BY score DESC, date_created ASC) r INNER join tbl_users u on u.id = r.user_id $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;

        foreach ($res1 as $row) {
            $tempRow['id'] = $count;
            $tempRow['user_id'] = $row->user_id;
            $tempRow['name'] = $row->name;
            $tempRow['email'] = $row->email;
            $tempRow['score'] = $row->score;
            $tempRow['user_rank'] = $row->user_rank;
            $tempRow['date_created'] = date('d-M-Y h:i A', strtotime($row->date_created));
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function monthly_leaderboard_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'r.user_rank';
        $order = 'ASC';
        $where = $where1 = $where_sub = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('year') && $this->get('year') != '') {
            $year = $this->get('year');
            $where1 = " WHERE (YEAR(m.date_created) = '" . $year . "') ";
            $where_sub = " WHERE (YEAR(m.date_created) = '" . $year . "') ";
            if ($this->get('month') && $this->get('month') != '') {
                $month = $this->get('month');
                $where1 .= " AND (MONTH(m.date_created) = '" . $month . "') ";
                $where_sub .= " AND (MONTH(m.date_created) = '" . $month . "') ";
            }
        } else if ($this->get('month') && $this->get('month') != '') {
            $month = $this->get('month');
            $where1 = " WHERE ( MONTH(m.date_created) = '" . $month . "') ";
            $where_sub = " WHERE ( MONTH(m.date_created) = '" . $month . "') ";
        }

        if ($this->get('user_id')) {
            $user_id = $this->get('user_id');
            if ($this->get('user_id') != '')
                $where1 .= " AND user_id=" . $user_id;
            $where .= " AND user_id=" . $user_id;
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where1 .= " AND (u.name like '%" . $search . "%' OR u.email like '%" . $search . "%' )";
            $where .= " AND (u.name like '%" . $search . "%' OR u.email like '%" . $search . "%' )";
        }


        $query = $this->db->query("SELECT COUNT(*) AS total FROM tbl_leaderboard_monthly m INNER JOIN tbl_users u ON m.user_id=u.id $where_sub $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT u.email,u.name,u.profile,r.* FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT m.id, user_id, sum(score) score,last_updated,date_created, MAX(last_updated) as latest_updated FROM tbl_leaderboard_monthly m join tbl_users u on u.id = m.user_id $where_sub GROUP BY user_id) s, (SELECT @user_rank := 0) init ORDER BY score DESC, latest_updated ASC ) r INNER join tbl_users u on u.id = r.user_id $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;

        foreach ($res1 as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['name'] = $row->name;
            $tempRow['email'] = $row->email;
            $tempRow['user_id'] = $row->user_id;
            $tempRow['score'] = $row->score;
            $tempRow['user_rank'] = $row->user_rank;
            $tempRow['last_updated'] = date("d-m-Y H:m:s", strtotime($row->last_updated));
            $tempRow['date_created'] = date("d-m-Y H:m:s", strtotime($row->date_created));
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function global_leaderboard_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'r.user_rank';
        $order = 'ASC';
        $where = $where_sub = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (r.id like '%" . $search . "%' OR u.name like '%" . $search . "%' OR u.email like '%" . $search . "%' )";
        }

        $query = $this->db->query("SELECT count(r.user_id) as total FROM ( SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT m.id, user_id, sum(score) score, MAX(last_updated) as latest_updated FROM tbl_leaderboard_monthly m join tbl_users u on u.id = m.user_id GROUP BY user_id) s, (SELECT @user_rank := 0) init ORDER BY score DESC, latest_updated ASC) r INNER join tbl_users u on u.id = r.user_id $where");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT r.*, u.email,u.name FROM ( SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT m.id, user_id, sum(score) score, MAX(last_updated) as latest_updated FROM tbl_leaderboard_monthly m join tbl_users u on u.id = m.user_id GROUP BY user_id) s, (SELECT @user_rank := 0) init ORDER BY score DESC, latest_updated ASC) r INNER join tbl_users u on u.id = r.user_id $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;

        foreach ($res1 as $row) {
            $tempRow['id'] = $count;
            $tempRow['user_id'] = $row->user_id;
            $tempRow['name'] = $row->name;
            $tempRow['email'] = $row->email;
            $tempRow['score'] = $row->score;
            $tempRow['user_rank'] = $row->user_rank;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function contest_leaderboard_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = $where_sub = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        $contest_id = $this->get('contest_id');
        $where = " WHERE contest_id=" . $contest_id . "";
        $where_sub = " WHERE contest_id = '" . $contest_id . "'";

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (`user_id` like '%" . $search . "%' OR `score` like '%" . $search . "%')";
        }


        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_contest_leaderboard  as r $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT r.*,u.name,u.profile FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT c.* FROM tbl_contest_leaderboard c join tbl_users u on u.id = c.user_id  $where_sub ) s, (SELECT @user_rank := 0) init ORDER BY score DESC, last_updated ASC) r INNER join tbl_users u on u.id = r.user_id $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['name'] = $row->name;
            $tempRow['user_id'] = $row->user_id;
            $tempRow['contest_id'] = $row->contest_id;
            $tempRow['questions_attended'] = $row->questions_attended;
            $tempRow['correct_answers'] = $row->correct_answers;
            $tempRow['score'] = $row->score;
            $tempRow['user_rank'] = $row->user_rank;
            $tempRow['last_updated'] = $row->last_updated;
            $tempRow['date_created'] = $row->date_created;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function contest_question_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('contest_id') && $this->get('contest_id') != '') {
            $where = ' WHERE tq.contest_id=' . $this->get('contest_id');
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (q.`id` like '%" . $search . "%' OR `question` like '%" . $search . "%' OR `optiona` like '%" . $search . "%' OR `optionb` like '%" . $search . "%' OR `optionc` like '%" . $search . "%' OR `optiond` like '%" . $search . "%' OR `answer` like '%" . $search . "%')";
            if ($this->get('contest_id') && $this->get('contest_id') != '') {
                $where .= ' AND tq.contest_id=' . $this->get('contest_id');
            }
        }

        $join = " JOIN tbl_contest tc ON tc.id = tq.contest_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_contest_question tq $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT tq.*, tc.name FROM tbl_contest_question tq $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? CONTEST_QUESTION_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['contest_id'] = $row->contest_id;
            $tempRow['name'] = $row->name;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('question_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['question'] = $row->question;
            $tempRow['question_type'] = $row->question_type;
            $tempRow['optiona'] = $row->optiona;
            $tempRow['optionb'] = $row->optionb;
            $tempRow['optionc'] = $row->optionc;
            $tempRow['optiond'] = $row->optiond;
            $tempRow['optione'] = $row->optione;
            $tempRow['answer'] = $row->answer;
            $tempRow['note'] = $row->note;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function contest_prize_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        $contest_id = $this->get('contest_id');
        $where = " WHERE p.contest_id=" . $contest_id . "";

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (`id` like '%" . $search . "%' OR `points` like '%" . $search . "%' )";
        }

        $join = " JOIN tbl_contest c ON c.id = p.contest_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_contest_prize p $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT p.*, c.name FROM tbl_contest_prize p $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' ><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['name'] = $row->name;
            $tempRow['top_winner'] = $row->top_winner;
            $tempRow['points'] = $row->points;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function contest_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('language_id')) {
            $language_id = $this->get('language_id');
            $where = 'where language_id = ' . $language_id;
        } else {
            $where = '';
        }


        if ($this->get('search')) {
            $search = $this->get('search');
            if (isset($language_id) && !empty($language_id)) {
                $where .= " AND (id like '%" . $search . "%' OR name like '%" . $search . "%' OR description like '%" . $search . "%')";
            } else {
                $where = " WHERE (id like '%" . $search . "%' OR name like '%" . $search . "%' OR description like '%" . $search . "%')";
            }
        }

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_contest c $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT c.*,(select count(contest_id) FROM tbl_contest_prize cp WHERE cp.contest_id=c.id) as top_users,(SELECT COUNT('id') from tbl_contest_leaderboard cl where cl.contest_id = c.id ) as participants,(SELECT COUNT('id') from tbl_contest_question q where q.contest_id=c.id) as total_question FROM tbl_contest c $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? CONTEST_IMG_PATH . $row->image : '';
            $operate = "<a class='btn btn-icon btn-sm btn-primary edit-data' data-id='" . $row->id . "' data-image='" . $image . "' data-toggle='modal' data-target='#editDataModal' title='" . lang('edit') . "'><i class='fas fa-edit'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-success edit-status' data-id='" . $row->id . "' data-toggle='modal' data-target='#editStatusModal' title='" . lang('edit_status') . "'><i class='fas fa-edit'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-danger delete-data' data-id='" . $row->id . "' data-image='" . $image . "' title='" . lang('delete') . "'><i class='fas fa-trash'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-warning' href='" . base_url() . "contest-leaderboard/" . $row->id . "' title='" . lang('view_top_users') . "'><i class='fas fa-list'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-info' href='contest-prize-distribute/" . $row->id . "' title='" . lang('ready_to_distribute_prize') . "'><i class='fas fa-bullhorn'></i></a>";

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['name'] = $row->name;
            $tempRow['start_date'] = $row->start_date;
            $tempRow['end_date'] = $row->end_date;
            $tempRow['image'] = "<a data-fancybox='" . lang('contest_gallery') . "' href='" . $image . "' data-lightbox='" . $row->name . "'><img src='" . $image . "' title='" . $row->name . "' width='50'/></a>";
            $tempRow['description'] = $row->description;
            $tempRow['entry'] = $row->entry;
            $tempRow['top_users'] = '<a class="btn btn-xs btn-warning" href="' . base_url() . 'contest-prize/' . $row->id . '" title="' . lang('view_prize') . '">' . $row->top_users . '</a>';
            $tempRow['participants'] = $row->participants;
            $tempRow['total_question'] = $row->total_question;
            $tempRow['status'] = ($row->status) ? "<label class='badge badge-success'>" . lang('active') . "</label>" : "<label class='badge badge-danger'>" . lang('deactive') . "</label>";
            $tempRow['prize_status'] = ($row->prize_status == 0) ? '<label class="badge badge-warning">' . lang('not_distributed') . '</label>' : '<label class="badge badge-success">' . lang('distributed') . '</label>';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function user_account_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'auth_id';
        $order = 'DESC';
        $where = ' WHERE status=0';
        $table = $this->get('table');

        if ($this->post('id'))
            $id = $this->post('id');

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (`auth_id` like '%" . $search . "%' OR `auth_username` like '%" . $search . "%' OR `role` like '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_authenticate $where");
        $res = $query->result();

        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT * FROM tbl_authenticate $where  ORDER BY  $sort  $order  LIMIT  $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res1 as $row) {
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->auth_id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->auth_id . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->auth_id;
            $tempRow['auth_username'] = $row->auth_username;
            $tempRow['role'] = $row->role;
            $tempRow['permissions'] = json_decode($row->permissions, 1);
            $tempRow['created'] = date('d-m-Y H:m a', strtotime($row->created));
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function notification_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';
        $table = $this->get('table');

        if ($this->post('id'))
            $id = $this->post('id');

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = "where (`id` like '%" . $search . "%' OR `title` like '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_notifications n $where");
        $res = $query->result();

        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT * FROM tbl_notifications n $where  ORDER BY  $sort  $order  LIMIT  $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? NOTIFICATION_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image=' . $image . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['title'] = $row->title;
            $tempRow['message'] = $row->message;
            $tempRow['users'] = ucwords($row->users);
            $tempRow['type'] = ucwords($row->type);
            $tempRow['type_id'] = ucwords($row->type_id);
            $tempRow['date_sent'] = date('d-m-Y', strtotime($row->date_sent));
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('image') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function users_get()
    {

        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('status') != '') {
            $status = $this->get('status');
            $where = " WHERE (`status` = " . $status . ")";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (id like '%" . $search . "%' OR name like '%" . $search . "%' OR mobile like '%" . $search . "%' OR email like '%" . $search . "%' OR date_registered like '%" . $search . "%' )";
            if ($this->get('status') != '') {
                $status = $this->get('status');
                $where .= " AND (`status` = " . $status . ")";
            }
        }

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_users $where ");
        $res1 = $query->result();
        foreach ($res1 as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT * FROM tbl_users  $where  ORDER BY  $sort  $order  LIMIT  $offset , $limit");
        $res = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;

        $icon = array(
            'email' => 'far fa-envelope-open',
            'gmail' => 'fab fa-google-plus-square text-danger',
            'fb' => 'fab fa-facebook-square text-primary',
            'mobile' => 'fa fa-phone-square',
            'apple' => 'fab fa-apple'
        );

        foreach ($res as $row) {
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= "<a class='btn btn-icon btn-sm btn-success' href='" . base_url() . "monthly-leaderboard/" . $row->id . "' title='" . lang('monthly_leaderboard') . "'><i class='fas fa-th'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-info' href='" . base_url() . "battle-statistics/" . $row->id . "' title='" . lang('user_statistics') . "'><i class='fas fa-chart-pie'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-warning' href='" . base_url() . "activity-tracker/" . $row->id . "' title='" . lang('track_activities') . "'><i class='far fa-chart-bar'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-primary' href='" . base_url() . "payment-requests/" . $row->id . "' title='" . lang('track_activities') . "'><i class='fas fa-rupee-sign'></i></a>";
            $operate .= "<a class='btn btn-icon btn-sm btn-info admin-coin' data-id='" . $row->id . "' data-toggle='modal' data-target='#coinsmodal' title='" . lang('coins') . "'><i class='fas fa-coins'></i></a>";
            //            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' ><i class="fa fa-trash"></i></a>';
            $operate .= "<a class='btn btn-icon btn-sm btn-success' href='" . base_url() . "in-app-users/" . $row->id . "' title='" . lang('in_app_history') . "'><i class='fas fa-database'></i></a>";

            if (filter_var($row->profile, FILTER_VALIDATE_URL) === FALSE) {
                // Not a valid URL. Its a image only or empty
                $profile = (!empty($row->profile)) ? base_url() . USER_IMG_PATH . $row->profile : '';
            } else {
                /* if it is a ur than just pass url as it is */
                $profile = $row->profile;
            }


            // $profile = base_url().'images/profile/'.$row->profile;


            $tempRow['id'] = $row->id;
            $tempRow['profile'] = (!empty($row->profile)) ? "<a data-lightbox='" . lang('profile_picture') . "' href='" . $profile . "'><img src='" . $profile . "' width='80'/></a>" : lang('no_image');
            $tempRow['name'] = $row->name;
            $tempRow['email'] =   $row->email;
            $tempRow['mobile'] =  $row->mobile;
            $tempRow['type'] = (isset($row->type) && $row->type != '') ? '<em class="' . $icon[trim($row->type)] . ' fa-2x"></em>' : '<em class="' . $icon['email'] . ' fa-2x"></em>';
            $tempRow['fcm_id'] = $row->fcm_id;
            $tempRow['coins'] = $row->coins;
            $tempRow['refer_code'] = $row->refer_code;
            $tempRow['friends_code'] = $row->friends_code;
            $tempRow['remove_ads'] = ($row->remove_ads) ? "<label class='badge badge-success'>" . lang('yes') . "</label>" : "<label class='badge badge-danger'>" . lang('no') . "</label>";
            $tempRow['date_registered'] = date('d-M-Y h:i A', strtotime($row->date_registered));
            $tempRow['status'] = ($row->status) ? "<label class='badge badge-success'>" . lang('active') . "</label>" : "<label class='badge badge-danger'>" . lang('deactive') . "</label>";
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function question_reports_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (qr.id like '%" . $search . "%' OR message like '%" . $search . "%' OR u.name like '%" . $search . "%' )";
        }

        $join = " JOIN tbl_users u ON u.id = qr.user_id";
        $join .= " JOIN tbl_question q ON q.id = qr.question_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_question_reports qr $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT qr.*, u.name, q.category, q.subcategory, q.language_id, q.image, q.question, q.question_type, q.optiona, q.optionb, q.optionc, q.optiond, q.optione, q.answer, q.level, q.note FROM tbl_question_reports qr $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? QUESTION_IMG_PATH . $row->image : '';
            // $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" href="' . base_url() . "question-reports/" . $row->question_id . '" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['question_id'] = $row->question_id;
            $tempRow['question'] = $row->question;
            $tempRow['user_id'] = $row->user_id;
            $tempRow['name'] = $row->name;
            $tempRow['message'] = $row->message;
            $tempRow['date'] = date('d-M-Y h:i A', strtotime($row->date));

            $tempRow['image_url'] = $image;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['question_type'] = $row->question_type;
            if (is_settings('latex_mode')) {
                $question = "<textarea class='editor-questions-inline'>" . $row->question . "</textarea>";
            } else {
                $question = $row->question;
            }
            $tempRow['question'] = $question ?? '';
            $tempRow['level'] = $row->level;
            $tempRow['answer'] = $row->answer;
            $tempRow['operate'] = $operate;

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function question_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('language') && $this->get('language') != '') {
            $where = " WHERE q.language_id=" . $this->get('language') . "";
            if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        } else if ($this->get('category') && $this->get('category') != '') {
            $where = ' WHERE q.category=' . $this->get('category');
            if ($this->get('subcategory') && $this->get('subcategory') != '') {
                $where .= ' AND q.subcategory=' . $this->get('subcategory');
            }
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (q.`id` like '%" . $search . "%' OR `question` like '%" . $search . "%' OR `optiona` like '%" . $search . "%' OR `optionb` like '%" . $search . "%' OR `optionc` like '%" . $search . "%' OR `optiond` like '%" . $search . "%' OR `answer` like '%" . $search . "%')";
            if ($this->get('language') && $this->get('language') != '') {
                $where .= " AND q.language_id=" . $this->get('language') . "";
                if ($this->get('category') && $this->get('category') != '') {
                    $where .= ' AND q.category=' . $this->get('category');
                    if ($this->get('subcategory') && $this->get('subcategory') != '') {
                        $where .= ' AND q.subcategory=' . $this->get('subcategory');
                    }
                }
            } else if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        }

        $join = " LEFT JOIN tbl_languages l ON l.id = q.language_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_question q $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT q.*, l.language FROM tbl_question q $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? QUESTION_IMG_PATH . $row->image : '';
            // $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" href="' . base_url() . "create-questions/" . $row->id . '" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('question_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['question_type'] = $row->question_type;
            if (is_settings('latex_mode')) {
                $question = "<textarea class='editor-questions-inline'>" . $row->question . "</textarea>";
                $optiona = "<textarea class='editor-questions-inline'>" . $row->optiona . "</textarea>";
                $optionb = "<textarea class='editor-questions-inline'>" . $row->optionb . "</textarea>";
                $optionc = $row->optionc ? "<textarea class='editor-questions-inline'>" . $row->optionc . "</textarea>" : "-";
                $optiond = $row->optiond ? "<textarea class='editor-questions-inline'>" . $row->optiond . "</textarea>" : "-";
                $optione = $row->optione ? "<textarea class='editor-questions-inline'>" . $row->optione . "</textarea>" : "-";
                $note = $row->note ? "<textarea class='editor-questions-inline'>" . $row->note . "</textarea>" : "-";
            } else {
                $question = $row->question;
                $optiona = $row->optiona;
                $optionb = $row->optionb;
                $optionc = $row->optionc ? $row->optionc : "-";
                $optiond = $row->optiond ? $row->optiond : "-";
                $optione = $row->optione ? $row->optione : "-";
                $note = $row->note ? $row->note : "-";
            }
            $tempRow['question'] = $question ?? '';
            $tempRow['optiona'] = $optiona;
            $tempRow['optionb'] = $optionb;
            $tempRow['optionc'] = $optionc;
            $tempRow['optiond'] = $optiond;
            $tempRow['optione'] = $optione;
            $tempRow['note'] = $note;
            $tempRow['level'] = $row->level;
            $tempRow['answer'] = $row->answer;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function audio_question_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('language') && $this->get('language') != '') {
            $where = " WHERE q.language_id=" . $this->get('language') . "";
            if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        } else if ($this->get('category') && $this->get('category') != '') {
            $where = ' WHERE q.category=' . $this->get('category');
            if ($this->get('subcategory') && $this->get('subcategory') != '') {
                $where .= ' AND q.subcategory=' . $this->get('subcategory');
            }
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (q.`id` like '%" . $search . "%' OR `question` like '%" . $search . "%' OR `optiona` like '%" . $search . "%' OR `optionb` like '%" . $search . "%' OR `optionc` like '%" . $search . "%' OR `optiond` like '%" . $search . "%' OR `answer` like '%" . $search . "%')";
            if ($this->get('language') && $this->get('language') != '') {
                $where .= " AND q.language_id=" . $this->get('language') . "";
                if ($this->get('category') && $this->get('category') != '') {
                    $where .= ' AND q.category=' . $this->get('category');
                    if ($this->get('subcategory') && $this->get('subcategory') != '') {
                        $where .= ' AND q.subcategory=' . $this->get('subcategory');
                    }
                }
            } else if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        }

        $join = " LEFT JOIN tbl_languages l ON l.id = q.language_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_audio_question q $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT q.*, l.language FROM tbl_audio_question q $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            if ($row->audio_type != '1') {
                $path = base_url() . QUESTION_AUDIO_PATH;
            } else {
                $path = "";
            }
            $audio_url = (!empty($row->audio) && $row->audio_type == 2) ? QUESTION_AUDIO_PATH . $row->audio : $row->audio;
            $audio = (!empty($row->audio)) ? (($row->audio_type == 2) ? $path . $row->audio : $row->audio) : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-audio="' . QUESTION_AUDIO_PATH . $row->audio . '"><i class="fa fa-trash"></i></a>';

            $tempRow['audio_url'] = $audio_url;
            $tempRow['id'] = $row->id;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['audio'] = '<audio id=' . $row->id . '><source src="' . $audio . '" type="audio/mpeg"></audio><a data-id=' . $row->id . ' class="btn btn-icon btn-sm btn-primary playbtn fa fa-play"></a>';
            $tempRow['question'] = $row->question;
            $tempRow['question_type'] = $row->question_type;
            $tempRow['audio_type'] = $row->audio_type;
            $tempRow['optiona'] = $row->optiona;
            $tempRow['optionb'] = $row->optionb;
            $tempRow['optionc'] = $row->optionc;
            $tempRow['optiond'] = $row->optiond;
            $tempRow['optione'] = $row->optione;
            $tempRow['answer'] = $row->answer;
            $tempRow['note'] = $row->note;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function subcategory_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'row_order';
        $order = 'ASC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        $type_name = $this->get('type');

        $type = $this->category_type[$type_name];
        $where = ' WHERE c.type=' . $type;

        if ($this->get('language') && $this->get('language') != '') {
            $where .= " AND s.language_id=" . $this->get('language') . "";
            if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND s.maincat_id=' . $this->get('category');
            }
        } else if ($this->get('category') && $this->get('category') != '') {
            $where = ' WHERE s.maincat_id=' . $this->get('category');
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (s.id like '%" . $search . "%' OR s.subcategory_name like '%" . $search . "%' OR l.`language` like '%" . $search . "%' OR c.`category_name` like '%" . $search . "%')";
        }

        $join = " LEFT JOIN tbl_languages l ON l.id = s.language_id";
        $join .= " JOIN tbl_category c ON c.id = s.maincat_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_subcategory s $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        if ($type == 2) {
            $no_of_que = ', (select count(id) from tbl_fun_n_learn q where q.subcategory = s.id ) as no_of_que';
        } else if ($type == 3) {
            // $no_of_que = ', (select count(id) from tbl_guess_the_word q where q.subcategory = s.id ) as no_of_que';
            $no_of_que = ', (select count(id) from tbl_guess_the_word q where q.subcategory = s.id ) as no_of_que';
        } else if ($type == 4) {
            $no_of_que = ', (select count(id) from tbl_audio_question q where q.subcategory = s.id ) as no_of_que';
        } else if ($type == 5) {
            $no_of_que = ', (select count(id) from tbl_maths_question q where q.subcategory = s.id ) as no_of_que';
        } else if ($type == 6) {
            $no_of_que = ', (select count(id) from tbl_multi_match q where q.subcategory = s.id ) as no_of_que';
        } else {
            $no_of_que = ',(select count(id) from tbl_question q where q.subcategory=s.id ) as no_of_que';
        }

        $query1 = $this->db->query("SELECT s.*,l.language,c.category_name $no_of_que FROM tbl_subcategory s $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();





        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? SUBCATEGORY_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['maincat_id'] = $row->maincat_id;
            $tempRow['category_name'] = $row->category_name;
            $tempRow['row_order'] = $row->row_order;
            $tempRow['subcategory_name'] = $row->subcategory_name;
            $tempRow['slug'] = $row->slug;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('subcategory_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : '<img src=' . $this->NO_IMAGE . ' height=30 >';
            $tempRow['is_premium'] = $row->is_premium;
            $tempRow['coins'] = $row->coins;
            $tempRow['status'] = ($row->status) ? "<span class='badge badge-success'>" . lang('active') . "</span>" : "<span class='badge badge-danger'>" . lang('deactive') . "</span>";
            $tempRow['no_of_que'] = $row->no_of_que;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function category_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'row_order';
        $order = 'ASC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        $type_name = $this->get('type');

        $type = $this->category_type[$type_name];



        $where = ' WHERE c.type=' . $type;

        if ($this->get('language') && $this->get('language') != '') {
            $language_id = $this->get('language');
            $where .= " AND c.language_id=" . $language_id . "";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (c.`id` like '%" . $search . "%' OR c.`category_name` like '%" . $search . "%' OR l.`language` like '%" . $search . "%' )";
        }

        $join = " LEFT JOIN tbl_languages l on l.id = c.language_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_category c $join $where");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        if ($type == 2) {
            $no_of_que = ', (select count(id) from tbl_fun_n_learn q where q.category = c.id ) as no_of_que';
        } else if ($type == 3) {
            $no_of_que = ', (select count(id) from tbl_guess_the_word q where q.category = c.id ) as no_of_que';
        } else if ($type == 4) {
            $no_of_que = ', (select count(id) from tbl_audio_question q where q.category = c.id ) as no_of_que';
        } else if ($type == 5) {
            $no_of_que = ', (select count(id) from tbl_maths_question q where q.category = c.id ) as no_of_que';
        } else if ($type == 6) {
            $no_of_que = ', (select count(id) from tbl_multi_match q where q.category = c.id ) as no_of_que';
        } else {
            $no_of_que = ', (select count(id) from tbl_question q where q.category = c.id ) as no_of_que';
        }

        $query1 = $this->db->query("SELECT c.*,l.language $no_of_que FROM tbl_category c $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;





        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? CATEGORY_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['row_order'] = $row->row_order;
            $tempRow['category_name'] = $row->category_name;
            $tempRow['slug'] = $row->slug;
            $tempRow['image'] = (!empty($image)) ? '<a href=' . base_url() . $image . '  data-lightbox="' . lang('category_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : '<img src=' . $this->NO_IMAGE . ' height=30 >';
            $tempRow['is_premium'] = $row->is_premium;
            $tempRow['coins'] = $row->coins;
            $tempRow['no_of_que'] = $row->no_of_que;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function languages_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = ' WHERE type=1';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (id like '%" . $search . "%' OR language like '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_languages $where ");
        $res1 = $query->result();
        foreach ($res1 as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT * FROM tbl_languages  $where  ORDER BY  $sort  $order  LIMIT  $offset , $limit");
        $res = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;

        foreach ($res as $row) {
            $operate = "";
            $operate .= '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            if ($row->default_active != 1) {
                $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' ><i class="fa fa-trash"></i></a>';
            }

            $tempRow['id'] = $row->id;
            $tempRow['language'] = $row->language;
            $tempRow['code'] = $row->code;
            $tempRow['status'] = $row->status;
            $tempRow['default_active'] = $row->default_active;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function coin_store_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (id like '%" . $search . "%' OR title like '%" . $search . "%' OR coins like '%" . $search . "%' OR product_id like '%" . $search . "%' OR description like '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_coin_store $where");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }


        $query1 = $this->db->query("SELECT * FROM tbl_coin_store $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? COIN_STORE_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary status-update" data-id=' . $row->id . ' data-toggle="modal" data-target="#updateStatusModal" title="' . lang('edit') . '"><i class="fa fa-toggle-on"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image=' . $image . ' ><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['type'] = $row->type;
            $tempRow['title'] = $row->title;
            $tempRow['coins'] = $row->coins;
            $tempRow['product_id'] = $row->product_id;
            $tempRow['description'] = $row->description;
            $tempRow['image_url'] = $image;
            $tempRow['status'] = ($row->status) ? "<label class='badge badge-success'>" . lang('active') . "</label>" : "<label class='badge badge-danger'>" . lang('deactive') . "</label>";
            $tempRow['status_db'] = $row->status;
            $tempRow['image'] = (!empty($image)) ? '<a href=' . base_url() . $image . '  data-lightbox="Coin Store Images"><img src=' . base_url() . $image . ' height=50, width=50 >' : '<img src=' . $this->NO_IMAGE . ' height=30 >';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }
    public function question_without_premium_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';
        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');
        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        $join = " LEFT JOIN tbl_languages l ON l.id = q.language_id LEFT JOIN tbl_category as c on c.id = q.category LEFT JOIN tbl_subcategory as sc on sc.id = q.subcategory where c.is_premium = 0";
        if ($this->get('language') && $this->get('language') != '') {
            $where = " AND q.language_id=" . $this->get('language') . "";
            if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        } else if ($this->get('category') && $this->get('category') != '') {
            $where = ' AND q.category=' . $this->get('category');
            if ($this->get('subcategory') && $this->get('subcategory') != '') {
                $where .= ' AND q.subcategory=' . $this->get('subcategory');
            }
        }
        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (q.id like '%" . $search . "%' OR q.question like '%" . $search . "%' OR q.optiona like '%" . $search . "%' OR q.optionb like '%" . $search . "%' OR q.optionc like '%" . $search . "%' OR q.optiond like '%" . $search . "%' OR q.optione like '%" . $search . "%' OR answer like '%" . $search . "%')";
            if ($this->get('language') && $this->get('language') != '') {
                $where .= " AND q.language_id=" . $this->get('language') . "";
                if ($this->get('category') && $this->get('category') != '') {
                    $where .= ' AND q.category=' . $this->get('category');
                    if ($this->get('subcategory') && $this->get('subcategory') != '') {
                        $where .= ' AND q.subcategory=' . $this->get('subcategory');
                    }
                }
            } else if ($this->get('category') && $this->get('category') != '') {
                $where .= ' AND q.category=' . $this->get('category');
                if ($this->get('subcategory') && $this->get('subcategory') != '') {
                    $where .= ' AND q.subcategory=' . $this->get('subcategory');
                }
            }
        }
        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_question q $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }
        $query1 = $this->db->query("SELECT q.*, l.language FROM tbl_question q $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? QUESTION_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" data-id=' . $row->id . ' data-toggle="modal" data-target="#editDataModal" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';
            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('question_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['question_type'] = $row->question_type;
            if (is_settings('latex_mode')) {
                $question = "<textarea class='editor-questions-inline'>" . $row->question . "</textarea>";
            } else {
                $question = $row->question;
            }
            $tempRow['question'] = $question ?? '';
            $tempRow['answer'] = $row->answer;
            $tempRow['level'] = $row->level;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }
        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }


    public function web_home_settings_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        $where = ' WHERE w.id!=0';

        if ($this->get('language') && $this->get('language') != '') {
            $language_id = $this->get('language');
            $where .= " AND w.language_id=" . $language_id . "";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (w.`id` like '%" . $search . "%' OR l.`language` like '%" . $search . "%' )";
        }

        $join = " LEFT JOIN tbl_languages l on l.id = w.language_id";
        $settings = [
            'section1_heading',
            'section1_title1',
            'section1_title2',
            'section1_title3',
            'section1_image1',
            'section1_image2',
            'section1_image3',
            'section1_desc1',
            'section1_desc2',
            'section1_desc3',
            'section2_heading',
            'section2_title1',
            'section2_title2',
            'section2_title3',
            'section2_title4',
            'section2_desc1',
            'section2_desc2',
            'section2_desc3',
            'section2_desc4',
            'section2_image1',
            'section2_image2',
            'section2_image3',
            'section2_image4',
            'section3_heading',
            'section3_title1',
            'section3_title2',
            'section3_title3',
            'section3_title4',
            'section3_image1',
            'section3_image2',
            'section3_image3',
            'section3_image4',
            'section3_desc1',
            'section3_desc2',
            'section3_desc3',
            'section3_desc4'
        ];

        $settings = "'" . implode("','", $settings) . "'"; // Convert array to string

        $where .= " AND w.`type` IN ($settings)"; // Add the condition to your where clause        

        $query = $this->db->query("SELECT w.*,l.language FROM tbl_web_settings w $join $where GROUP BY language_id ORDER BY $sort $order LIMIT $offset , $limit");
        $res = $query->result();

        // HAVE TO DO JUGAR AS it was not working with the expected code
        $query1 = $this->db->query("SELECT w.id FROM tbl_web_settings w $join $where GROUP BY language_id");
        $total = $query1->num_rows();

        $bulkData = array();

        $rows = array();
        $tempRow = array();
        $count = 1;

        foreach ($res as $row) {
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" href="' . base_url() . "web-home-settings/" . $row->language_id . '" data-id=' . $row->id . 'title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            // $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['no'] = $count;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }
        $bulkData['total'] = $total;
        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }


    public function badge_settings_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        $where = ' WHERE b.id!=0';

        if ($this->get('language') && $this->get('language') != '') {
            $language_id = $this->get('language');
            $where .= " AND b.language_id=" . $language_id . "";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where .= " AND (b.`id` like '%" . $search . "%' OR l.`language` like '%" . $search . "%' )";
        }

        $join = " LEFT JOIN tbl_languages l on l.id = b.language_id";
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

        $badges = "'" . implode("','", $badges) . "'"; // Convert array to string

        $where .= " AND b.`type` IN ($badges)"; // Add the condition to your where clause        

        // HAVE TO DO JUGAR AS it was not working with the expected code 
        $query1 = $this->db->query("SELECT b.id as total FROM tbl_badges b $join $where GROUP BY language_id");
        $total = $query1->num_rows();

        $query = $this->db->query("SELECT b.*,l.language FROM tbl_badges b $join $where GROUP BY language_id ORDER BY $sort $order LIMIT $offset , $limit");
        $res = $query->result();

        $bulkData = array();

        $rows = array();
        $tempRow = array();
        $count = 1;

        foreach ($res as $row) {
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" href="' . base_url() . "badges-settings/" . $row->language_id . '" data-id=' . $row->id . 'title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            // $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['no'] = $count;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }
        // $bulkData['total'] = count($rows);
        $bulkData['total'] = $total;
        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function in_app_user_list_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('user_id') && $this->get('user_id') != '') {
            $user_id = $this->get('user_id');
            $where = " WHERE t.user_id=" . $user_id . "";
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $where = " WHERE (t.id like '%" . $search . "%' OR u.name like '%" . $search . "%' OR t.amount like '%" . $search . "%' OR t.product_id like '%" . $search . "%' OR t.transaction_id like '%" . $search . "%' OR t.date like '%" . $search . "%')";
            if ($this->get('user_id') && $this->get('user_id') != '') {
                $user_id = $this->get('user_id');
                $where .= " AND t.user_id=" . $user_id . "";
            }
        }

        $join = " LEFT JOIN tbl_users u on u.id = t.user_id";

        $query = $this->db->query("SELECT COUNT(*) as total FROM tbl_users_in_app t $join $where ");
        $res = $query->result();
        foreach ($res as $row1) {
            $total = $row1->total;
        }

        $query1 = $this->db->query("SELECT t.*, u.name FROM tbl_users_in_app t $join $where ORDER BY $sort $order LIMIT $offset , $limit");
        $res1 = $query1->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;

        foreach ($res1 as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['name'] = $row->name;
            $tempRow['pay_from'] = $row->pay_from;
            $tempRow['product_id'] = $row->product_id;
            $tempRow['transaction_id'] = $row->transaction_id;
            $tempRow['amount'] = $row->amount;
            $tempRow['date'] = $row->date;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function system_langauge_get()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'language_name';
        $order = 'DESC';
        $where = '';

        if ($this->get('offset'))
            $offset = $this->get('offset');
        if ($this->get('limit'))
            $limit = $this->get('limit');

        if ($this->get('sort'))
            $sort = $this->get('sort');
        if ($this->get('order'))
            $order = $this->get('order');

        if ($this->get('search')) {
            $search = trim($this->get('search'));
        }

        $res = panel_languages();

        if (!empty($search)) {
            $res = array_filter($res, function ($item) use ($search) {
                return stripos($item, $search) !== false; // Case-insensitive search
            });
        }

        if ($sort == 'language_name') {
            if ($order == 'ASC') {
                sort($res);
            } else {
                rsort($res);
            }
        }

        // Pagination logic
        $totalRows = count($res);
        $slicedData = array_slice($res, $offset, $limit);

        $bulkData = array();
        $rows = array();
        $count = $offset + 1;
        $bulkData['total'] = $totalRows;

        $tempRow = array();
        foreach ($slicedData as $row) {
            $title = $row;
            $operate = '';
            $user_current_language = get_user_permissions($this->session->userdata('authId'))['language'] ?? 'english';
            if ($row != $user_current_language && ($row != 'en' && $row != 'english' && $row != 'sample_file')) {
                $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-lang=' . $row . '><i class="fa fa-trash"></i></a>';
            }
            $operate .= '<a class="btn btn-icon btn-sm btn-warning view-data" href="' . base_url() . "new-labels/" . $row . '" ><i class="fa fa-eye"></i></a>';

            $tempRow['no'] = $count;
            $tempRow['name'] = $row;
            $tempRow['title'] = $title;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }
        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function app_system_langauge_get()
    {
        $sort = $this->get('sort') ?? 'id';
        $order = $this->get('order') ?? 'DESC';

        $this->db->where('app_version!=', '0.0.0');
        $this->db->from('tbl_upload_languages');

        if ($this->get('search')) {
            $search = $this->get('search');
            $this->db->group_start()->like('id', $search)
                ->or_like('name', $search)->group_end();
        }

        $total = $this->db->count_all_results('', false);
        $this->db->order_by($sort, $order);
        if ($this->get('limit')) {
            $offset = $this->get('offset');
            $limit = $this->get('limit');
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        $res1 = $query->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $count = 1;
        $tempRow = array();
        foreach ($res1 as $row) {
            $file = $row->name;
            $operate = '';
            $operate .= '<a class="btn btn-icon btn-sm btn-primary edit-data" data-toggle="modal" data-target="#editDataModalApp" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            if ($row->app_default == 0) {
                $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-app-data" data-lang=' . $file . '><i class="fa fa-trash"></i></a>';
            }
            $operate .= '<a class="btn btn-icon btn-sm btn-warning view-data" href="' . base_url() . "new-labels/app/" . $file . '" ><i class="fa fa-eye"></i></a>';

            $tempRow['no'] = $count;
            $tempRow['name'] = $file;
            $tempRow['title'] = $row->title;
            $tempRow['app_default'] = $row->app_default;
            $tempRow['app_version'] = $row->app_version;
            $tempRow['app_status'] = $row->app_status;
            $tempRow['app_rtl_support'] = $row->app_rtl_support;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function web_system_langauge_get()
    {
        $sort = $this->get('sort') ?? 'id';
        $order = $this->get('order') ?? 'DESC';

        $this->db->where('web_version!=', '0.0.0');
        $this->db->from('tbl_upload_languages');

        if ($this->get('search')) {
            $search = $this->get('search');
            $this->db->group_start()->like('id', $search)
                ->or_like('name', $search)->group_end();
        }

        $total = $this->db->count_all_results('', false);
        $this->db->order_by($sort, $order);
        if ($this->get('limit')) {
            $offset = $this->get('offset');
            $limit = $this->get('limit');
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        $res1 = $query->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $count = 1;
        $tempRow = array();
        foreach ($res1 as $row) {
            $file = $row->name;
            $operate = '';
            $operate .= '<a class="btn btn-icon btn-sm btn-primary edit-data" data-toggle="modal" data-target="#editDataModalWeb" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            if ($row->web_default == 0) {
                $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-web-data" data-lang=' . $file . '><i class="fa fa-trash"></i></a>';
            }
            $operate .= '<a class="btn btn-icon btn-sm btn-warning view-data" href="' . base_url() . "new-labels/web/" . $file . '" ><i class="fa fa-eye"></i></a>';

            $tempRow['no'] = $count;
            $tempRow['name'] = $file;
            $tempRow['title'] = $row->title;
            $tempRow['web_default'] = $row->web_default;
            $tempRow['web_version'] = $row->web_version;
            $tempRow['web_status'] = $row->web_status;
            $tempRow['web_rtl_support'] = $row->web_rtl_support;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }


    public function multi_match_question_get()
    {
        $sort = $this->get('sort') ?? 'id';
        $order = $this->get('order') ?? 'DESC';

        $this->db->select('ms.*, l.language');
        $this->db->from('tbl_multi_match ms');
        $this->db->join('tbl_languages l', 'l.id = ms.language_id', 'left');

        if ($this->get('language') && $this->get('language') != '') {
            $language_id = $this->get('language');
            $this->db->where('ms.language_id', $language_id);
        }
        if ($this->get('category') && $this->get('category') != '') {
            $this->db->where('ms.category', $this->get('category'));
        }
        if ($this->get('subcategory') && $this->get('subcategory') != '') {
            $this->db->where('ms.subcategory', $this->get('subcategory'));
        }

        if ($this->get('search')) {
            $search = $this->get('search');
            $this->db->group_start()->like('ms.id', $search)
                ->or_like('question', $search)
                ->or_like('optiona', $search)
                ->or_like('optionb', $search)
                ->or_like('optionc', $search)
                ->or_like('optiond', $search)
                ->or_like('optione', $search)
                ->or_like('l.language', $search)
                ->group_end();
        }

        $total = $this->db->count_all_results('', false);
        $this->db->order_by($sort, $order);
        if ($this->get('limit')) {
            $offset = $this->get('offset');
            $limit = $this->get('limit');
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        $res1 = $query->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $count = 1;
        $tempRow = array();
        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? MULTIMATCH_QUESTION_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" href="' . base_url() . "multi-match/" . $row->id . '" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . ' data-image="' . $image . '"><i class="fa fa-trash"></i></a>';

            $tempRow['image_url'] = $image;
            $tempRow['id'] = $row->id;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['language'] = $row->language;
            $tempRow['image'] = (!empty($row->image)) ? '<a href=' . base_url() . $image . ' data-lightbox="' . lang('question_images') . '"><img src=' . base_url() . $image . ' height=50, width=50 >' : lang('no_image');
            $tempRow['question_type'] = $row->question_type;
            $tempRow['answer_type'] = $row->answer_type;

            $question = $row->question;
            $optiona = $row->optiona;
            $optionb = $row->optionb;
            $optionc = $row->optionc ? $row->optionc : "-";
            $optiond = $row->optiond ? $row->optiond : "-";
            $optione = $row->optione ? $row->optione : "-";
            $note = $row->note ? $row->note : "-";

            $tempRow['question'] = $question ?? '';
            $tempRow['optiona'] = $optiona;
            $tempRow['optionb'] = $optionb;
            $tempRow['optionc'] = $optionc;
            $tempRow['optiond'] = $optiond;
            $tempRow['optione'] = $optione;
            $tempRow['note'] = $note;
            $tempRow['level'] = $row->level;
            $tempRow['answer'] = $row->answer;
            $tempRow['operate'] = $operate;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }

    public function multi_match_question_reports_get()
    {
        $sort = $this->get('sort') ?? 'id';
        $order = $this->get('order') ?? 'DESC';

        $this->db->select('qr.*, u.name, q.category, q.subcategory, q.language_id, q.image, q.question, q.question_type, q.optiona, q.optionb, q.optionc, q.optiond, q.optione, q.answer, q.level, q.note');
        $this->db->from('tbl_multi_match_question_reports qr');
        $this->db->join('tbl_users u', 'u.id = qr.user_id');
        $this->db->join('tbl_multi_match q', 'q.id = qr.question_id');

        if ($this->get('search')) {
            $search = $this->get('search');
            $this->db->group_start()->like('qr.id', $search)
                ->or_like('u.name', $search)
                ->or_like('q.question', $search)
                ->or_like('q.optiona', $search)
                ->or_like('q.optionb', $search)
                ->or_like('q.optionc', $search)
                ->or_like('q.optiond', $search)
                ->or_like('q.optione', $search)
                ->or_like('l.language', $search)
                ->group_end();
        }

        $total = $this->db->count_all_results('', false);
        $this->db->order_by($sort, $order);
        if ($this->get('limit')) {
            $offset = $this->get('offset');
            $limit = $this->get('limit');
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        $res1 = $query->result();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $count = 1;
        $tempRow = array();

        foreach ($res1 as $row) {
            $image = (!empty($row->image)) ? MULTIMATCH_QUESTION_IMG_PATH . $row->image : '';
            $operate = '<a class="btn btn-icon btn-sm btn-primary edit-data" href="' . base_url() . "multi-match-question-reports/" . $row->question_id . '" title="' . lang('edit') . '"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-icon btn-sm btn-danger delete-data" data-id=' . $row->id . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['question_id'] = $row->question_id;
            $tempRow['question'] = $row->question;
            $tempRow['user_id'] = $row->user_id;
            $tempRow['name'] = $row->name;
            $tempRow['message'] = $row->message;
            $tempRow['date'] = date('d-M-Y h:i A', strtotime($row->date));

            $tempRow['image_url'] = $image;
            $tempRow['category'] = $row->category;
            $tempRow['subcategory'] = $row->subcategory;
            $tempRow['language_id'] = $row->language_id;
            $tempRow['question_type'] = $row->question_type;
            $question = $row->question;
            $tempRow['question'] = $question ?? '';
            $tempRow['level'] = $row->level;
            $tempRow['answer'] = $row->answer;
            $tempRow['operate'] = $operate;

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        echo json_encode($bulkData, JSON_UNESCAPED_UNICODE);
    }
}
