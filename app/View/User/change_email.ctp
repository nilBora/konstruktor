<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), array('controller' => 'User', 'action' => 'view/'.Hash::get($currUser, 'User.id')));
	$this->Html->addCrumb(__('Change email'), array('controller' => 'User', 'action' => 'changeEmail'));

	$id = $this->request->data('User.id');
?>
<div class="fixedLayout">
	<h1><?=__('Change email')?></h1>
	<?=$this->Form->create('User')?>
	<?=$this->Form->hidden('User.id')?>
		<div class="form-group">
			<label><?=__('New email')?></label>
			<?=$this->Form->input('username', array('class' => 'form-control', 'label' => false, 'value' => ''))?>
		</div>
		<div class="form-group">
			<label><?=__('Confirm email')?></label>
			<?=$this->Form->input('confirm_email', array('class' => 'form-control', 'label' => false, 'value' => ''))?>
		</div>
		<button type="submit" class="btn btn-default save-button" type="button"><?=__('Save')?></button>

	<?=$this->Form->end?>
</div>
<br /><br /><br />



<script type="text/javascript">
function updateSave() {
	var enabled = $('#UserUsername').val() && $('#UserUsername').val() === $('#UserConfirmEmail').val();
	$('.save-button').prop('disabled', !enabled);
	$('.save-button').removeClass('disabled');
	if (!enabled) {
		$('.save-button').addClass('disabled');
	}
}

$(document).ready(function(){
	$('#UserUsername, #UserConfirmEmail').keyup(function(){
		updateSave();
	});

	$('.save-button').click(function(){
		updateSave();
		return !$('.save-button').prop('disabled');
	});
	updateSave();
});
</script>
