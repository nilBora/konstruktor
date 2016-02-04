<div class="front-header">
	<a class="logo-company" href="/">
		<img alt="" src="/img/logo2.png" class="logo-image">
	</a>
</div>
<div class="rightContent clearfix" id="password_form_block">
	<?=$this->Form->create('User', array('url' => array('controller' => 'User', 'action' => 'forgetPassword'), 'class' => 'formFields', 'id' => 'passwdForm', 'autocomplete' => 'false'))?>
		<input type="hidden" name="data[User][id]" value="<?=$user['User']['id']?>">
		<div class="form-group">
			<?=$this->Form->input('password', array('label' => false, 'placeholder' => __('New password'), 'class' => 'form-control', 'id' => 'passwd', 'value' => '', 'autocomplete' => 'off', 'disabled' => 'disabled'))?>
		</div>
		<div class="form-group">
			<?=$this->Form->input('confirm_password', array('type' => 'password', 'label' => false, 'placeholder' => __('Confirm password'), 'class' => 'form-control', 'id' => 'passwdConfirm', 'value' => '', 'autocomplete' => 'off'))?>
		</div>
		<div class="clearfix">
			<button class="" id="resetPass" type="submit"><?=__('Change password')?></button>
		</div>
	<?=$this->Form->end()?>

	<div class="modal fade" id="passwdLetterSent" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="outer-modal-dialog">
			<div class="modal-dialog">
				<div class="modal-content small-size">
					<?=__('Your password was successfully changed. Now, please, log in with your new password.')?><br /><br />
					<a href="javascript: void(0)" class="btn btn-primary" data-dismiss="modal"><?=__('Thanks');?></a>
				</div>
			</div>
		</div>
	</div>
</div>





<script type="text/javascript">

function updatePass() {
	var enabled = $('#passwd').val() === $('#passwdConfirm').val() && $('#passwdConfirm').val().length > 3;
	if(enabled) {
		if($('#resetPass').hasClass('disabled')) {
			$('#resetPass').removeClass('disabled');
		}
	} else {
		if(!$('#resetPass').hasClass('disabled')) {
			$('#resetPass').addClass('disabled');
		}
	}
}

$(document).ready(function() {
	setTimeout(function(){
		$('#passwd').prop('disabled', false);
	}, 500);

	$('.form-control').on('keyup', function() {
		updatePass();
	});

	$('.form-control').on('change', function() {
		updatePass();
	});

	$('#resetPass').click ( function() {

		$.post(
			'<?=$this->Html->url(array('controller' => 'User', 'action' => 'passwordRequest'))?>',
			$('#passwdForm').serialize(),
			function(response){
//				$('#passwdLetterSent').modal();
			}
		);

//		$('#passwdLetterSent').modal();
	});

});
</script>
