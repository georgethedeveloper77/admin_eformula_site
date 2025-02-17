<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= lang('web_settings'); ?> | <?php echo (is_settings('app_name')) ? is_settings('app_name') : "" ?></title>

    <?php base_url() . include 'include.php'; ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <?php base_url() . include 'header.php'; ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= lang('system_settings_for_web'); ?> <small class="text-small"><?= lang('note_that_this_will_directly_reflect_the_changes_in_web'); ?></small></h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" class="needs-validation" novalidate="" enctype="multipart/form-data">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                            <!-- Firebase Configuration -->
                                            <h4>
                                                <label class="control-label"><b><?= lang('firebase_configurations'); ?></b></label>
                                            </h4>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('api_key'); ?></label> <small class="text-danger">*</small>
                                                    <input type="text" id="firebase_api_key" name="firebase_api_key" required class="form-control" placeholder="<?= lang('api_key'); ?>" value="<?php echo (!empty($firebase_api_key['message'])) ? $firebase_api_key['message'] : "" ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('auth_domain'); ?></label> <small class="text-danger">*</small>
                                                    <input type="text" id="firebase_auth_domain" name="firebase_auth_domain" required class="form-control" placeholder="<?= lang('auth_domain'); ?>" value="<?php echo (!empty($firebase_auth_domain['message'])) ? $firebase_auth_domain['message'] : "" ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('database_url'); ?></label> <small class="text-danger"><?= lang('optional'); ?></small>
                                                    <input type="text" id="firebase_database_url" name="firebase_database_url" class="form-control" placeholder="<?= lang('database_url'); ?>" value="<?php echo (!empty($firebase_database_url['message'])) ? $firebase_measurement_id['message'] : "" ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('project_id'); ?></label> <small class="text-danger">*</small>
                                                    <input type="text" id="firebase_project_id" name="firebase_project_id" required class="form-control" placeholder="<?= lang('project_id'); ?>" value="<?php echo (!empty($firebase_project_id['message'])) ? $firebase_project_id['message'] : "" ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('storage_bucket'); ?></label> <small class="text-danger">*</small>
                                                    <input type="text" id="firebase_storage_bucket" name="firebase_storage_bucket" required class="form-control" placeholder="<?= lang('storage_bucket'); ?>" value="<?php echo (!empty($firebase_storage_bucket['message'])) ? $firebase_storage_bucket['message'] : "" ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('messaging_sender_id'); ?></label> <small class="text-danger">*</small>
                                                    <input type="text" id="firebase_messager_sender_id" name="firebase_messager_sender_id" required class="form-control" placeholder="<?= lang('messaging_sender_id'); ?>" value="<?php echo (!empty($firebase_messager_sender_id['message'])) ? $firebase_messager_sender_id['message'] : "" ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('app_id'); ?></label> <small class="text-danger">*</small>
                                                    <input type="text" id="firebase_app_id" name="firebase_app_id" required class="form-control" placeholder="<?= lang('app_id'); ?>" value="<?php echo (!empty($firebase_app_id['message'])) ? $firebase_app_id['message'] : "" ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('measurement_id'); ?></label> <small class="text-danger"><?= lang('optional'); ?></small>
                                                    <input type="text" id="firebase_measurement_id" name="firebase_measurement_id" class="form-control" placeholder="<?= lang('measurement_id'); ?>" value="<?php echo (!empty($firebase_measurement_id['message'])) ? $firebase_measurement_id['message'] : "" ?>">
                                                </div>
                                            </div>

                                            <!-- Header Configuration -->
                                            <hr>
                                            <h4>
                                                <label class="control-label"><b><?= lang('header_configuration'); ?></b></label>
                                            </h4>
                                            <ul>
                                                <div class="text-danger text-small">
                                                    <li> <?= lang('favicon_icon_recommended_size'); ?></li>
                                                </div>
                                                <div class="text-danger text-small">
                                                    <li> <?= lang('logo_recommended_size'); ?></li>
                                                </div>
                                                <div class="text-danger text-small">
                                                    <li> <?= lang('file_type_support'); ?></li>
                                                </div>
                                            </ul>
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <!-- checks the header logo exists or not  -->
                                                    <?php
                                                    if (!empty($header_logo['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('header_logo'); ?></label>
                                                            <input class="form-control header_logo" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="header_logo">
                                                            <p style="display: none" id="header_logo_img_error_msg" class="alert alert-danger"></p>
                                                            <div><a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $header_logo['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $header_logo['message'] ?>' height=50, width=50></a></div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('header_logo'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control header_logo" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="header_logo" required>
                                                            <p style="display: none" id="header_logo_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!----------->
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <!-- checks the Footer logo exists or not  -->
                                                    <?php
                                                    if (!empty($footer_logo['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('footer_logo'); ?></label>
                                                            <input class="form-control footer_logo" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="footer_logo">
                                                            <p style="display: none" id="header_logo_img_error_msg" class="alert alert-danger"></p>
                                                            <div><a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $footer_logo['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $footer_logo['message'] ?>' height=50, width=50></a></div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('footer_logo'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control footer_logo" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="footer_logo" required>
                                                            <p style="display: none" id="header_logo_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!---------------->
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <!-- checks the Sticky header logo exists or not  -->
                                                    <?php
                                                    if (!empty($sticky_header_logo['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('sticky_header_logo'); ?></label>
                                                            <input class="form-control sticky_header_logo" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="sticky_header_logo">
                                                            <p style="display: none" id="sticky_header_logo_img_error_msg" class="alert alert-danger"></p>
                                                            <div><a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $sticky_header_logo['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $sticky_header_logo['message'] ?>' height=50, width=50></a></div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('sticky_header_logo'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control sticky_header_logo" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="sticky_header_logo" required>
                                                            <p style="display: none" id="sticky_header_logo_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- ------------------------------- -->
                                                </div>
                                            </div>

                                            <!-- Features Icons  -->
                                            <hr>
                                            <h4>
                                                <label class="control-label"><b><?= lang('feature_icons'); ?></b></label>
                                            </h4>
                                            <ul>
                                                <div class="text-danger text-small">
                                                    <li><?= lang('recommended_size'); ?></li>
                                                </div>
                                                <div class="text-danger text-small">
                                                    <li> <?= lang('file_type_support'); ?></li>
                                                </div>
                                            </ul>
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Quiz Zone Icon exists or not  -->
                                                    <?php
                                                    if (!empty($quiz_zone_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('quiz_zone_icon'); ?></label>
                                                            <input class="form-control quiz_zone_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="quiz_zone_icon">
                                                            <p style="display: none" id="quiz_zone_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $quiz_zone_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $quiz_zone_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('quiz_zone_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control quiz_zone_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="quiz_zone_icon" required>
                                                            <p style="display: none" id="quiz_zone_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!----------->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Daily Quiz Icon exists or not  -->
                                                    <?php
                                                    if (!empty($daily_quiz_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('daily_quiz_icon'); ?></label>
                                                            <input class="form-control daily_quiz_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="daily_quiz_icon">
                                                            <p style="display: none" id="daily_quiz_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $daily_quiz_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $daily_quiz_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('daily_quiz_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control daily_quiz_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="daily_quiz_icon" required>
                                                            <p style="display: none" id="daily_quiz_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!----------->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the True & False Icon exists or not  -->
                                                    <?php
                                                    if (!empty($true_false_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('true_flase_icon'); ?></label>
                                                            <input class="form-control true_false_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="true_false_icon">
                                                            <p style="display: none" id="true_false_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $true_false_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $true_false_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('true_flase_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control true_false_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="true_false_icon" required>
                                                            <p style="display: none" id="true_false_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!---------------->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Fun & Learn Icon exists or not  -->
                                                    <?php
                                                    if (!empty($fun_learn_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('fun_learn_icon'); ?></label>
                                                            <input class="form-control fun_learn_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="fun_learn_icon">
                                                            <p style="display: none" id="fun_learn_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $fun_learn_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $fun_learn_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('fun_learn_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control fun_learn_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="fun_learn_icon" required>
                                                            <p style="display: none" id="fun_learn_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- ------------------------------- -->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Self Challange Icon exists or not  -->
                                                    <?php
                                                    if (!empty($self_challange_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('self_challange_icon'); ?></label>
                                                            <input class="form-control self_challange_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="self_challange_icon">
                                                            <p style="display: none" id="self_challange_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $self_challange_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $self_challange_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('self_challange_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control self_challange_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="self_challange_icon" required>
                                                            <p style="display: none" id="self_challange_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- ------------------------------- -->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Contest Play exists or not  -->
                                                    <?php
                                                    if (!empty($contest_play_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('contest_play_icon'); ?></label>
                                                            <input class="form-control contest_play_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="contest_play_icon">
                                                            <p style="display: none" id="contest_play_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $contest_play_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $contest_play_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('contest_play'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control contest_play_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="contest_play_icon" required>
                                                            <p style="display: none" id="contest_play_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- ------------------------------- -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <!-- checks the 1 vs 1 Battle Icon exists or not  -->
                                                    <?php
                                                    if (!empty($one_one_battle_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('1_vs_1_battle_icon'); ?></label>
                                                            <input class="form-control one_one_battle_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="one_one_battle_icon">
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $one_one_battle_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $one_one_battle_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('1_vs_1_battle_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control one_one_battle_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="one_one_battle_icon" required>
                                                            <p style="display: none" id="one_one_battle_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!----------->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Group Battle Icon exists or not  -->
                                                    <?php
                                                    if (!empty($group_battle_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('group_battle_icon'); ?></label>
                                                            <input class="form-control group_battle_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="group_battle_icon">
                                                            <p style="display: none" id="group_battle_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $group_battle_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $group_battle_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('group_battle_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control group_battle_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="group_battle_icon" required>
                                                            <p style="display: none" id="group_battle_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!----------->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Audio Questions Icon exists or not  -->
                                                    <?php
                                                    if (!empty($audio_question_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('audio_question_icon'); ?></label>
                                                            <input class="form-control audio_question_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="audio_question_icon">
                                                            <p style="display: none" id="audio_question_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $audio_question_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $audio_question_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('audio_question_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control audio_question_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="audio_question_icon" required>
                                                            <p style="display: none" id="audio_question_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!---------------->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Math Mania Icon exists or not  -->
                                                    <?php
                                                    if (!empty($math_mania_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('math_mania_icon'); ?></label>
                                                            <input class="form-control math_mania_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="math_mania_icon">
                                                            <p style="display: none" id="math_mania_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $math_mania_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $math_mania_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('math_mania_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control math_mania_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="math_mania_icon" required>
                                                            <p style="display: none" id="math_mania_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- ------------------------------- -->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Exam Icon exists or not  -->
                                                    <?php
                                                    if (!empty($exam_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('exam_icon'); ?></label>
                                                            <input class="form-control exam_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="exam_icon">
                                                            <p style="display: none" id="exam_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $exam_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $exam_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('exam_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control exam_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="exam_icon" required>
                                                            <p style="display: none" id="exam_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- ------------------------------- -->
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <!-- checks the Guess The Word Icon exists or not  -->
                                                    <?php
                                                    if (!empty($guess_the_word_icon['message'])) { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('guess_the_word_icon'); ?></label>
                                                            <input class="form-control guess_the_word_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="guess_the_word_icon">
                                                            <p style="display: none" id="guess_the_word_icon_img_error_msg" class="alert alert-danger"></p>
                                                            <a href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $guess_the_word_icon['message'] ?>' data-lightbox="Logos"><img src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $guess_the_word_icon['message'] ?>' height=50, width=50></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="mb-3">
                                                            <label for="formFile" class="control-label"><?= lang('guess_the_word_icon'); ?></label> <small class="text-danger"> * </small>
                                                            <input class="form-control guess_the_word_icon" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" name="guess_the_word_icon" required>
                                                            <p style="display: none" id="guess_the_word_icon_img_error_msg" class="alert alert-danger"></p>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- ------------------------------- -->
                                                </div>
                                            </div>

                                            <!-- Footer Configuration -->
                                            <hr>
                                            <h4>
                                                <label class="control-label"><b><?= lang('footer_configuration'); ?></b></label>
                                            </h4>
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label class="control-label"><?= lang('company_name_footer'); ?></label>
                                                    <input type="text" id="company_name_footer" name="company_name_footer" placeholder="<?= lang('company_name_footer'); ?>" class="form-control" value="<?php echo (!empty($company_name_footer['message'])) ? $company_name_footer['message'] : "" ?>">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="control-label"><?= lang('email_footer'); ?></label>
                                                    <input type="email" id="email_footer" name="email_footer" placeholder="<?= lang('email_footer'); ?>" class="form-control" value="<?php echo (!empty($email_footer['message'])) ? $email_footer['message'] : "" ?>">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="control-label"><?= lang('phone_number_footer'); ?></label>
                                                    <input type="text" id="phone_number_footer" name="phone_number_footer" placeholder="<?= lang('phone_number_footer'); ?>" class="form-control" value="<?php echo (!empty($phone_number_footer['message'])) ? $phone_number_footer['message'] : "" ?>">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="control-label"><?= lang('web_link_footer'); ?></label>
                                                    <input type="text" id="web_link_footer" name="web_link_footer" placeholder="<?= lang('web_link_footer'); ?>" class="form-control" value="<?php echo (!empty($web_link_footer['message'])) ? $web_link_footer['message'] : "" ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('company_text'); ?></label>
                                                    <textarea id="company_text" name="company_text" placeholder="<?= lang('company_text'); ?>" class="form-control"><?= (!empty($company_text['message'])) ? $company_text['message'] : '' ?></textarea>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label"><?= lang('address_text'); ?></label>
                                                    <textarea id="address_text" name="address_text" placeholder="<?= lang('address_text'); ?>" class="form-control"><?= (!empty($address_text['message'])) ? $address_text['message'] : '' ?></textarea>
                                                </div>
                                            </div>

                                            <!-- Other Configuration -->
                                            <hr>
                                            <h4>
                                                <label class="control-label"><b><?= lang('other_configuration'); ?></b></label>
                                            </h4>
                                            <div class="row">
                                                <div class="form-group col-md-2 col-sm-6">
                                                    <label class="control-label"><?= lang('rtl_support'); ?></label><br>
                                                    <input type="checkbox" id="rtl_support_btn" data-plugin="switchery" <?php if (!empty($rtl_support) && $rtl_support['message'] == '1') {
                                                                                                                            echo 'checked';
                                                                                                                        } ?>>

                                                    <input type="hidden" id="rtl_support" name="rtl_support" value="<?= ($rtl_support) ? $rtl_support['message'] : 0; ?>">
                                                </div>

                                                <div class="form-group col-sm-6 col-md-4 col-lg-3">
                                                    <label class="control-label"><?= lang('primary_color'); ?></label>
                                                    <input id="primary_color" name="primary_color" class="form-control" data-jscolor="{}" value="<?= isset($primary_color) && !empty($primary_color['message']) ? $primary_color['message'] : "#EF5388" ?>">
                                                </div>

                                                <div class="form-group col-sm-6 col-md-4 col-lg-3">
                                                    <label class="control-label"><?= lang('footer_color'); ?></label>
                                                    <input id="footer_color" name="footer_color" class="form-control" data-jscolor="{}" value="<?= isset($footer_color) && !empty($footer_color['message']) ? $footer_color['message'] : "#090029" ?>">
                                                </div>
                                            </div>
                                            <hr>
                                            <h4>
                                                <label class="control-label"><b><?= lang('social_media'); ?></b></label>
                                            </h4>
                                            <div class="extra-social-media-content">
                                                <?php
                                                if (isset($social_media) && !empty($social_media['message'])) {
                                                    $socialMediaData = json_decode($social_media['message']);
                                                    foreach ($socialMediaData as $key => $singleValue) { ?>
                                                        <div class="social_media_content bg-light rounded p-4 mb-3" style="display: block;">
                                                            <div class="row">
                                                                <div class="form-group col-sm-12 col-lg-6">
                                                                    <label class="control-label"><?= lang('link'); ?> </label>
                                                                    <input type="text" name="social_media[<?= ($key + 1) ?>][link]" value="<?= $singleValue->link ?>" class="form-control social_media_link" placeholder="<?= lang('link'); ?>" required="required">
                                                                </div>
                                                                <?php if (!empty($singleValue->icon)) { ?>
                                                                    <div class="form-group col-sm-11 col-lg-5">
                                                                        <label class="control-label"><?= lang('icon'); ?></label>
                                                                        <input type="hidden" class="exists-icon-value" name="social_media[<?= ($key + 1) ?>][icon]" value="<?= $singleValue->icon ?>">
                                                                        <input class="form-control social_media_icon" name="social_media[<?= ($key + 1) ?>][file]" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" data-no="<?= ($key + 1) ?>">
                                                                        <p style="display: none" id="social_media_icon_img_error_msg" class="alert alert-danger"></p>
                                                                        <a class="last-icon-preview" href='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $singleValue->icon ?>' data-lightbox="Logos"><img class="last-icon" src='<?= base_url() . WEB_SETTINGS_LOGO_PATH . $singleValue->icon ?>' height=50, width=50></a>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="form-group col-sm-11 col-lg-5">
                                                                        <label class="control-label"><?= lang('icon'); ?></label>
                                                                        <input type="hidden" name="social_media[<?= ($key + 1) ?>][icon]" value="">
                                                                        <input class="form-control social_media_icon" name="social_media[<?= ($key + 1) ?>][file]" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg" required="required" data-no="<?= ($key + 1) ?>">
                                                                        <p style="display: none" id="social_media_icon_img_error_msg" class="alert alert-danger"></p>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="form-group col-md-1 pl-0 mt-4">
                                                                    <button type="button" class="btn btn-icon btn-primary remove-social-media" title="<?= lang('remove_social_media'); ?>">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div class="col-md-4 pl-0 mb-4 mt-4">
                                                <button type="button" class="btn btn-success add-social-media-content" title="<?= lang('add_new_row'); ?>">
                                                    <?= lang('add_new_data'); ?>
                                                </button>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-md-2 col-sm-6">
                                                    <input type="submit" name="btnadd" value="<?= lang('submit'); ?>" class="<?= BUTTON_CLASS ?> mt-4" />
                                                </div>
                                            </div>
                                        </form>
                                        <!-- Template for New Social Media -->
                                        <div class="social_media_template_div" style="display:none">
                                            <div class="social_media_template">
                                                <div class="row bg-light rounded p-4">
                                                    <div class="form-group col-sm-12 col-lg-6">
                                                        <label class="control-label"><?= lang('link'); ?> </label>
                                                        <input type="text" name="social_media[0][link]" class="temp_social_media_link form-control" placeholder="<?= lang('link'); ?>" />
                                                    </div>
                                                    <div class="form-group col-sm-11 col-lg-5">
                                                        <label class="control-label"><?= lang('icon'); ?></label>
                                                        <input class="form-control temp_social_media_icon" name="social_media[0][file]" type="file" accept="image/jpg,image/png,image/svg+xml,image/jpeg">
                                                        <p style="display: none" id="social_media_icon_img_error_msg" class="alert alert-danger"></p>
                                                    </div>
                                                    <div class="form-group col-md-1 pl-0 mt-4">
                                                        <button type="button" class="btn btn-icon btn-primary remove-social-media" title="<?= lang('remove_social_media'); ?>">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Template for New Social Media -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php base_url() . include 'footer.php'; ?>
    <script type="text/javascript">
        $(document).on('click', '.add-social-media-content', function(e) {
            e.preventDefault();
            let html = ""
            if ($('.extra-social-media-content').find('.social_media_content').html()) {
                html = $('.extra-social-media-content').find('.social_media_content:last').clone();
                html.find('.social_media_link').attr('required', true);
                html.find('.social_media_icon').attr('required', true);
                html.find('.last-icon').attr("src", "");
                html.find('.last-icon-preview').attr("href", "");
                html.find('.last-icon').remove();
                html.find('.exists-icon-value').val("");
            } else {
                html = $('.social_media_template:last').clone();
                html.removeClass('social_media_template').addClass('social_media_content');
                html.find('.temp_social_media_link').removeClass('temp_social_media_link').addClass('social_media_link').attr('required', true);
                html.find('.temp_social_media_icon').removeClass('temp_social_media_icon').addClass('social_media_icon').attr('required', true);
            }
            html.find('.error').remove();
            html.css('display', 'block');
            html.find('.has-danger').removeClass('has-danger');
            html.find('.hidden').remove();
            // This function will replace the last index value and increment in the multidimensional name attribute
            $(this).parent().siblings('.extra-social-media-content').append(html);
            html.find('.social_media_link').val('')
            html.find('.social_media_icon').val('');
            html.find('input').each(function(key, element) {
                this.name = this.name.replace(/\[(\d+)\]/, function(str, p1) {
                    html.find('.social_media_icon').attr("data-no", (parseInt(p1, 10) + 1));
                    return '[' + (parseInt(p1, 10) + 1) + ']';
                });
            })
        });

        $(document).on('click', '.remove-social-media', function() {
            var $this = $(this);
            if (confirm('Are you sure you want to remove it ? ')) {
                $this.parent().parent().parent().remove();
            }
        });

        $('[data-plugin="switchery"]').each(function(index, element) {
            var init = new Switchery(element, {
                size: 'small',
                color: '#1abc9c',
                secondaryColor: '#f1556c'
            });
        });

        var changeCheckbox2 = document.querySelector('#rtl_support_btn');
        changeCheckbox2.onchange = function() {
            if (changeCheckbox2.checked)
                $('#rtl_support').val(1);
            else
                $('#rtl_support').val(0);
        };
    </script>

    <script type="text/javascript">
        var _URL = window.URL || window.webkitURL;

        // get the value of all the logos and icons 
        let logos = ['favicon', 'header_logo', 'footer_logo', 'sticy_header_logo', 'quiz_zone_icon', 'daily_quiz_icon', 'true_false_icon', 'fun_learn_icon', 'self_challange_icon', 'contest_play_icon', 'one_one_battle_icon', 'group_battle_icon', 'audio_question_icon', 'math_mania_icon', 'exam_icon', 'guess_the_word_icon', 'social_media_icon'];

        //added in loop to check the upload file validation
        $.each(logos, function(index, value) {
            $("." + value).change(function(e) {
                var file, img;

                if ((file = this.files[0])) {
                    img = new Image();

                    //checks only image which are not png or jpg
                    if (file.type !== 'image/png' && file.type !== 'image/jpeg' && file.type !== 'image/jpg' && file.type !== 'image/svg+xml') {
                        $('.' + value).val('');
                        $('#' + value + '_img_error_msg').html('<?= IMAGE_ALLOW_PNG_JPG_SVG_MSG; ?>');
                        $('#' + value + '_img_error_msg').show().delay(3000).fadeOut();
                    }

                    //gets error when uploading any file rather than image
                    img.onerror = function() {
                        $('.' + value + '_icon').val('');
                        $('#' + value + '_icon_img_error_msg').html('<?= INVALID_IMAGE_TYPE; ?>');
                        $('#' + value + '_icon_img_error_msg').show().delay(3000).fadeOut();
                    };
                    img.src = _URL.createObjectURL(file);
                }
            });
        });
    </script>
</body>

</html>