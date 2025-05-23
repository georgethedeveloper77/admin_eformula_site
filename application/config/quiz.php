<?php

defined('BASEPATH') or exit('No direct script access allowed');

$config['system_modules'] = [
    'users' => array('read', 'update'), //
    'languages' => array('create', 'read', 'update', 'delete'), //
    'categories' => array('create', 'read', 'update', 'delete'), //
    'subcategories' => array('create', 'read', 'update', 'delete'), //
    'category_order' => array('read', 'update'), //
    'questions' => array('create', 'read', 'update', 'delete'), //
    'daily_quiz' => array('read', 'update'), //
    'manage_contest' => array('create', 'read', 'update', 'delete'), //
    'manage_contest_question' => array('create', 'read', 'update', 'delete'), //
    'import_contest_question' => array('read', 'update'), //
    'fun_n_learn' => array('create', 'read', 'update', 'delete'), //
    'guess_the_word' => array('create', 'read', 'update', 'delete'), //
    'audio_question' => array('create', 'read', 'update', 'delete'), //
    'maths_questions' => array('create', 'read', 'update', 'delete'),
    'exam_module' => array('create', 'read', 'update', 'delete'), //
    'question_report' => array('read', 'update', 'delete'),
    'send_notification' => array('create', 'read', 'delete'),
    'import_question' => array('read', 'update'), //
    'system_configuration' => array('create', 'read', 'update'), //
    'system_languages' => array('read', 'create', 'update', 'delete'), //
    'in_app_settings' => array('create', 'read', 'update', 'delete'),
    'in_app_users' => array('read'), //
    'remove_image' => array('delete'),
    'authentication_settings' => array('read', 'update'), //
    'audio_question' => array('create', 'read', 'update', 'delete'),
    'coin_store_settings' => array('create', 'read', 'update', 'delete'),
    'users_accounts_rights' => array('create', 'read', 'update', 'delete'), //
    'leaderboard' => array('read'),
    'profile' => array('read'), //
    'reset_password' => array('read'), //
    'activity_tracker' => array('read'), //
    'payment_requests' => array('create', 'read'), //
    'system_utilities' => array('read', 'update'), //
    'firebase_configurations' => array('read', 'update'), //
    'payment_settings' => array('read', 'update'), //
    'ads_settings' => array('read', 'update'), //
    'badges_settings' => array('read', 'update'), //
    'about_us' => array('read', 'update'), //
    'contact_us' => array('read', 'update'), //
    'how_to_play' => array('read', 'update'), //
    'privacy_policy' => array('read', 'update'), //
    'term_conditions' => array('read', 'update'), //
    'web_settings' => array('read', 'update'),
    'system_update' => array('read', 'update'),
    'multi_match' => array('create', 'read', 'update', 'delete'), //
    'multi_match_import_question' => array('read', 'update'), //
    'multi_match_question_report' => array('read', 'update'), //
];

$config['category_type'] = [
    'main-category' => 1,
    'fun-n-learn-category' => 2,
    'guess-the-word-category' => 3,
    'audio-question-category' => 4,
    'maths-question-category' => 5,
    'multi-match-category' => 6,
    'sub-category' => 1,
    'fun-n-learn-subcategory' => 2,
    'guess-the-word-subcategory' => 3,
    'audio-question-subcategory' => 4,
    'maths-question-subcategory' => 5,
    'multi-match-subcategory' => 6,
    'category-order' => 1,
    'fun-n-learn-category-order' => 2,
    'guess-the-word-category-order' => 3,
    'audio-question-category-order' => 4,
    'maths-question-category-order' => 5,
    'multi-match-category-order' => 6,
];
