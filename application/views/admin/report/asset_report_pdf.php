<!DOCTYPE html>
<html>
<head>
    <title><?= lang('asset_report') ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }?>
    <style>
        th {
            padding: 10px 0px 5px 5px;
        <?php if(!empty($RTL)){?> text-align: right;<?php }else{?>text-align: left;<?php }?>
            font-size: 13px;
            border: 1px solid black;
        }

        td {
            padding: 5px 0px 0px 5px;
            border: 1px solid black;
            font-size: 13px;
        <?php if(!empty($RTL)){?> text-align: right;<?php }else{?>text-align: left;<?php }?>
        }
    </style>

</head>
<body style="min-width: 98%; min-height: 100%; overflow: hidden; alignment-adjust: central;">
<br/>
<?php
$img_path = ROOTPATH . '/' . config_item('company_logo');
if (!file_exists($img_path)) {
    $img_path = ROOTPATH . '/uploads/default_logo.png'; // Fallback image
}

if (file_exists($img_path)) {
    $image_data = file_get_contents($img_path);
    $base64_img = 'data:image/png;base64,' . base64_encode($image_data);
} else {
    $base64_img = ''; // Empty fallback
}
?>
<div style="width: 100%; border-bottom: 2px solid black;">
    <table style="width: 100%; vertical-align: middle;">
        <tr>
            <td style="width: 50px; border: 0px;">
                <img style="width: 130px;height: 50px;margin-bottom: 5px;"
                     src="<?= $base64_img ?>" alt="" class="img-circle"/>
            </td>

            <td style="border: 0px;">
                <p style="margin-left: 10px; font: 14px lighter;"><?= config_item('company_name') ?></p>
            </td>
        </tr>
    </table>
</div>
<br/>
<div style="width: 100%;">
    <table style="width: 100%; font-family: Arial, Helvetica, sans-serif; border-collapse: collapse;">
        <tr>
            <th style="width: 8%"><?= lang('date') ?></th>
            <th style="width: 15%"><?= lang('account') ?></th>
            <th><?= lang('deposit_category') ?></th>
            <th><?= lang('type') ?></th>
            <th><?= lang('notes') ?></th>
            <th><?= lang('amount') ?></th>
            <th><?= lang('credit') ?></th>
            <th><?= lang('debit') ?></th>
            <th><?= lang('balance') ?></th>
            <th><?= lang('acc_balance') ?></th>
        </tr>
        <?php
        $total_amount = 0;
        $total_debit = 0;
        $total_credit = 0;
        $balance = 0;
        $total_balance = 0;
        $curency = $this->report_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        if (!empty($all_transaction_info)): foreach ($all_transaction_info as $v_transaction) :
            $account_info = $this->report_model->check_by(array('account_id' => $v_transaction->account_id), 'tbl_accounts');

            if($v_transaction->type == 'Expense') {
                $category_info = $this->report_model->check_by(array('expense_category_id' => $v_transaction->category_id), 'tbl_expense_category')->expense_category;
            }
            ?>

            <tr style="width: 100%;">
                <td><?= strftime(config_item('date_format'), strtotime($v_transaction->date)); ?></td>
                <td class="vertical-td"><?= $account_info->account_name ?></td>
                <td><?php echo !empty($category_info) ? $category_info : ' - '; ?></td>
                <td class="vertical-td"><?= lang($v_transaction->type) ?> </td>
                <td class="vertical-td"><?= strip_html_tags($v_transaction->notes,true) ?></td>
                <td><?= display_money($v_transaction->amount, $curency->symbol) ?></td>
                <td><?= display_money($v_transaction->credit, $curency->symbol) ?></td>
                <td><?= display_money($v_transaction->debit, $curency->symbol) ?></td>
                <?php if($v_transaction->type == 'Expense') { ?>
                    <td><?= display_money($balance + $v_transaction->debit, $curency->symbol) ?></td>
                <?php } ?>
                <td><?= display_money($v_transaction->total_balance, $curency->symbol) ?></td>
            </tr>

            <?php
            if($v_transaction->type == 'Expense') {
                $balance += $v_transaction->debit;
            }

            $total_amount += $v_transaction->amount;
            $total_debit += $v_transaction->debit;
            $total_credit += $v_transaction->credit;
            $total_balance += $v_transaction->total_balance;
            ?>
        <?php endforeach; ?>
            <tr class="custom-color-with-td">
                <td style="text-align: right;" colspan="5"><strong><?= lang('total') ?>:</strong></td>
                <td><strong><?= display_money($total_amount, $curency->symbol) ?></strong></td>
                <td><strong><?= display_money($total_credit, $curency->symbol) ?></strong></td>
                <td><strong><?= display_money($total_debit, $curency->symbol) ?></strong></td>
                <td><strong><?= display_money($balance, $curency->symbol) ?></strong></td>
                <td colspan="1"></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="7">
                    <strong>There is no Report to display</strong>
                </td>
            </tr>
        <?php endif; ?>
    </table>

</div>
</body>
</html>