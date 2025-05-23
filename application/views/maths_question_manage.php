<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= lang('questions_for_maths_quiz'); ?> | <?php echo (is_settings('app_name')) ? is_settings('app_name') : "" ?></title>

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
                        <h1><?= lang('manage_maths_questions'); ?></h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <?php if (is_language_mode_enabled()) { ?>
                                                <div class="col-md-3">
                                                    <select id="filter_language" class="form-control" required>
                                                        <option value=""><?= lang('select_language'); ?></option>
                                                        <?php foreach ($language as $lang) { ?>
                                                            <option value="<?= $lang->id ?>"><?= $lang->language ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select id="filter_category" class="form-control" required>
                                                        <option value=""><?= lang('select_main_category'); ?></option>

                                                    </select>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-3">
                                                    <select id="filter_category" class="form-control" required>
                                                        <option value=""><?= lang('select_main_category'); ?></option>
                                                        <?php foreach ($category as $cat) { ?>
                                                            <option value="<?= $cat->id ?>"><?= $cat->category_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                            <div class='col-md-3'>
                                                <select id='filter_subcategory' class='form-control' required>
                                                    <option value=''><?= lang('select_sub_category'); ?></option>
                                                </select>
                                            </div>
                                            <div class='col-md-3'>
                                                <button class='<?= BUTTON_CLASS ?> btn-block form-control' id='filter_btn'><?= lang('filter_data'); ?></button>
                                            </div>
                                        </div>
                                        <div id="toolbar">
                                            <?php if (has_permissions('delete', 'maths_questions')) { ?>
                                                <button class="btn btn-danger" id="delete_multiple_questions" title="<?= lang('delete_selected_questions'); ?>"><em class='fa fa-trash'></em></button>
                                            <?php } ?>
                                        </div>
                                        <table aria-describedby="mydesc" class='table-striped' id='question_list' data-toggle="table" data-url="<?= base_url() . 'Table/maths_question' ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200, All]" data-search="true" data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="3" data-maintain-selected="true" data-show-export="true" data-export-types='["csv","excel","pdf"]' data-export-options='{ "fileName": "question-list-<?= date('d-m-y') ?>" }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th scope="col" data-field="state" data-checkbox="true"></th>
                                                    <th scope="col" data-field="id" data-sortable="true"><?= lang('id'); ?></th>
                                                    <th scope="col" data-field="category" data-sortable="true" data-visible='false'><?= lang('main_category'); ?></th>
                                                    <th scope="col" data-field="subcategory" data-sortable="true" data-visible='false'><?= lang('sub_category'); ?></th>
                                                    <?php if (is_language_mode_enabled()) { ?>
                                                        <th scope="col" data-field="language_id" data-sortable="true" data-visible='false'><?= lang('language_id'); ?></th>
                                                        <th scope="col" data-field="language" data-sortable="true" data-visible='true'><?= lang('language'); ?></th>
                                                    <?php } ?>
                                                    <th scope="col" data-field="image" data-sortable="false"><?= lang('image'); ?></th>
                                                    <th scope="col" data-field="question" data-sortable="true"><?= lang('question'); ?></th>
                                                    <th scope="col" data-field="question_type" data-sortable="true" data-visible='false'><?= lang('question_type'); ?></th>
                                                    <th scope="col" data-field="optiona" data-sortable="true"><?= lang('option_a'); ?></th>
                                                    <th scope="col" data-field="optionb" data-sortable="true"><?= lang('option_b'); ?></th>
                                                    <th scope="col" data-field="optionc" data-sortable="true"><?= lang('option_c'); ?></th>
                                                    <th scope="col" data-field="optiond" data-sortable="true"><?= lang('option_d'); ?></th>
                                                    <?php if (is_option_e_mode_enabled()) { ?>
                                                        <th scope="col" data-field="optione" data-sortable="true"><?= lang('option_e'); ?></th>
                                                    <?php } ?>
                                                    <th scope="col" data-field="answer" data-sortable="true" data-visible='false'><?= lang('answer'); ?></th>
                                                    <th scope="col" data-field="note" data-sortable="true" data-visible='false'><?= lang('note'); ?></th>
                                                    <th scope="col" data-field="operate" data-sortable="false"><?= lang('operate'); ?></th>
                                                </tr>
                                            </thead>
                                        </table>
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
    <script src="<?= base_url('assets/ckeditor/ckeditor.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#question_list').on('post-body.bs.table', function() {
                createCkeditor();
            })
        });


        $('#filter_btn').on('click', function(e) {
            $('#question_list').bootstrapTable('refresh');
        });
        $('#delete_multiple_questions').on('click', function(e) {
            var base_url = "<?php echo base_url(); ?>";
            sec = 'tbl_maths_question';
            is_image = 1;
            table = $('#question_list');
            delete_button = $('#delete_multiple_questions');
            selected = table.bootstrapTable('getSelections');
            ids = "";
            $.each(selected, function(i, e) {
                ids += e.id + ",";
            });
            ids = ids.slice(0, -1);
            if (ids == "") {
                alert("<?= lang('please_select_questions_to_delete'); ?>");
            } else {
                if (confirm("<?= lang('sure_to_delete_all_questions'); ?>")) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'delete_multiple',
                        data: 'ids=' + ids + '&sec=' + sec + '&is_image=' + is_image,
                        beforeSend: function() {
                            delete_button.html('<i class="fa fa-spinner fa-pulse"></i>');
                        },
                        success: function(result) {
                            if (result == 1) {
                                alert("<?= lang('questions_deleted_successfully'); ?>");
                            } else {
                                alert("<?= lang('not_delete_question_try_again'); ?>");
                            }
                            delete_button.html('<i class="fa fa-trash"></i>');
                            table.bootstrapTable('refresh');
                        }
                    });
                }
            }
        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '.delete-data', function() {
            if (confirm("<?= lang('sure_to_delete_questions_releated_data_deleted'); ?>")) {
                var base_url = "<?php echo base_url(); ?>";
                id = $(this).data("id");
                image = $(this).data("image");
                $.ajax({
                    url: base_url + 'delete_maths_questions',
                    type: "POST",
                    data: 'id=' + id + '&image_url=' + image,
                    success: function(result) {
                        if (result) {
                            $('#question_list').bootstrapTable('refresh');
                        } else {
                            var PERMISSION_ERROR_MSG = "<?= lang(PERMISSION_ERROR_MSG); ?>";
                            ErrorMsg(PERMISSION_ERROR_MSG);
                        }
                    }
                });
            }
        });
    </script>

    <script type="text/javascript">
        var base_url = "<?php echo base_url(); ?>";
        var type = 'maths-question-subcategory';

        $('#filter_language').on('change', function(e) {
            var language_id = $('#filter_language').val();
            $.ajax({
                type: 'POST',
                url: base_url + 'get_categories_of_language',
                data: 'language_id=' + language_id + '&type=' + type,
                beforeSend: function() {
                    $('#filter_category').html('<option value=""><?= lang('please_wait'); ?></option>');
                },
                success: function(result) {
                    $('#filter_category').html(result);
                }
            });
        });

        $('#filter_category').on('change', function(e) {
            var category_id = $('#filter_category').val();
            $.ajax({
                type: 'POST',
                url: base_url + 'get_subcategories_of_category',
                data: 'category_id=' + category_id,
                beforeSend: function() {
                    $('#filter_subcategory').html('<option value=""><?= lang('please_wait'); ?></option>');
                },
                success: function(result) {
                    $('#filter_subcategory').html(result);
                }
            });
        });
    </script>

    <script type="text/javascript">
        function queryParams(p) {
            return {
                "language": $('#filter_language').val(),
                "category": $('#filter_category').val(),
                "subcategory": $('#filter_subcategory').val(),
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search
            };
        }
    </script>

</body>

</html>