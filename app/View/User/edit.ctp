<?
	$viewStyles = array(
		'jquery.Jcrop.min',
		'bootstrap/bootstrap-tokenfield'
	);

	$this->Html->css($viewStyles, null, array('inline' => false));

	$viewScripts = array(
		'vendor/bootstrap-datetimepicker.min',
		'vendor/bootstrap-datetimepicker.ru',
		'vendor/bootstrap-tokenfield',
		'vendor/jquery/jquery.Jcrop.min',
		'vendor/jquery/jquery.iframe-transport',
		'vendor/jquery/jquery.fileupload',
		'vendor/exif',
		'/table/js/format',
		'upload'
	);
	$this->Html->script($viewScripts, array('inline' => false));

	$id = $this->request->data('User.id');

?>

<style type="text/css">

	.popover.bottom > .arrow:after {
		border-bottom-color: #00aaaa;
		border-left-width: 4px;
		border-right-width: 4px;
		bottom: 0;
	}

	.error-message {
		font-weight: 900;
		color: red;
	}

	.popover.bottom > .arrow {
		border-bottom-color: transparent;
		top: -9px;
		margin-left: -8px;
	}

	.popover > .arrow:after { border-width: 8px;}

	.ui-autocomplete { max-height: 400px; overflow: auto; }

</style>

<h1><?=__('User settings')?></h1>

<?=$this->Form->create('User')?>
<?=$this->Form->hidden('User.id')?>

<div class="row baseInfoRow">

	<div class="col-sm-3 leftFormBlock">

		<div class="avatar-img-User">
			<img id="User<?=$id?>" src="<?=$this->Media->imageUrl($this->request->data('UserMedia'), 'thumb200x200')?>" alt="" class="img-responsive"  data-resize="thumb200x200" data-id="<?=$this->request->data('UserMedia.id')?>"/>
			<?php echo $this->Avatar->user($user, array(
				'id' => 'User'.$id,
				'class' => 'ava-bottom img-responsive',
				'alt' => $this->request->data('User.full_name'),
				'data-id' => $this->request->data('UserMedia.id'),
				'data-resize' => 'thumb200x197',
				//take down width and height by avatar border(usually 3px) x 2
				'size' => 'thumb200x197',
			)); ?>

		</div>

		<div class="inputFile">
			<input id="userAvatarChoose" class="filestyle fileuploader" type="file" data-object_type="User" data-object_id="<?=$id?>" data-progress_id="progress-User<?=$id?>" accept="image/*"/>
			<input id="userAvatarUpload" type="button" class="btn btn-primary save-button upload" value="<?=__('Save and upload')?>" style="display: none;" />
		</div>

		<div class="progress" id="progress-User<?=$id?>">
			<div class="progress-bar progress-bar-info" role="progressbar">
				<span id="progress-stats"></span>
			</div>
		</div>

		<br />
		<div class="editUserLink">
			<span class="glyphicons life_preserver"></span>
			<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'tickets'))?>" class="underlink"><?=__('Technical support')?></a>
		</div>
		<br />
		<div class="editUserLink">
			<span class="glyphicons message_full"></span>
			<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'changeEmail'))?>" class="underlink"><?=__('Change email')?></a>
		</div>
		<div class="editUserLink">
			<span class="glyphicons unlock"></span>
			<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'changePassword'))?>" class="underlink"><?=__('Change password')?></a>
		</div>
	</div>

	<div class="col-sm-9 rightFormBlock">
<?
	if (isset($this->request->query['success']) && $this->request->query['success']) {
?>
		<div align="center">
			<label>
				<?=__('User has been successfully saved')?>
			</label>
		</div>
<?
	}
