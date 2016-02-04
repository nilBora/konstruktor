<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), array('controller' => 'User', 'action' => 'view/'.Hash::get($currUser, 'User.id')));
	$this->Html->addCrumb(__('Change password'), array('controller' => 'User', 'action' => 'changePassword'));

	$id = $this->request->data('User.id');
?>
<div class="fixedLayout">
	<h1><?=__('Change password')?></h1>
	<?=$this->Form->create('User')?>
	<?=$this->Form->hidden('User.id')?>
		<div class="form-group">
			<label><?=__('New password')?></label>
			<?=$this->Form->input('password', array('class' => 'form-control', 'label' => false, 'value' => '', 'autocomplete' => 'false'))?>
		</div>
		<div class="form-group">
			<label><?=__('Confirm password')?></label>
			<?=$this->Form->input('confirm_password', array('type' => 'password', 'class' => 'form-control', 'label' => false, 'value' => '', 'autocomplete' => 'false'))?>
		</div>
		<button type="submit" class="btn btn-default save-button" type="button"><?=__('Save')?></button>

	<?=$this->Form->end?>
</div>
<br /><br /><br />



<script type="text/javascript">
function updateSave() {
	var enabled = $('#UserPassword').val() && $('#UserPassword').val() === $('#UserConfirmPassword').val();
	$('.save-button').prop('disabled', !enabled);
	$('.save-button').removeClass('disabled');
	if (!enabled) {
		$('.save-button').addClass('disabled');
	}
}

$(document).ready(function(){
	$('#UserUsername, #UserConfirmPassword').keyup(function(){
		updateSave();
	});

	$('.save-button').click(function(){
		updateSave();
		return !$('.save-button').prop('disabled');
	});
	updateSave();
});
</script>
