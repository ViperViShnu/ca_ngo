<?php
if (!empty($project_files_info)) {
    ?>
    <div class="p">
        <div class="row">
            <?php
            $this->load->helper('file');
            foreach ($project_files_info as $key => $v_files_info) {
                $uploaded_by = $this->db->where('user_id', $v_files_info[0]->user_id)->get('tbl_account_details')->row();
                ?>

                <div class="col-md-4 pr0 mb-sm" id="attachment_container-<?= $v_files_info[0]->attachments_id ?>">
                    <div class="box-shadow">
                        <div class="col-sm-12 p0">
                            <p style="border-bottom: 1px solid #e4e5e0;">
                                <a href="<?= base_url() ?>client/projects/attachment_details/g/<?= $v_files_info[0]->attachments_id ?>"
                                   data-toggle="modal" data-target="#myModal_extra_lg">
                                    <small
                                        class="text-gray-dark"><?= '<b style="color:#000">' . $uploaded_by->fullname . '</b>' . ' ' . lang('uploaded') . '  ' . count(array($v_files_info)) . ' ' . lang('attachment') ?>
                                        <br/>
                                        - <?= $v_files_info[0]->title ?>
                                    </small>
                                </a>
                                <a data-toggle="tooltip" data-placement="top"
                                   title="<?= lang('download') . ' ' . lang('all') ?>"
                                   style="position: absolute;top: 0;right: 0"
                                   href="<?= base_url() ?>client/projects/download_all_files/<?= $v_files_info[0]->attachments_id ?>"
                                   class="pull-right"><i
                                        class="fa fa-cloud-download"></i></a>
                                <?php if ($v_files_info[0]->user_id == $this->session->userdata('user_id')) { ?>
                                    <small class="pull-right">
                                        <?php echo ajax_anchor(base_url("client/projects/delete_files/" . $v_files_info[0]->attachments_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#attachment_container-" . $v_files_info[0]->attachments_id)); ?>
                                    </small>
                                <?php } ?>
                            </p>
                        </div>
                        <?php
                        $limit = 3;
                        shuffle($v_files_info);
                        if (!empty($v_files_info)) {
                            foreach ($v_files_info as $l_key => $v_files) {

                                if (!empty($v_files)) {
                                    if ($l_key <= $limit) {
                                        ?>
                                        <div class="col-sm-6 p0 pr-sm mb-sm">
                                            <div class="hovereffect">
                                                <a data-toggle="modal" data-target="#myModal_extra_lg"
                                                   href="<?= base_url() ?>client/projects/attachment_details/r/<?= $v_files_info[0]->attachments_id . '/' . $v_files->uploaded_files_id ?>">
                                                    <?php if ($v_files->is_image == 1) : ?>
                                                        <img class="img-responsive"
                                                             src="<?= base_url() . $v_files->files ?>"
                                                             alt="">
                                                    <?php else : ?>
                                                        <span class="icon"><i
                                                                class="fa fa-file-pdf-o"></i></span>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="overlay">
                                                    <a data-toggle="modal"
                                                       data-target="#myModal_extra_lg"
                                                       href="<?= base_url() ?>client/projects/attachment_details/r/<?= $v_files_info[0]->attachments_id . '/' . $v_files->uploaded_files_id ?>"
                                                       class="name"><i
                                                            class="fa fa-paperclip"></i> <?php
                                                        $fileName = (strlen($v_files->file_name) > 11) ? strip_html_tags(mb_substr($v_files->file_name, 0, 11)) . '...' : $v_files->file_name;
                                                        echo $fileName;
                                                        ?>
                                                    </a>
                                                    <p class="time m0 p0"> <?= date('Y-m-d' . "<br/> h:m A", strtotime($v_files_info[0]->upload_time)); ?></p>
                                                                    <span
                                                                        class="size"> <?= $v_files->size ?> <?= lang('kb') ?>
                                                                        <a href="<?= base_url() ?>client/projects/download_files/<?= $v_files->uploaded_files_id ?>"
                                                                           class="pull-right"><i
                                                                                class="fa fa-cloud-download"></i></a></span>
                                                </div>
                                            </div>
                                            <?php if ($l_key == 3) {
                                                $more = count(array($v_files_info)) - 4;
                                                if (!empty($more)) {
                                                    ?><a
                                                    data-toggle="modal"
                                                    data-target="#myModal_extra_lg"
                                                    href="<?= base_url() ?>client/projects/attachment_details/g/<?= $v_files_info[0]->attachments_id ?>">
                                                    <span
                                                        class="more">+<?= $more ?></span>
                                                    </a>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                    <?php } ?>
                                <?php }
                            }
                        } ?>
                    </div>
                </div>
                <?php
            } ?>
        </div>
    </div>
<?php } ?>