?>
		<div class="form-group">
			<label><?=__('Full name')?></label>
			<?=$this->Form->input('User.full_name', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Full name').'...'))?>
		</div>

		<div class="form-group">
			<label><?=__('Skills')?></label>
			<?=$this->Form->input('User.skills', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'userSkill', 'placeholder' => __('Skills').'...'))?>
		</div>

		<div class="form-group">
			<label><?=__('Profile url')?></label>
			<?=$this->Form->input('User.profile_url', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'profileUrl', 'placeholder' => $id, 'maxlength' => 32))?>
		</div>

		<div class="form-group">
			<label><?=__('My video')?></label>
			<div class="input-group">
				<div class="input-group-addon glyphicons facetime_video"></div>
				<?=$this->Form->input('User.video_url', array('label' => false, 'class' => 'tokenfield form-control', 'placeholder' => 'http://youtube.com...'))?>
			</div>
		</div>

		<div class="form-group">
			<label><?=__('Phone')?></label>
			<div class="input-group">
				<div class="input-group-addon glyphicons earphone"></div>
				<?=$this->Form->input('User.phone', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Phone').'...'))?>
			</div>
		</div>

		<div class="form-group">
			<label><?=__('Birthday')?></label>
			<div class="input-group date">
<?
	$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
	$dateValue = $this->LocalDate->date($this->request->data('User.birthday'));
?>
				<div class="input-group-addon glyphicons calendar" ></div>
				<?=$this->Form->input('User.js_birthday', array('type' => 'text', 'label' => false, 'class' => 'form-control datetimepicker', 'value' => $dateValue, 'data-date-format' => $dateFormat))?>
				<?=$this->Form->hidden('User.birthday')?>
			</div>
		</div>
<?php
	$country = Hash::get($currUser, 'User.live_country');
	if($country and !array_key_exists($country,$aMainCountries)){
				$aMainCountries[$country] = $aAllCountries[$country];
	}
	asort($aMainCountries);
	$aMainCountries['more'] = "-- [".__('Show More')."] --";
?>
		<div class="form-group noBorder">
			<label><?=__('Country of residence')?></label>
			<div class="input-group input-large">
				<span class="input-group-addon glyphicon-extended glyphicons direction"></span>
				<?=$this->Form->input('User.live_country', array('options' => $aMainCountries, 'empty' => __('Select country'), 'label' => false, 'class' => 'formstyler countrySelect'))?>
			</div>
		</div>

		<div class="form-group">
			<label><?=__('City/town of residence')?></label>
			<div class="input-group">
				<div class="input-group-addon glyphicons direction"></div>
				<?=$this->Form->input('User.live_place', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => __('City/town of residence').'...'))?>
			</div>
		</div>

		<div class="form-group">
			<label><?=__('Address')?></label>
			<div class="input-group">
				<div class="input-group-addon glyphicons direction"></div>
				<?=$this->Form->input('User.live_address', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => __('Address').'...'))?>
			</div>
		</div>

		<div class="form-group">
			<label><?=__('University/college')?></label>
			<?=$this->Form->input('User.university', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => __('University/college').'...'))?>
		</div>

		<div class="form-group">
			<label><?=__('Occupation')?></label>
			<?=$this->Form->input('User.speciality', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Occupation').'...'))?>
		</div>

		<div class="form-group noBorder">
			<label><?=__('University/college photo')?></label>
			<div class="gallery-add page-menu clearfix">
				<img id="UserUniversity<?=$id?>" alt="" src="<?=$this->Media->imageUrl($this->request->data('UniversityMedia'), 'thumb200x200')?>" data-media_id="<?=$this->request->data('UniversityMedia.id')?>" data-resize="thumb200x200">
				<div class="photoButtons">
					<input id="userUniversityChoose" class="fileuploader filestyle" type="file" data-object_type="UserUniversity" data-object_id="<?=$id?>" data-progress_id="progress-UserUniversity<?=$id?>" accept="image/*" />
					<span id="progress-UserUniversity<?=$id?>">
						<div id="progress-bar">
							<div id="progress-stats"></div>
						</div>
					</span>
				</div>
			</div>
		</div>

	</div>
</div>

<div class="row profile-achievements-block">
	<div class="col-sm-3 leftFormBlock addNewField">
		<a href="javascript:void(0)" class="addNewInfo">
			<span class="glyphicons circle_plus"></span>
			<span class="title"><?=__('Add user\'s accomplishments')?></span>
		</a>
	</div>
	<div class="col-sm-9 rightFormBlock">
<?
	$aAchiev = $this->request->data('UserAchievement');
	if (!$aAchiev) {
?>

		<div class="form-group noBorder no-items">
			<?=__('No achivements yet')?>
		</div>
<?
	} else {
		foreach($aAchiev as $i => $row) {
?>
		<div id="achieve_<?=$i?>" class="group-fieldset achievementBlock">
			<input type="hidden" name="data[UserAchievement][<?=$i?>][id]" value="<?=Hash::get($row, 'id')?>">
			<input type="hidden" name="data[UserAchievement][<?=$i?>][profile_id]" value="<?=$id?>">
			<div class="form-group">
				<label for="achiv-title<?=$i?>"><?=__('Achievement')?></label>
				<input class="form-control" id="achiv-title<?=$i?>" name="data[UserAchievement][<?=$i?>][title]" placeholder="<?=__('Achievement')?>..." value="<?=Hash::get($row, 'title')?>">
			</div>
			<div class="form-group">
				<label for="achiv-url<?=$i?>"><?=__('Link to verified accomplishments')?></label>
				<input id="achiv-url<?=$i?>" class="form-control" type="text" name="data[UserAchievement][<?=$i?>][url]" value="<?=Hash::get($row, 'url')?>" placeholder="http://yoursite.com..."/>
			</div>
			<div class="clearfix" style="margin-top: -10px; margin-bottom: 10px">
				<button type="button" class="btn btn-default pull-right" onclick="$('#achieve_<?=$i?>').remove()"><?=__('Delete')?></button>
			</div>
		</div>
<?
		}
	}
?>
		<div class="form-group noBorder">
			<label><?=__('Interface language')?></label>
			<?=$this->Form->input('User.lang', array('label' => false, 'options' => $aLangOptions, 'id' => 'settings-input-row-lang'))?>
		</div>

		<div class="form-group noBorder">
			<label for="settings-input-row-lang"><?=__('Timezone')?></label>
			<?=$this->Form->input('User.timezone', array('label' => false, 'options' => $aTimezoneOptions))?>
		</div>

		<div class="form-group noBorder clearfix">
			<button type="submit" class="btn btn-primary" id="submitBtn"><?=__('Save')?></button>

			<div class="addLink">
				<a class="underlink" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view'))?>"><?=__('How other people see this page')?></a>
			</div>
		</div>

	</div>
</div>
<?=$this->Form->end()?>
<br /><br /><br />


<script type="text/javascript">
$(document).ready(function(){
	$('.countrySelect').bind('change',function(){
			if($(this).find(":selected").val()=='more'){
				$(this).find("option").remove();
				$(this).append($('.allCountries').html());
				$(this).trigger('refresh');
			}
	});

	$('.progress').css('height', '0');
<?php

if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>;
	$('.datetimepicker').datetimepicker({
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		language:"<?=$lang?>",
		endDate: '-1d'
	});

	$('#UserJsBirthday').change(function(){
		$('#UserBirthday').val(Date.local2sql($(this).val()));
	});

	$('#UserJsBirthday').on('keydown cut', function (event) {
		event.preventDefault();
		event.stopPropagation();
		return false;
	});

	$('.profile-achievements-block .addNewInfo').click(function(){
		$('.profile-achievements-block .no-items').remove();
		$('.profile-achievements-block .rightFormBlock').prepend(
			tmpl('profile-achiev', {i: $('.profile-achievements-block .rightFormBlock .achievementBlock').length})
		);
	});

	$('#userAvatarChoose').styler({
		fileBrowse: '<?=__('Choose image')?>'
	});
	$('select, input.filestyle, input.checkboxStyle').styler({fileBrowse: 'Загрузить фото'});


	$('#userAvatarUpload').click(function(){
		$(this).data().submit();
	});

	$('#UserEditForm').submit(function() {
		var d = new Date( $('#UserBirthday').val() );
		var valid = true;

		//Валидация возраста
		if( calculateAge(d) <= 13 ) {
			if( (/webOS|Android|iPad|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ) {
				$('#UserJsBirthday').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('You need to be at least thirteen years of age to use this service')?>" });
			} else {
				$('#UserJsBirthday').popover({ toggle: 'popover', placement: 'right', content: "<?=__('You need to be at least thirteen years of age to use this service')?>" });
			}
			$('html, body').animate({
				scrollTop: $("#UserJsBirthday").offset().top - 100
			}, 400, function() {
				$('#UserBirthday').val('');
				$('#UserJsBirthday').val('');
				$('#UserJsBirthday').popover('show');
			});
			valid = false;
		}

		//Валидация ссылки на youtube
		if(!( $('#UserVideoUrl').val().length === 0 ) && ( !IsYoutubeUrl($('#UserVideoUrl').val()) )) {
			if( (/webOS|Android|iPad|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ) {
				$('#UserVideoUrl').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Invalid video url. Leave blank or insert valid youtube url')?>" });
			} else {
				$('#UserVideoUrl').popover({ toggle: 'popover', placement: 'right', content: "<?=__('Invalid video url. Leave blank or insert valid youtube url')?>" });
			}
			$('html, body').animate({
				scrollTop: $("#UserVideoUrl").offset().top - 100
			}, 400, function() {
				//$('#UserVideoUrl').val('');
				$('#UserVideoUrl').popover('show');
			});
			valid = false;
		}

		//Валидация ссылки - проверка на содержание чаров, не только чисел, а так же длины
		if( !TestString($('#profileUrl').val(), 3) && $('#profileUrl').val().length != 0 ) {
			if( (/webOS|Android|iPad|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ) {
				$('#profileUrl').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Atleast 1 letter and 3 chars length required')?>" });
			} else {
				$('#profileUrl').popover({ toggle: 'popover', placement: 'right', content: "<?=__('Atleast 1 letter and 3 chars length required')?>" });
			}
			$('html, body').animate({
				scrollTop: $("#profileUrl").offset().top - 100
			}, 400, function() {
				//$('#UserVideoUrl').val('');
				$('#profileUrl').popover('show');
			});
			valid = false;
		}


		if(!valid) {
			return false;
		}
	});

	$('#UserVideoUrl, #UserJsBirthday, #profileUrl').click(function(){
		$(this).popover('destroy');
	});

	function IsYoutubeUrl( url ) {
		var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
		/*			|	  protocol    |	subdomain |		  domain name		|						URI with video						 |*/
		return regex.test(url);
	}

	function calculateAge( birthday ) { // birthday is a date
		var ageDifMs = Date.now() - birthday.getTime();
		var ageDate = new Date(ageDifMs); // miliseconds from epoch
		return Math.abs(ageDate.getUTCFullYear() - 1970);
	}

	var skillList = <?=$aSkills?>;
	var skillAdded = false;

	function split( val ) {
		return val.split( /,\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}

	$( "#userSkill" )
		// don't navigate away from the field on tab when selecting an item
		.on( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).autocomplete( "instance" ).menu.active ) {
				event.preventDefault();
			}
			if(skillAdded) {
				$(this).val($(this).val() + ', ');
				skillAdded = false;
			}
		})
		.on( "changed", function() {
			console.log( $('#userSkill').val() );
		})
		.tokenfield({
			autocomplete: {
			minLength: 0,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						skillList, extractLast( request.term ) ) );

				},
				delay: 100
			},
			showAutocompleteOnFocus: true
		})
		.on('tokenfield:createtoken tokenfield:createdtoken tokenfield:edittoken tokenfield:removedtoken', function (e) {
			console.log( $('#userSkill').tokenfield('getTokens') );
			console.log( $('#userSkill').val() );
		});

	// Overrides the default autocomplete filter function to search only from the beginning of the string
	$.ui.autocomplete.filter = function (array, term) {
		var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(term), "i");
		return $.grep(array, function (value) {
			return matcher.test(value.label || value.value || value);
		});
	};

	/*
	$('#userSkill').autocomplete({
  		delay: 0,
		source: skillList
	});
	*/
	$('#profileUrl').keypress(function(event){
		var ew = event.which;
		if(48 <= ew && ew <= 57 && $('#profileUrl').val().length > 0)
			return true;
		if(65 <= ew && ew <= 90)
			return true;
		if(97 <= ew && ew <= 122)
			return true;
		return false;
	});

	$('#profileUrl').keyup(function(event){
		if($('#profileUrl').val().length == 1 && /[0-9]/.test($('#profileUrl').val())) {
			$('#profileUrl').val('');

			$('#profileUrl').popover('destroy');
			if( (/webOS|Android|iPad|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ) {
				$('#profileUrl').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Digit can not be the first character')?>" });
			} else {
				$('#profileUrl').popover({ toggle: 'popover', placement: 'right', content: "<?=__('Digit can not be the first character')?>" });
			}
			$('#profileUrl').popover('show');

			setTimeout(function () {
				$('#profileUrl').popover('destroy');
			}, 3000);

			return false;
		}
		return true;
	});

	function TestString(str, sLen) {
		return ( (str.length >= sLen) && (/[a-zA-Z]/.test(str) || /[а-яА-Я]/.test(str)) )
	}
});
</script>

<script type="text/x-tmpl" id="profile-achiev">
<div class="group-fieldset achievementBlock">
	<input type="hidden" name="data[UserAchievement][{%=o.i%}][id]" value="">
	<input type="hidden" name="data[UserAchievement][{%=o.i%}][profile_id]" value="<?=$id?>">
	<div class="form-group">
		<label for="achiv-title{%=o.i%}"><?=__('Achievement')?></label>
		<input class="form-control" id="achiv-title{%=o.i%}" name="data[UserAchievement][{%=o.i%}][title]" placeholder="<?=__('Achievement')?>...">
	</div>
	<div class="form-group">
		<label for="achiv-url{%=o.i%}"><?=__('Link to verified accomplishments')?></label>
		<input id="achiv-url{%=o.i%}" class="form-control" type="text" name="data[UserAchievement][{%=o.i%}][url]" value="" placeholder="http://yoursite.com..."/>
	</div>
</div>
</script>
<div class="allCountries" style="display:none">
<?php
	asort($aAllCountries);
	foreach($aAllCountries as $code=>$name){ ?>
		<option value="<?php echo $code?>"><?php echo $name;?></option>
<?php }?>
</div>
