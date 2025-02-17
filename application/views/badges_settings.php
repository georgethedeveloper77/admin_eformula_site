<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Badges Settings | <?php echo (is_settings('app_name')) ? is_settings('app_name') : "" ?></title>

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
                        <h1>Badges Settings</h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <form method="post" class="needs-validation" novalidate="" enctype="multipart/form-data">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                            <table aria-describedby="mydesc" class='table-striped' id='badges_settings_list' data-toggle="table" data-url="<?= base_url() . 'Table/badge_settings' ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200, All]" data-search="true" data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true" data-fixed-columns="true" data-fixed-number="2" data-fixed-right-number="1" data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id" data-sort-order="asc" data-pagination-successively-size="3" data-maintain-selected="true" data-show-export="true" data-export-types='["csv","excel","pdf"]' data-export-options='{ "fileName": "badges-home-settings-list-<?= date('d-m-y') ?>" }' data-query-params="queryParams" data-escape="false">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-field="id" data-sortable="true" data-visible="false">ID</th>
                                                        <th scope="col" data-field="no">Sr No.</th>
                                                        <th scope="col" data-field="language_id" data-sortable="true" data-visible="false">Language ID</th>
                                                        <th scope="col" data-field="language" data-sortable="true">Language</th>
                                                        <th scope="col" data-field="operate" data-sortable="false" data-force-hide="true">Operate</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <hr>

                                            <ul>
                                                <h5>Note :- </h5>
                                                <div class="text-danger text-small">
                                                    <li> Only Notification Title, Notification Body, Label and Note is according to language</li>
                                                </div>
                                                <div class="text-danger text-small">
                                                    <li> Other Data like Icon, Reward Counter is Same for Same type of different languages <br> If any Changes is there in these, then changes will affected to all languages data having same type of badge</li>
                                                </div>
                                            </ul>

                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <div class="form-group row">
                                                <?php if (is_language_mode_enabled()) { ?>
                                                    <div class="col-md-3 col-sm-12">
                                                        <label class="control-label">Language</label>
                                                        <select name="language_id" class="form-control" required>
                                                            <option value="">Select Language</option>
                                                            <?php foreach ($language as $lang) { ?>
                                                                <option value="<?= $lang->id ?>" <?= ($this->uri->segment(2) == $lang->id) ? 'selected' : '' ?>><?= $lang->language ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </div>


                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Notification Settings</h6>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="control-label">Title</label>
                                                    <input name="title" type="text" class="form-control" placeholder="Enter Title" value="<?php echo (!empty($notification_title) ? $notification_title['message'] : "Congratulations !") ?>" />
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label class="control-label">Body</label>
                                                    <textarea name="body" class="form-control" rows="2" placeholder="Enter your notification message here..."><?php echo (!empty($notification_body) ? $notification_body['message'] : "You have unlocked new badge") ?></textarea>
                                                </div>
                                            </div>

                                            <!-- Dashing Debut -->
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Dashing Debut</h6>
                                                </div>
                                                <?php if (isset($dashing_debut) && !empty($dashing_debut['badge_icon'])) { ?>
                                                    <input name="dashing_debut_file" type="hidden" value="<?= $dashing_debut['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="dashing_debut_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($dashing_debut) && !empty($dashing_debut['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $dashing_debut['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="dashing_debut_label" value="<?= (isset($dashing_debut) && !empty($dashing_debut['badge_label'])) ? $dashing_debut['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="dashing_debut_note" required class="form-control"><?= (isset($dashing_debut) && !empty($dashing_debut['badge_note'])) ? $dashing_debut['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="dashing_debut_reward" value="<?= (isset($dashing_debut) && !empty($dashing_debut['badge_reward'])) ? $dashing_debut['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter</label>
                                                    <input type="number" min="1" name="dashing_debut_counter" value="<?= (isset($dashing_debut) && !empty($dashing_debut['badge_counter'])) ? $dashing_debut['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Combat Winner</h6>
                                                </div>
                                                <?php if (isset($combat_winner) && !empty($combat_winner['badge_icon'])) { ?>
                                                    <input name="combat_winner_file" type="hidden" value="<?= $combat_winner['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="combat_winner_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($combat_winner) && !empty($combat_winner['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $combat_winner['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="combat_winner_label" value="<?= (isset($combat_winner) && !empty($combat_winner['badge_label'])) ? $combat_winner['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="combat_winner_note" required class="form-control"><?= (isset($combat_winner) && !empty($combat_winner['badge_note'])) ? $combat_winner['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="combat_winner_reward" value="<?= (isset($combat_winner) && !empty($combat_winner['badge_reward'])) ? $combat_winner['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter</label>
                                                    <input type="number" min="1" name="combat_winner_counter" value="<?= (isset($combat_winner) && !empty($combat_winner['badge_counter'])) ? $combat_winner['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Clash Winner</h6>
                                                </div>
                                                <?php if (isset($clash_winner) && !empty($clash_winner['badge_icon'])) { ?>
                                                    <input name="clash_winner_file" type="hidden" value="<?= $clash_winner['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="clash_winner_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($clash_winner) && !empty($clash_winner['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $clash_winner['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="clash_winner_label" value="<?= (isset($clash_winner) && !empty($clash_winner['badge_label'])) ? $clash_winner['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="clash_winner_note" required class="form-control"><?= (isset($clash_winner) && !empty($clash_winner['badge_note'])) ? $clash_winner['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="clash_winner_reward" value="<?= (isset($clash_winner) && !empty($clash_winner['badge_reward'])) ? $clash_winner['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter</label>
                                                    <input type="number" min="1" name="clash_winner_counter" value="<?= (isset($clash_winner) && !empty($clash_winner['badge_counter'])) ? $clash_winner['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Most Wanted Winner</h6>
                                                </div>
                                                <?php if (isset($most_wanted_winner) && !empty($most_wanted_winner['badge_icon'])) { ?>
                                                    <input name="most_wanted_winner_file" type="hidden" value="<?= $most_wanted_winner['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="most_wanted_winner_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($most_wanted_winner) && !empty($most_wanted_winner['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $most_wanted_winner['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="most_wanted_winner_label" value="<?= (isset($most_wanted_winner) && !empty($most_wanted_winner['badge_label'])) ? $most_wanted_winner['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="most_wanted_winner_note" required class="form-control"><?= (isset($most_wanted_winner) && !empty($most_wanted_winner['badge_note'])) ? $most_wanted_winner['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="most_wanted_winner_reward" value="<?= (isset($most_wanted_winner) && !empty($most_wanted_winner['badge_reward'])) ? $most_wanted_winner['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter</label>
                                                    <input type="number" min="1" name="most_wanted_winner_counter" value="<?= (isset($most_wanted_winner) && !empty($most_wanted_winner['badge_counter'])) ? $most_wanted_winner['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Ultimate Player</h6>
                                                </div>
                                                <?php if (isset($ultimate_player) && !empty($ultimate_player['badge_icon'])) { ?>
                                                    <input name="ultimate_player_file" type="hidden" value="<?= $ultimate_player['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="ultimate_player_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($ultimate_player) && !empty($ultimate_player['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $ultimate_player['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="ultimate_player_label" value="<?= (isset($ultimate_player) && !empty($ultimate_player['badge_label'])) ? $ultimate_player['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="ultimate_player_note" required class="form-control"><?= (isset($ultimate_player) && !empty($ultimate_player['badge_note'])) ? $ultimate_player['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="ultimate_player_reward" value="<?= (isset($ultimate_player) && !empty($ultimate_player['badge_reward'])) ? $ultimate_player['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Quiz Warrior</h6>
                                                </div>
                                                <?php if (isset($quiz_warrior) && !empty($quiz_warrior['badge_icon'])) { ?>
                                                    <input name="quiz_warrior_file" type="hidden" value="<?= $quiz_warrior['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="quiz_warrior_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($quiz_warrior) && !empty($quiz_warrior['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $quiz_warrior['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="quiz_warrior_label" value="<?= (isset($quiz_warrior) && !empty($quiz_warrior['badge_label'])) ? $quiz_warrior['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="quiz_warrior_note" required class="form-control"><?= (isset($quiz_warrior) && !empty($quiz_warrior['badge_note'])) ? $quiz_warrior['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="quiz_warrior_reward" value="<?= (isset($quiz_warrior) && !empty($quiz_warrior['badge_reward'])) ? $quiz_warrior['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter</label>
                                                    <input type="number" min="1" name="quiz_warrior_counter" value="<?= (isset($quiz_warrior) && !empty($quiz_warrior['badge_counter'])) ? $quiz_warrior['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Super Sonic</h6>
                                                </div>
                                                <?php if (isset($super_sonic) && !empty($super_sonic['badge_icon'])) { ?>
                                                    <input name="super_sonic_file" type="hidden" value="<?= $super_sonic['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="super_sonic_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($super_sonic) && !empty($super_sonic['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $super_sonic['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="super_sonic_label" value="<?= (isset($super_sonic) && !empty($super_sonic['badge_label'])) ? $super_sonic['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="super_sonic_note" required class="form-control"><?= (isset($super_sonic) && !empty($super_sonic['badge_note'])) ? $super_sonic['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="super_sonic_reward" value="<?= (isset($super_sonic) && !empty($super_sonic['badge_reward'])) ? $super_sonic['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter <small>(in seconds)</small></label>
                                                    <input type="number" min="1" name="super_sonic_counter" value="<?= (isset($super_sonic) && !empty($super_sonic['badge_counter'])) ? $super_sonic['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Flashback</h6>
                                                </div>
                                                <?php if (isset($flashback) && !empty($flashback['badge_icon'])) { ?>
                                                    <input name="flashback_file" type="hidden" value="<?= $flashback['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="flashback_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($flashback) && !empty($flashback['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $flashback['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="flashback_label" value="<?= (isset($flashback) && !empty($flashback['badge_label'])) ? $flashback['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="flashback_note" required class="form-control"><?= (isset($flashback) && !empty($flashback['badge_note'])) ? $flashback['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="flashback_reward" value="<?= (isset($flashback) && !empty($flashback['badge_reward'])) ? $flashback['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter <small>(in seconds)</small></label>
                                                    <input type="number" name="flashback_counter" value="<?= (isset($flashback) && !empty($flashback['badge_counter'])) ? $flashback['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Brainiac</h6>
                                                </div>
                                                <?php if (isset($brainiac) && !empty($brainiac['badge_icon'])) { ?>
                                                    <input name="brainiac_file" type="hidden" value="<?= $brainiac['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="brainiac_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($brainiac) && !empty($brainiac['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $brainiac['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="brainiac_label" value="<?= (isset($brainiac) && !empty($brainiac['badge_label'])) ? $brainiac['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="brainiac_note" required class="form-control"><?= (isset($brainiac) && !empty($brainiac['badge_note'])) ? $brainiac['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="brainiac_reward" value="<?= (isset($brainiac) && !empty($brainiac['badge_reward'])) ? $brainiac['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Big Thing</h6>
                                                </div>
                                                <?php if (isset($big_thing) && !empty($big_thing['badge_icon'])) { ?>
                                                    <input name="big_thing_file" type="hidden" value="<?= $big_thing['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="big_thing_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($big_thing) && !empty($big_thing['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $big_thing['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="big_thing_label" value="<?= (isset($big_thing) && !empty($big_thing['badge_label'])) ? $big_thing['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="big_thing_note" required class="form-control"><?= (isset($big_thing) && !empty($big_thing['badge_note'])) ? $big_thing['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="big_thing_reward" value="<?= (isset($big_thing) && !empty($big_thing['badge_reward'])) ? $big_thing['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter</label>
                                                    <input type="number" min="1" name="big_thing_counter" value="<?= (isset($big_thing) && !empty($big_thing['badge_counter'])) ? $big_thing['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Elite</h6>
                                                </div>
                                                <?php if (isset($elite) && !empty($elite['badge_icon'])) { ?>
                                                    <input name="elite_file" type="hidden" value="<?= $elite['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="elite_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($elite) && !empty($elite['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $elite['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="elite_label" value="<?= (isset($elite) && !empty($elite['badge_label'])) ? $elite['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="elite_note" required class="form-control"><?= (isset($elite) && !empty($elite['badge_note'])) ? $elite['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="elite_reward" value="<?= (isset($elite) && !empty($elite['badge_reward'])) ? $elite['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter <small>(in coins)</small></label>
                                                    <input type="number" min="1" name="elite_counter" value="<?php echo (isset($elite) && !empty($elite['badge_counter'])) ? $elite['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Thirsty</h6>
                                                </div>
                                                <?php if (isset($thirsty) && !empty($thirsty['badge_icon'])) { ?>
                                                    <input name="thirsty_file" type="hidden" value="<?= $thirsty['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="thirsty_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($thirsty) && !empty($thirsty['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $thirsty['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="thirsty_label" value="<?= (isset($thirsty) && !empty($thirsty['badge_label'])) ? $thirsty['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="thirsty_note" required class="form-control"><?= (isset($thirsty) && !empty($thirsty['badge_note'])) ? $thirsty['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="thirsty_reward" value="<?= (isset($thirsty) && !empty($thirsty['badge_reward'])) ? $thirsty['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter <small>(in days)</small></label>
                                                    <input type="number" min="1" name="thirsty_counter" value="<?php echo (isset($thirsty) && !empty($thirsty['badge_counter'])) ? $thirsty['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Power Elite</h6>
                                                </div>
                                                <?php if (isset($power_elite) && !empty($power_elite['badge_icon'])) { ?>
                                                    <input name="power_elite_file" type="hidden" value="<?= $power_elite['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="power_elite_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($power_elite) && !empty($power_elite['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $power_elite['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="power_elite_label" value="<?= (isset($power_elite) && !empty($power_elite['badge_label'])) ? $power_elite['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="power_elite_note" required class="form-control"><?= (isset($power_elite) && !empty($power_elite['badge_note'])) ? $power_elite['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="power_elite_reward" value="<?= (isset($power_elite) && !empty($power_elite['badge_reward'])) ? $power_elite['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter</label>
                                                    <input type="number" min="1" name="power_elite_counter" value="<?php echo (isset($power_elite) && !empty($power_elite['badge_counter'])) ? $power_elite['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Sharing is Caring</h6>
                                                </div>
                                                <?php if (isset($sharing_caring) && !empty($sharing_caring['badge_icon'])) { ?>
                                                    <input name="sharing_caring_file" type="hidden" value="<?= $sharing_caring['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="sharing_caring_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($sharing_caring) && !empty($sharing_caring['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $sharing_caring['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="sharing_caring_label" value="<?= (isset($sharing_caring) && !empty($sharing_caring['badge_label'])) ? $sharing_caring['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="sharing_caring_note" required class="form-control"><?= (isset($sharing_caring) && !empty($sharing_caring['badge_note'])) ? $sharing_caring['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="sharing_caring_reward" value="<?= (isset($sharing_caring) && !empty($sharing_caring['badge_reward'])) ? $sharing_caring['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter</label>
                                                    <input type="number" min="1" name="sharing_caring_counter" value="<?php echo (isset($sharing_caring) && !empty($sharing_caring['badge_counter'])) ? $sharing_caring['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <h6 class="font-weight-bold">Streak</h6>
                                                </div>
                                                <?php if (isset($streak) && !empty($streak['badge_icon'])) { ?>
                                                    <input name="streak_file" type="hidden" value="<?= $streak['badge_icon'] ?>" />
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Icon</label>
                                                    <input name="streak_file" type="file" accept="image/*" class="form-control" />
                                                </div>
                                                <?php if (isset($streak) && !empty($streak['badge_icon'])) { ?>
                                                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                        <img src="<?= base_url() . BADGE_IMG_PATH . $streak['badge_icon'] ?>" width="80" height="80" />
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Label</label>
                                                    <input name="streak_label" value="<?= (isset($streak) && !empty($streak['badge_label'])) ? $streak['badge_label'] : "" ?>" type="text" required class="form-control" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <label class="control-label">Note</label>
                                                    <textarea name="streak_note" required class="form-control"><?= (isset($streak) && !empty($streak['badge_note'])) ? $streak['badge_note'] : "" ?></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Reward <small>(coins)</small></label>
                                                    <input name="streak_reward" value="<?= (isset($streak) && !empty($streak['badge_reward'])) ? $streak['badge_reward'] : "" ?>" type="number" min="1" required class="form-control" />
                                                </div>
                                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                                                    <label class="control-label">Counter <small>(in days)</small></label>
                                                    <input type="number" min="1" name="streak_counter" value="<?php echo (isset($streak) && !empty($streak['badge_counter'])) ? $streak['badge_counter'] : "" ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <input type="submit" name="btnadd" value="Submit" class="<?= BUTTON_CLASS ?>" />
                                                </div>
                                            </div>
                                        </form>
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


</body>
<script>
    function queryParams(p) {
        return {
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            limit: p.limit,
            search: p.search
        };
    }
</script>

</html>