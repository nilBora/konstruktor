<?=$this->Form->create('User', array('class' => 'formFields', 'id' => 'passwdForm'))?>
	<div class="form-group">
		<?=$this->Form->input('username', array('label' => false, 'placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'passwdMail'))?>
	</div>
	<div class="clearfix">
		<a href="javascript: void(0)" class="btn btn-default pull-right" id="passwdReset"><?=__('Send')?></a>
	</div>
	<div class="recovery-message">
		<?=__('An mail with further instructions was sent to your email address')?><br /><br />
	</div>
<?=$this->Form->end()?>

<script type="text/javascript">

	$('#passwdForm .input').keypress(function (e) {
		if (e.which == 13) {
			e.preventDefault();
			$('#passwdReset').trigger('click');
		}
	});

	$('#passwdReset').click ( function() {
		var allow = checkPassFields();
		if(allow) {
			$.post(
				'<?=$this->Html->url(array('controller' => 'User', 'action' => 'passwordRequest'))?>',
				$('#passwdForm').serialize(),
				function(response){
					$('#passwdMail').hide();
					$('#passwdReset').hide();
					$('.recovery-message').fadeIn(500);
				}
			);
		}
	});

	function checkPassFields() {
		var allow = true;

		if( IsEmail($('#passwdMail').val()) ) {
			$('#passwdMail').removeClass('incorrect');
		} else {
			$('#passwdMail').val('');
			$('#passwdMail').removeClass('incorrect').addClass('incorrect');
			$('#passwdMail').popover('destroy');

			allow = false;
		}

		if(!allow) {
			setTimeout((function () {
				var placement = ($(window).width() < 768) ? 'bottom' : 'left';
				$('#passwdMail').popover({ toggle: 'popover', placement: placement, content: "<?=__('Enter valid email address')?>" });
				$('#passwdMail').popover('show');
			}), 500);
		}

		return allow;
	}

	$('#passwdForm input').click( function() {
		if($(this).hasClass('incorrect')) {
			$(this).popover('destroy');
			$(this).removeClass('incorrect');
		}
	});

</script>
