<?php
    $viewScripts = array(
        'vendor/bootstrap-datetimepicker.min',
        'vendor/bootstrap-datetimepicker.ru',
        'vendor/jquery/jquery.ui.widget',
        'vendor/jquery/jquery.iframe-transport',
        'vendor/jquery/jquery.fileupload',
        '/table/js/format',
        'upload'
    );
    $this->Html->script($viewScripts, array('inline' => false));

    $id = $this->request->data('Project.id');
    $pageTitle = ($id) ? __('Project settings') : __('Create project');

    $reqGroupID = isset($this->request->named['Project.group_id']) ? $this->request->named['Project.group_id'] : null;
    $class = $hasAccount ? '' : 'hide';

	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($project, 'Project.group_id')));
	$this->Html->addCrumb(Hash::get($project, 'Project.title'), array('controller' => 'Project', 'action' => 'view/'.$id));
	$this->Html->addCrumb($pageTitle, array('controller' => 'Project', 'action' => 'edit/'.$id));
?>

<?=$this->Form->create('Project', array('class' => 'oneFormBlock'))?>
<?=$this->Form->hidden('Project.id')?>

<?php if($reqGroupID) : ?>
        <?=$this->Form->hidden('Project.group_id', array('value' => $reqGroupID))?>
<?php else : ?>
        <?=$this->Form->hidden('Project.group_id')?>
<?php endif; ?>

<?php  if (isset($this->request->query['success']) && $this->request->query['success']) : ?>
	<div align="center">
		<label>
			<?=__('Project has been successfully saved')?>
		</label>
	</div>
<?php endif; ?>

<div class="form-group">
    <label for="group-create-2"><?=__('Project name')?></label>
    <?=$this->Form->input('Project.title', array('label' => false, 'placeholder' => __('Project name').'...', 'class' => 'form-control'))?>
</div>

<div class="form-group">
    <label for="group-create-3"><?=__('Deadline')?></label>
    <div class="input-group date">
        <div class="input-group-addon glyphicons calendar"></div>
<?
    $dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
    $dateValue = $this->LocalDate->date($this->request->data('Project.deadline'));
?>
        <?=$this->Form->input('Project.js_deadline', array('type' => 'text', 'label' => false, 'class' => 'form-control datetimepicker', 'value' => $dateValue, 'data-date-format' => $dateFormat))?>
        <?=$this->Form->hidden('Project.deadline')?>
    </div>
</div>

<div class="form-group">
    <label for="group-create-3"><?=__('Description')?></label>
    <?=$this->Form->input('Project.descr', array('type' => 'textarea', 'label' => false, 'placeholder' => __('Description').'...', 'class' => 'form-control'))?>
</div>

<div class="form-group noBorder">
    <label><?=__('Responsible')?></label>
    <?=$this->Form->input('responsible_id', array('options' => $aMemberOptions, 'class' => 'formstyler', 'label' => false, 'required' => 'required', 'value' => isset($responsibleID) ? $responsibleID : $currUserID))?>
</div>

<div class="form-group noBorder bigCheckBox">
    <?=$this->Form->input('Project.use_account', array('label' => false, 'type' => 'checkbox', 'class' => 'checkboxStyle glyphicons ok_2', 'div' => false, 'checked' => $hasAccount ))?>
    <span class="checkboxText"><?=__('Bind to finances')?></span>
</div>

<div id="accountBlock" class="form-group noBorder <?=$class?>">
    <label><?=__('Account')?></label>
    <?=$this->Form->input('Project.finance_account_id', array('options' => $aFinanceAccounts, 'class' => 'formstyler', 'label' => false, 'required' => $hasAccount))?>
</div>

<div class="form-group noBorder">
    <button type="submit" class="btn btn-primary disabled"><?=__('Save')?></button>
</div>

<?=$this->Form->end()?>

<script type="text/javascript">

var checkFields = function() {
    var enabled = $('#ProjectTitle').val().length >= 1;
    if(enabled) {
        if($('button').hasClass('disabled')) {
            $('button').removeClass('disabled');
        }
    } else {
        if(!$('button').hasClass('disabled')) {
            $('button').addClass('disabled');
        }
    }
}

checkFields();

$('.form-control').bind("keyup change", function() {
    checkFields();
});

$(document).ready(function(){

             $('input.checkboxStyle').styler();

<?
        $lang = 'en';
if(Configure::read('Config.language') == 'rus'){
    $lang = 'ru';
}else{
    $lang = 'en';
}
?>
    $('.datetimepicker').datetimepicker({
                 weekStart: 1,
                 autoclose: 1,
                 todayHighlight: 1,
                 minView: 2,
                 language:"<?=$lang?>"
             });

    $('#ProjectJsDeadline').change(function(){
        $('#ProjectDeadline').val(Date.local2sql($(this).val()));
    });

    $('#ProjectJsDeadline').on('keydown cut', function (event) {
        event.preventDefault();
        event.stopPropagation();
        return false;
    });

    $('#ProjectUseAccount').on('change', function (event) {
        if( $(this).prop('checked') ) {
            $('#accountBlock').removeClass('hide');
            $('#ProjectFinanceAccountId').attr('requred', true);
        } else {
            $('#accountBlock').addClass('hide');
            $('#ProjectFinanceAccountId').addClass('hide');
            $('#ProjectFinanceAccountId').attr('requred', false);
        }
    });

});

$('#ProjectDescr').autosize({append:false});
$('#ProjectDescr').on('keyup copy cut paste change', function() {
    $('#ProjectDescr').trigger('autosize.resize');
});
$('#ProjectDescr').trigger('autosize.resize');
</script>
