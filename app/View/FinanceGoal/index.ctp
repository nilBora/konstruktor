<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($group, 'Group.id')));
	$this->Html->addCrumb(__('Finances'), array('controller' => 'FinanceProject', 'action' => 'index/'.$id));
	$this->Html->addCrumb(__('Goals'), array('controller' => 'FinanceGoal', 'action' => 'index/'.$id));

$this->Html->script(array(
    'vendor/bootstrap-datetimepicker.min',
    'vendor/bootstrap-datetimepicker.ru.js',
), array('inline' => false));
?>

<?= $this->element('Finance/project_top') ?>
<div class="financeSettings fixedLayout">
    <?= $this->element('Finance/project_nav') ?>
    <div class="addButtons">
        <a class="btn btn-default"  data-toggle="modal" data-target="#finance-modal-save-goal" href="<?= $this->Html->url(array('controller' => 'FinanceGoal', 'action' => 'addGoal', $id)) ?>">
            <?= __('New goal') ?>
        </a>
    </div>
    <?= $this->element('Finance/goals_list') ?>
</div>
<?= $this->element('Finance/modal_save_goal') ?>

<script type="application/javascript">
$(document).ready(function () {
// Close goal modal
    $('#finance-modal-save-goal').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
    });
});
</script>
