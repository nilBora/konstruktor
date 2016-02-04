<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($group, 'Group.id')));
	$this->Html->addCrumb(__('Finances'), array('controller' => 'FinanceProject', 'action' => 'index/'.$id));

$this->Html->script(array(
    'vendor/bootstrap-tokenfield',
    'https://www.google.com/jsapi',
    'vendor/bootstrap-datetimepicker.min',
    'vendor/bootstrap-datetimepicker.ru.js',
), array('inline' => false));

echo $this->Html->css(array(
    'bootstrap/bootstrap-tokenfield'
));
?>

<?= $this->element('Finance/project_top') ?>
<div class="financeSettings">
    <?= $this->element('Finance/project_nav') ?>
    <div class="clearfix">
        <div class="invoice">
            <? if ($isOwner || $isFullAccess) { ?>
                <?= $this->element('Finance/project_currency_balance') ?>
            <? } ?>
            <? if ($isOwner || $isFullAccess || !empty($currUserShare['accounts'])) { ?>
                <?= $this->element('Finance/project_account_list') ?>
            <? } ?>
        </div>
        <?= $this->element('Finance/operation_forms') ?>
        <? if ($isOwner || $isFullAccess) { ?>
            <?= $this->element('Finance/project_regular_payment') ?>
        <? } ?>
    </div>
    <div class="balance">
        <? if ($isOwner || $isFullAccess) { ?>
            <?= $this->element('Finance/chart_balance') ?>
        <? } ?>
    </div>
    <div class="row">
        <div class="col-sm-6 costs">
            <? if ($isOwner || $isFullAccess) { ?>
                <?= $this->element('Finance/chart_statistic') ?>
            <? } ?>
        </div>
        <div class="col-sm-6 compare">
            <? if ($isOwner || $isFullAccess) { ?>
                <?= $this->element('Finance/chart_compare') ?>
            <? } ?>
        </div>
    </div>
</div>

<script type="application/javascript">
// Load google charts
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(function () {
    chartBalanceRender();
    chartStatisticRender();
    chartCompareRender();
});
$(document).ready(function(){
// Init page
    $('select, input.checkboxStyle').styler();
// Init currency symbols
    financeSymbolFor = function (code) {
        return <?= json_encode($this->Money->symbols()) ?>[code];
    }
});
</script>
