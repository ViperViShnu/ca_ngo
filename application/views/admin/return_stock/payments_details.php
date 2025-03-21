<?php
$edited = can_action('152', 'edited');
if (empty($payments_info)) {
    redirect('admin/return_stock');
}
$can_edit = $this->return_stock_model->can_action('tbl_return_stock', 'edit', array('return_stock_id' => $payments_info->return_stock_id));
$return_stock_info = $this->return_stock_model->check_by(array('return_stock_id' => $payments_info->return_stock_id), 'tbl_return_stock');
if ($return_stock_info->module == 'client') {
    $supplier_info = $this->return_stock_model->check_by(array('client_id' => $return_stock_info->module_id), 'tbl_client');
} else if ($return_stock_info->module == 'supplier') {
    $supplier_info = $this->return_stock_model->check_by(array('supplier_id' => $return_stock_info->module_id), 'tbl_suppliers');
}

if (is_numeric($payments_info->payment_method)) {
    $payment_methods = $this->return_stock_model->check_by(array('payment_methods_id' => $payments_info->payment_method), 'tbl_payment_methods');
} else {
    $payment_methods->method_name = $payments_info->payment_method;
}
$currency = $this->return_stock_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
?>
<div class="row">
    <div class="col-sm-3">
        <div class="panel panel-custom">
            <div class="panel-heading">
                <?= lang('all_payments') ?>
            </div>

            <div class="panel-body">
                <section class="scrollable  ">
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0"
                        data-size="5px" data-color="#333333">
                        <ul class="nav"><?php

                                        if (!empty($all_return_stocks)) {
                                            $all_return_stocks = array_reverse($all_return_stocks);
                                            foreach ($all_return_stocks as $v_return_stock) {
                                                $payment_status = $this->return_stock_model->get_payment_status($v_return_stock->return_stock_id);
                                                if ($payment_status == ('fully_paid')) {
                                                    $label = "success";
                                                } elseif ($payment_status == ('draft')) {
                                                    $label = "default";
                                                } elseif ($payment_status == ('cancelled')) {
                                                    $label = "danger";
                                                } elseif ($payment_status == ('partially_paid')) {
                                                    $label = "warning";
                                                } elseif ($v_return_stock->emailed == 'Yes') {
                                                    $label = "info";
                                                    $payment_status = ('sent');
                                                } else {
                                                    $label = "danger";
                                                }
                                        ?>
                            <li class="<?php
                                                if ($v_return_stock->return_stock_id == $this->uri->segment(5)) {
                                                    echo "active";
                                                }
                                                ?>">
                                <?php
                                                if ($v_return_stock->module == 'client') {
                                                    $client_info = $this->return_stock_model->check_by(array('client_id' => $v_return_stock->module_id), 'tbl_client');
                                                } else if ($v_return_stock->module == 'supplier') {
                                                    $client_info = $this->return_stock_model->check_by(array('supplier_id' => $v_return_stock->module_id), 'tbl_suppliers');
                                                }
                                                if (!empty($client_info)) {
                                                    $client_name = lang($v_return_stock->module) . ': ' . $client_info->name;
                                                } else {
                                                    $client_name = '-';
                                                }
                                        ?>
                                <a
                                    href="<?= base_url() ?>admin/return_stock/payments_details/<?= $v_return_stock->return_stock_id ?>">
                                    <?= $client_name ?>
                                    <div class="pull-right">
                                        <?= display_money($this->return_stock_model->get_return_stock_cost($v_return_stock->return_stock_id), $currency->symbol); ?>
                                    </div>
                                    <br>
                                    <small class="block small text-muted"><?= $v_return_stock->reference_no ?>
                                        <span class="label label-<?= $label ?>"><?= lang($payment_status) ?></span>
                                    </small>
                                </a>
                            </li>
                            <?php
                                            }
                                        }
                            ?>
                        </ul>

                    </div>
                </section>
            </div>
        </div>
    </div>

    <section class="col-sm-9">
        <div class="row">
            <section class="panel panel-custom">
                <div class="panel-body">
                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                    <div class="btn-group">
                        <a data-toggle="tooltip" data-placement="top"
                            href="<?= base_url() ?>admin/return_stock/all_payments/<?= $payments_info->payments_id ?>"
                            title="<?= lang('edit_payment') ?>" class="btn btn-sm btn-primary">
                            <i class="fa fa-pencil"></i> <?= lang('edit_payment') ?></a>
                    </div>

                    <a data-toggle="tooltip" data-placement="top"
                        href="<?= base_url() ?>admin/return_stock/send_payment/<?= $payments_info->payments_id . '/' . $payments_info->amount ?>"
                        title="<?= lang('send_email') ?>" class="btn btn-sm btn-danger pull-right ">
                        <i class="fa fa-envelope"></i> <?= lang('send_email') ?></a>


                    <a data-toggle="tooltip" data-placement="top"
                        href="<?= base_url() ?>admin/return_stock/payments_pdf/<?= $payments_info->payments_id ?>"
                        title="<?= lang('pdf') ?>" class="btn btn-sm btn-success pull-right mr">
                        <i class="fa fa-file-pdf-o"></i> <?= lang('pdf') ?></a>
                    <?php } ?>


                    <div class="details-page" style="margin:45px 25px 25px 8px">
                        <div class="details-container clearfix" style="margin-bottom:20px">
                            <div style="font-size:10pt;">

                                <div style="padding:5px;">
                                    <div class="payments_header">
                                        <div>
                                            <div style="text-transform: uppercase;font-weight: bold;">
                                                <div class="pull-left">
                                                    <img style="width: 60px;width: 60px;margin-top: -10px;margin-right: 10px;"
                                                        src="<?= base_url() . config_item('invoice_logo') ?>">
                                                </div>
                                                <div class="pull-left">
                                                    <?= config_item('company_name') ?>
                                                    <p style="color:#999"><?= $this->config->item('company_address') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>
                                    <div style="padding:15px 0 50px;text-align:center">
                                        <span class="payments_header-t"><?= lang('payments_received') ?></span>
                                    </div>
                                    <div style="width: 70%;float: left;">
                                        <div style="width: 100%;padding: 11px 0;">
                                            <div style="color:#999;width:35%;float:left;"><?= lang('payment_date') ?>
                                            </div>
                                            <div class="payment_details_border_semi">
                                                <?= display_date($payments_info->payment_date); ?></div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        <div style="width: 100%;padding: 10px 0;">
                                            <div style="color:#999;width:35%;float:left;"><?= lang('transaction_id') ?>
                                            </div>
                                            <div class="payment_details_border_semi"><?= $payments_info->trans_id ?>
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                    </div>
                                    <div class="amount_received">
                                        <span> <?= lang('amount_received') ?></span><br>
                                        <span
                                            style="font-size:16pt;"><?= display_money($payments_info->amount, $currency->symbol); ?></span>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div style="padding-top:10px">
                                        <div class="payment_details_border">
                                            <strong><?= (!empty($supplier_info->name) ? $supplier_info->name : '') ?></strong>
                                        </div>
                                        <div style="color:#999;width:25%"><?= lang('paid') . ' ' . lang('TO') ?></div>
                                    </div>
                                    <?php
                                    $role = $this->session->userdata('user_type');
                                    if ($role == 1 && $payments_info->account_id != 0) {
                                        $account_info = $this->return_stock_model->check_by(array('account_id' => $payments_info->account_id), 'tbl_accounts');
                                        if (!empty($account_info)) {
                                    ?>
                                    <div style="padding-top:25px">
                                        <div class="payment_details_border">
                                            <a
                                                href="<?= base_url() ?>admin/account/create_account/<?php echo $account_info->account_id; ?>"><?= $account_info->account_name ?></a>
                                        </div>
                                        <div style="color:#999;width:25%"><?= lang('received_account') ?></div>
                                    </div>
                                    <?php }
                                    } ?>
                                    <div style="padding-top:25px">
                                        <div class="payment_details_border">
                                            <?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-' ?>
                                        </div>
                                        <div style="color:#999;width:25%"><?= lang('payment_mode') ?></div>
                                    </div>

                                    <div style="padding-top:25px">
                                        <div class="payment_details_border"><?= $payments_info->notes ?></div>
                                        <div style="color:#999;width:25%"><?= lang('notes') ?></div>
                                    </div>
                                    <?php $return_stock_due = $this->return_stock_model->calculate_to('return_stock_due', $payments_info->return_stock_id); ?>

                                    <div style="margin-top:50px">
                                        <div style="width:100%">
                                            <div style="width:50%;float:left">
                                                <h4><?= lang('payment_for') ?></h4>
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>

                                        <table style="width:100%;margin-bottom:35px;table-layout:fixed;" cellpadding="0"
                                            cellspacing="0" border="0">
                                            <thead>
                                                <tr class="payment_header">
                                                    <td style="padding:5px 10px 5px 10px;word-wrap: break-word;">
                                                        <?= lang('reference_no') ?>
                                                    </td>
                                                    <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                                        align="right">
                                                        <?= lang('return_stock_date') ?>
                                                    </td>
                                                    <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                                        align="right">
                                                        <?= lang('return_stock') . ' ' . lang('amount') ?>
                                                    </td>
                                                    <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                                        align="right">
                                                        <?= lang('paid_amount') ?>
                                                    </td>
                                                    <?php if ($return_stock_due > 0) { ?>
                                                    <td style="padding:5px 10px 5px 5px;color:red;word-wrap: break-word;"
                                                        align="right">
                                                        <?= lang('due_amount') ?>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="cbb">
                                                    <td style="padding: 10px 0px 10px 10px;" valign="top"><a
                                                            href="<?= base_url() ?>admin/return_stock/return_stock_details/<?= $payments_info->return_stock_id ?>">
                                                            <?= $return_stock_info->reference_no ?></a>
                                                    </td>
                                                    <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;"
                                                        valign="top">
                                                        <?= display_date($return_stock_info->return_stock_date) ?>
                                                    </td>
                                                    <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;"
                                                        valign="top">
                                                        <span><?= display_money($this->return_stock_model->calculate_to('total', $payments_info->return_stock_id), $currency->symbol); ?></span>
                                                    </td>
                                                    <td style="text-align:right;padding: 10px 10px 10px 5px;word-wrap: break-word;"
                                                        valign="top">
                                                        <span><?= display_money($payments_info->amount, $currency->symbol); ?></span>
                                                    </td>
                                                    <?php if ($return_stock_due > 0) { ?>
                                                    <td style="text-align:right;padding: 10px 10px 10px 5px;word-wrap: break-word;color: red"
                                                        valign="top">
                                                        <span><?= display_money($return_stock_due, $currency->symbol); ?></span>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Payment -->
            </section>
        </div>
    </section>
</div>
<!-- end -->