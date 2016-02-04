<?
	$this->Html->css('jquery.Jcrop.min', null, array('inline' => false));
	$viewScripts = array(
		'vendor/jquery/jquery.Jcrop.min',
		'vendor/jquery/jquery.ui.widget',
		'vendor/jquery/jquery.iframe-transport',
		'vendor/jquery/jquery.iframe-transport',
		'vendor/jquery/jquery.fileupload',
		'vendor/exif',
		'/table/js/format',
		'upload'
	);
	$this->Html->script($viewScripts, array('inline' => false));

	$id = $this->request->data('Group.id');

	$pageTitle = ($id) ? __('Group settings') : __('Create group');

	/* Breadcrumbs */
	$this->Html->addCrumb($pageTitle, array('controller' => 'Group', 'action' => 'edit'));
?>

<style type="text/css">

	.error-message {
		font-weight: 900;
		color: red;
	}

	.jq-file__browse, .jq-file {
		width: auto!important;
		float: left!important;
	}

</style>

<h1><?=$pageTitle?></h1>

<?=$this->Form->create(array('id'=>'Group','onsubmit'=>'return check_form();'))?>
<?=$this->Form->hidden('Group.id')?>
<?=$this->Form->hidden('Group.finance_project_id')?>


	<div class="row baseInfoRow ">

		<div class="col-sm-3 leftFormBlock">
<?
	if ($id) {
?>
			<div class="avatar-img-Group">
				<?php echo $this->Avatar->group($this->request->data, array(
					'id' => 'Group'.$id,
					'class' => 'img-responsive',
					'data-resize' => 'thumb200x200',
					'data-id' => $this->request->data('Media.id'),
					'size' => 'thumb200x200'
				)); ?>
			</div>

			<div class="inputFile">
				<input id="userAvatarChoose" class="filestyle fileuploader" type="file" data-object_type="Group" data-object_id="<?=$id?>" data-progress_id="progress-Group<?=$id?>" accept="image/*" />
				<input id="userAvatarUpload" class="btn btn-primary save-button upload" type="button" value="<?=__('Save and upload')?>" style="display: none;" />
			</div>

			<div class="progress" id="progress-Group<?=$id?>">
				<div class="progress-bar progress-bar-info" role="progressbar">
					<span id="progress-stats"></span>
				</div>
			</div>
<?
	}
?>
		</div>

		<div class="col-sm-9 rightFormBlock">

<?
	if (isset($this->request->query['success']) && $this->request->query['success']) {
?>
			<div align="center">
				<label>
					<?=__('Group has been successfully saved')?>
				</label>
			</div>
<?
	}
?>

			<div class="form-group">
				<label for="group-create-2"><?=__('Group title')?></label>
				<?=$this->Form->input('Group.title', array('label' => false, 'placeholder' => __('Group title').'...', 'class' => 'form-control'))?>
			</div>

			<div class="form-group">
				<label><?=__('Group url')?></label>
				<?=$this->Form->input('Group.group_url', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'groupUrl', 'placeholder' => $id, 'maxlength' => 32))?>
			</div>

			<div class="form-group">
				<label for="group-create-1"><?=__('Group video')?></label>
				<div class="input-group">
					<div class="input-group-addon glyphicons facetime_video"></div>
					<?=$this->Form->input('Group.video_url', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => 'http://youtube.com...'))?>
				</div>
			</div>

			<div class="form-group">
				<label for="group-create-3"><?=__('Description')?></label>
				<?=$this->Form->input('Group.descr', array('type' => 'textarea', 'label' => false, 'class' => 'form-control', 'placeholder' => __('Description')))?>
			</div>

			<div class="form-group noBorder">
				<label><?=__('Category')?></label>
				<div class="input-group">
						<?=$this->Form->input('Group.cat_id', array('id'=> 'buy','options' => $aGroupCategories, 'label' => false, 'empty' => __('Select category'), 'class' => 'formstyler countrySelect' ))?>
				</div>
			</div>

            <div class="form-group noBorder">
                <label><?=__('Responsible')?></label>
                <div class="input-group">
                    <?=$this->Form->input('Group.responsible_id', array('id'=> 'group-responsible','options' => $aMembers, 'label' => false, 'empty' => __('Select Group Responsible'), 'data-placeholder' => __('Select Group Responsible'), 'class' => 'formstyler responsibleSelect' ))?>
                </div>
            </div>

			<script>
			function check_form(){
				if(!$("#buy").val()){
					alert('<?=__('Select category')?>');
					return false;
				}
			}
			</script>

			<div class="form-group">
				<label for="group-create-3"><?=__('Your role in group')?></label>
				<?php if(isset($this->request->data['GroupAdministrator']['id'])):?>
					<?=$this->Form->hidden('GroupAdministrator.id')?>
				<?php endif;?>
				<?=$this->Form->input('GroupAdministrator.role', array('type' => 'text', 'label' => false, 'placeholder' => __('Your role in group').'...', 'class' => 'form-control'))?>
			</div>

			<div class="form-group noBorder bigCheckBox">
				<?=$this->Form->input('Group.hidden', array('label' => false, 'type' => 'checkbox', 'class' => 'checkboxStyle glyphicons ok_2', 'div' => false))?>
				<span class="checkboxText"><?=__('Hide group')?></span>
			</div>

<?
	if($id) {
?>
			<div class="form-group noBorder bigCheckBox">
				<?=$this->Form->input('Group.hide_finance', array('label' => false, 'type' => 'checkbox', 'class' => 'checkboxStyle glyphicons ok_2', 'div' => false))?>
				<span class="checkboxText"><?=__('Use finance project')?></span>
			</div>
<?
	} else {
?>
			<?=$this->Form->hidden('Group.hide_finance')?>
<?
	}
?>

<?
	if ($id) {
?>
			<div class="form-group noBorder">
				<label><?=__('Photo or video gallery')?></label>
				<div class="avatar-img-GroupGallery"></div>
				<ul class="photoCollection clearfix">
				</ul>

				<div class="clearfix" style="height: 31px; width: 100%; margin-top: 20px;">
					<div class="photoButtons" style="max-width: 9999px;">
						<div class="inputFile" style="display: inline-block; float: left;">
							<input type="file" id="groupGalleryChoose" class="filestyle fileuploader" data-object_type="GroupGallery" data-object_id="<?=$id?>" data-progress_id="progress-GroupGallery_<?=$id?>" accept="image/*">
							<button id="groupGalleryUpload" type="button" class="btn btn-primary save-button upload" style="display: none; float: left; margin-left: 12px;"><?=__('Save and upload')?></button>
						</div>
						<button class="btn btn-default" type="button" data-toggle="modal" data-target=".newVideoPopup" style="float: left; margin-left: 12px; display: inline-block;"><?=__('Upload video')?></button>
					</div>
				</div>
			</div>
			<div class="progress" id="progress-GroupGallery_<?=$id?>">
				<div class="progress-bar progress-bar-info" role="progressbar">
					<span id="progress-stats"></span>
				</div>
			</div>
<?
	}
?>
		</div>
	</div>

	<div class="row addressRow">

		<div class="col-sm-3 leftFormBlock addNewField">
			<a href="javascript:void(0)" class="addNewInfo" id="addNewAddress">
				<span class="glyphicons circle_plus"></span>
				<span class="title"><?=__('Add group address')?></span>
			</a>
		</div>

		<div class="col-sm-9 rightFormBlock">

<?
	$aGroupAddress = $this->request->data('GroupAddress');
	if ($aGroupAddress) {
		foreach($aGroupAddress as $i => $groupAddress) {
			if(!array_key_exists($groupAddress['country'],$aMainCountries)){
				$aMainCountries[$groupAddress['country']] = $aAllCountries[$groupAddress['country']];
			}
		}
	}
	asort($aMainCountries);
	$aMainCountries['more'] = "-- [".__('Show More')."] --";


	if (!$aGroupAddress) {
?>
			<div class="form-group no-items noBorder">
				<?=__('No addresses yet')?>
			</div>
<?
	} else {

		foreach($aGroupAddress as $i => $groupAddress) {
?>
			<div id="addrBlock_<?=$i?>" class="group-fieldset addressBlock">
				<input type="hidden" name="data[GroupAddress][<?=$i?>][id]" value="<?=Hash::get($groupAddress, 'id')?>">
				<input type="hidden" name="data[GroupAddress][<?=$i?>][group_id]" value="<?=$id?>">

				<div class="form-group">
						<?=$this->Form->input('GroupAddress.'.$i.'.head_office', array('label' => __('Head Office'),'type'=>'checkbox'))?>
				</div>

				<div class="form-group noBorder">
					<label><?=__('Country')?></label>
					<div class="input-group input-large">
						<span class="input-group-addon glyphicon-extended glyphicons direction"></span>
						<?=$this->Form->input('GroupAddress.'.$i.'.country', array('options' => $aMainCountries, 'label' => false, 'class' => 'formstyler countrySelect'))?>
					</div>
				</div>

                <div class="form-group">
					<label for="group-create-8"><?=__('Zip Code')?></label>
					<div class="input-group">
						<div class="input-group-addon forTextarea glyphicons direction"></div>
						<input type="text" class="form-control" id="group-create-8" name="data[GroupAddress][<?=$i?>][zip_code]" placeholder="<?=__('Zip Code')?>..." value="<?=Hash::get($groupAddress, 'zip_code')?>" />
					</div>
				</div>

				<div class="form-group">
					<label for="group-create-4"><?=__('Address')?></label>
					<div class="input-group">
						<div class="input-group-addon forTextarea glyphicons direction"></div>
						<textarea class="form-control" id="group-create-4" name="data[GroupAddress][<?=$i?>][address]" placeholder="<?=__('Address')?>..."><?=Hash::get($groupAddress, 'address')?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="group-create-5"><?=__('Phone')?></label>
					<div class="input-group">
						<div class="input-group-addon glyphicons earphone"></div>
						<input class="form-control" id="group-create-5" type="text" name="data[GroupAddress][<?=$i?>][phone]" value="<?=Hash::get($groupAddress, 'phone')?>" placeholder="<?=__('Phone')?>..." />
					</div>
				</div>
				<div class="form-group">
					<label for="group-create-6"><?=__('Fax')?></label>
					<div class="input-group">
						<div class="input-group-addon glyphicons earphone"></div>
						<input class="form-control" id="group-create-6" type="text" name="data[GroupAddress][<?=$i?>][fax]" value="<?=Hash::get($groupAddress, 'fax')?>" placeholder="<?=__('Fax')?>..." />
					</div>
				</div>
				<div class="form-group">
					<label for="group-create-7"><?=__('Site URL and email')?></label>
					<div class="input-group">
						<div class="input-group-addon glyphicons globe_af"></div>
						<input class="form-control" id="group-create-7" type="text" name="data[GroupAddress][<?=$i?>][url]" value="<?=Hash::get($groupAddress, 'url')?>" placeholder="<?=__('http://yoursite.com')?>..." />
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon glyphicons message_empty"></div>
						<input class="form-control" id="group-create-8" type="text" name="data[GroupAddress][<?=$i?>][email]" value="<?=Hash::get($groupAddress, 'email')?>" placeholder="<?=__('Email')?>..."/>
					</div>
				</div>
				<div class="clearfix" style="margin-top: -10px; margin-bottom: 10px">
					<button type="button" class="btn btn-default pull-right" onclick="$('#addrBlock_<?=$i?>').remove()"><?=__('Delete')?></button>
				</div>
			</div>
<?
		}
	}
?>

		</div>

	</div>

	<div class="row achieveRow">
		<div class="col-sm-3 leftFormBlock addNewField">
			<a href="javascript:void(0)" class="addNewInfo" id="addNewAchieve">
				<span class="glyphicons circle_plus"></span>
				<span class="title"><?=__('Add group achievement')?></span>
			</a>
		</div>
		<div class="col-sm-9 rightFormBlock">
<?
	$aAchiev = $this->request->data('GroupAchievement');
	if (!$aAchiev) {
?>
			<div class="form-group no-items noBorder">
				<?=__('No achivements yet')?>
			</div>
<?
	} else {
		foreach($aAchiev as $i => $row) {
?>
			<div id="achieveBlock_<?=$i?>" class="group-fieldset achieveBlock">
				<input type="hidden" name="data[GroupAchievement][<?=$i?>][id]" value="<?=Hash::get($row, 'id')?>">
				<input type="hidden" name="data[GroupAchievement][<?=$i?>][group_id]" value="<?=$id?>">
				<div class="form-group">
					<label for="achiv-title<?=$i?>"><?=__('Achievement')?></label>
					<textarea class="form-control" id="achiv-title<?=$i?>" name="data[GroupAchievement][<?=$i?>][title]" placeholder="<?=__('Achievement')?>..."><?=Hash::get($row, 'title')?></textarea>
				</div>

				<div class="form-group">
					<label for="achiv-url<?=$i?>"><?=__('Link to verified accomplishments')?></label>
					<input class="form-control" id="achiv-url<?=$i?>" type="text" name="data[GroupAchievement][<?=$i?>][url]" value="<?=Hash::get($row, 'url')?>" placeholder="http://yoursite.com..."/>
				</div>
				<div class="clearfix" style="margin-top: -10px; margin-bottom: 10px">
					<button type="button" class="btn btn-default pull-right" onclick="$('#achieveBlock_<?=$i?>').remove()"><?=__('Delete')?></button>
				</div>
			</div>
<?
		}
	}
?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3 leftFormBlock"></div>
		<div class="col-sm-9 rightFormBlock">
			<button type="submit" class="btn btn-primary" type="button"><?=__('Save')?></button>
<?
	if ($id) {
?>
			<!--a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'delete', $id))?>" class="btn btn-default delete-group"><?=__('Delete')?></a-->
<?
	}
?>
		</div>
	</div>

<?=$this->Form->end()?>
<br /><br /><br />

<!----------------------------------- POPUP POPUP POPUP POPUP POPUP ------------------------------------------------------------------>

<div class="modal fade newTaskPopup newVideoPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<div class="form-group">
					<label for="add-new-video"><?=__('Youtube.com URL')?></label>
					<input id="add-new-video" class="form-control" type="text"/>
				</div>
				<div class="clearfix">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="Group.addGalleryVideo(<?=$id?>)"><?=__('Add')?></button>
				</div>
			</div>
		</div>
	</div>
</div>

<!----------------------------------- POPUP POPUP POPUP POPUP POPUP ------------------------------------------------------------------>

<script type="text/javascript">

$(document).ready(function(){

	$('input.checkboxStyle').styler();

	$('.countrySelect').bind('change',function(){
			if($(this).find(":selected").val()=='more'){
				$(this).find("option").remove();
				$(this).append($('.allCountries').html());
				$(this).trigger('refresh');
			}
	});

	$('.progress').css('height', '0');
<?
	if ($id) {
?>
	Group.updateGalleryAdmin(<?=$id?>);
<?
	}
?>
	$('#addNewAddress').click(function(){
		$('.addressRow .no-items').remove();
		var number = $('.addressRow .rightFormBlock .addressBlock').length;
		$('.addressRow .rightFormBlock').prepend(
			tmpl('group-address', {i: number})
		);
		selector = '.countrySelect[name="data[GroupAddress]['+number+'][country]"]';
		$(selector).bind('change',function(){
			if($(this).find(":selected").val()=='more'){
				$(this).find("option").remove();
				$(this).append($('.allCountries').html());
			}
		});
	});

	$('#addNewAchieve').click(function(){
		$('.achieveRow .no-items').remove();
		$('.achieveRow .rightFormBlock').prepend(
			tmpl('group-achiev', {i: $('.achieveRow .rightFormBlock .achieveBlock').length})
		);
	});

	$('#userAvatarChoose, #groupGalleryChoose').styler({
		fileBrowse: '<?=__('Choose image')?>'
	});

	$('#userAvatarUpload, #groupGalleryUpload').click(function(){
		$(this).data().submit();
	});

	$( "#GroupEditForm" ).submit(function( event ) {

		var id = "<?=$id?>";
		if( !id ) {
			if(confirm( "<?=__('Do you want to add project to finance manager?')?>" )){
				$('#GroupHideFinance').val(0);
			} else {
				$('#GroupHideFinance').val(1);
			}
		}

		if($('input[name*="head_office"]:checked').length==false){
			$('input[name*="[0][head_office]"]').click();
		}
		if($('input[name*="head_office"]:checked').length > 1){
			$('input[name*="[head_office]"]:checked').click();
			$('input[name*="[0][head_office]"]').click();
		}
		return true;
	});

	$('#GroupEditForm').submit(function() {
		var valid = true;

		//Валидация ссылки на youtube
		if(!( $('#GroupVideoUrl').val().length === 0 ) && ( !IsYoutubeUrl($('#GroupVideoUrl').val()) )) {
			if( (/webOS|Android|iPad|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ) {
				$('#GroupVideoUrl').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Invalid video url. Leave blank or insert valid youtube url')?>" });
			} else {
				$('#GroupVideoUrl').popover({ toggle: 'popover', placement: 'right', content: "<?=__('Invalid video url. Leave blank or insert valid youtube url')?>" });
			}
			$('html, body').animate({
				scrollTop: $("#GroupVideoUrl").offset().top - 100
			}, 1000, function() {
				$('#GroupVideoUrl').popover('show');
			});
			valid = false;
		}

		//Валидация ссылки - проверка на содержание чаров, не только чисел, а так же длины
		if(! TestString($('#groupUrl').val(), 3) && $('#groupUrl').val().length != 0 ) {
			if( (/webOS|Android|iPad|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ) {
				$('#groupUrl').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Atleast 1 letter and 3 chars length required')?>" });
			} else {
				$('#groupUrl').popover({ toggle: 'popover', placement: 'right', content: "<?=__('Atleast 1 letter and 3 chars length required')?>" });
			}
			$('html, body').animate({
				scrollTop: $("#groupUrl").offset().top - 100
			}, 400, function() {
				//$('#UserVideoUrl').val('');
				$('#groupUrl').popover('show');
			});
			valid = false;
		}

		if(!valid) {
			return false;
		}
	});

	$('#GroupVideoUrl, #groupUrl').click(function(){
		$(this).popover('destroy');
	});

	function IsYoutubeUrl( url ) {
		var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
		/*			|	  protocol    |	subdomain |		  domain name		|						URI with video						 |*/
		return regex.test(url);
	}

	$('#groupUrl').keyup(function(event){
		if($('#groupUrl').val().length == 1 && /[0-9]/.test($('#groupUrl').val())) {
			$('#groupUrl').val('');

			$('#groupUrl').popover('destroy');
			if( (/webOS|Android|iPad|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ) {
				$('#groupUrl').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Digit can not be the first character')?>" });
			} else {
				$('#groupUrl').popover({ toggle: 'popover', placement: 'right', content: "<?=__('Digit can not be the first character')?>" });
			}
			$('#groupUrl').popover('show');

			setTimeout(function () {
				$('#groupUrl').popover('destroy');
			}, 3000);

			return false;
		}
		return true;
	});

	function TestString(str, sLen) {
		return ( (str.length >= sLen) && (/[a-zA-Z]/.test(str) || /[а-яА-Я]/.test(str)) )
	}
});

var my_options = $("#GroupAddress0Country option");
var selected = $("#GroupAddress0Country").val(); /* preserving original selection, step 1 */
my_options.sort(function(a,b) {
	if (a.text > b.text) return 1;
	else if (a.text < b.text) return -1;
	else return 0
})
$("#GroupAddress0Country").empty().append( my_options );
$("#GroupAddress0Country").val(selected); /* preserving original selection, step 2 */

$('#GroupDescr').autosize({append:false});
$('#GroupDescr').on('keyup copy cut paste change', function() {
	$('#GroupDescr').trigger('autosize.resize');
});
$('#GroupDescr').trigger('autosize.resize');

</script>

<script type="text/x-tmpl" id="group-address">
<div class="group-fieldset addressBlock">
	<input type="hidden" name="data[GroupAddress][{%=o.i%}][id]" value="">
	<input type="hidden" name="data[GroupAddress][{%=o.i%}][group_id]" value="<?=$id?>">

		<div class="form-group">
			<div class="input checkbox">
				<input type="checkbox" id="group-create-9" name="data[GroupAddress][{%=o.i%}][head_office]" value="1" />
				<label><?= __('Head Office')?></label>
			</div>
		</div>

        <div class="form-group noBorder">
			<label><?=__('Country')?></label>
			<div class="input-group input-large">
				<span class="input-group-addon glyphicon-extended glyphicons direction"></span>
                                <select name="data[GroupAddress][{%=o.i%}][country]" class="formStyler countrySelect">
                                    <?php foreach($aMainCountries as $code=>$name): ?>
                                        <option value="<?php echo $code?>"><?php echo $name;?></option>
									 <?php endforeach;?>
                                </select>
			</div>
        </div>

        <div class="form-group">
		<label for="group-create-8"><?=__('Zip Code')?></label>
		<div class="input-group">
			<div class="input-group-addon forTextarea glyphicons direction"></div>
			<input type="text" class="form-control" id="group-create-8" name="data[GroupAddress][{%=o.i%}][zip_code]" value="" placeholder="<?=__('Zip Code')?>..." />
		</div>
	</div>

        <div class="form-group">
		<label for="group-create-4"><?=__('Address')?></label>
		<div class="input-group">
			<div class="input-group-addon forTextarea glyphicons direction"></div>
			<textarea class="form-control" id="group-create-4" name="data[GroupAddress][{%=o.i%}][address]" placeholder="<?=__('Address')?>..."></textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="group-create-5"><?=__('Phone')?></label>
		<div class="input-group">
			<div class="input-group-addon glyphicons earphone"></div>
			<input class="form-control" id="group-create-5" type="text" name="data[GroupAddress][{%=o.i%}][phone]" value="" placeholder="<?=__('Phone')?>..." />
		</div>
	</div>
	<div class="form-group">
		<label for="group-create-6"><?=__('Fax')?></label>
		<div class="input-group">
			<div class="input-group-addon glyphicons earphone"></div>
			<input class="form-control" id="group-create-6" type="text" name="data[GroupAddress][{%=o.i%}][fax]" value="" placeholder="<?=__('Fax')?>..." />
		</div>
	</div>
	<div class="form-group">
		<label for="group-create-7"><?=__('Site URL and email')?></label>
		<div class="input-group">
			<div class="input-group-addon glyphicons globe_af"></div>
			<input class="form-control" id="group-create-7" type="text" name="data[GroupAddress][{%=o.i%}][url]" value="" placeholder="<?=__('http://yoursite.com')?>..." />
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<div class="input-group-addon glyphicons message_empty"></div>
			<input class="form-control" id="group-create-8" type="text" name="data[GroupAddress][{%=o.i%}][email]" value="" placeholder="<?=__('Email')?>..."/>
		</div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="group-achiev">
<div class="group-fieldset achieveBlock">
	<input type="hidden" name="data[GroupAchievement][{%=o.i%}][id]" value="">
	<input type="hidden" name="data[GroupAchievement][{%=o.i%}][group_id]" value="<?=$id?>">
	<div class="form-group">
		<label for="achiv-title{%=o.i%}"><?=__('Achievement')?></label>
		<textarea class="form-control" id="achiv-title{%=o.i%}" name="data[GroupAchievement][{%=o.i%}][title]" placeholder="<?=__('Achievement')?>..."></textarea>
	</div>

	<div class="form-group">
		<label for="achiv-url{%=o.i%}"><?=__('Link to verified accomplishments')?></label>
		<input class="form-control" id="achiv-url{%=o.i%}" type="text" name="data[GroupAchievement][{%=o.i%}][url]" value="" placeholder="http://yoursite.com..."/>
	</div>

</div>
</script>

<script type="text/x-tmpl" id="group-gallery-image-admin">
{%
	for(var i = 0; i < o.length; i++) {
		var img = o[i].Media;
%}
	<li>
		<a class="remove" href="javascript: void(0)" onclick="Group.delGalleryImage({%=img.object_id%}, {%=img.id%})"><span class="glyphicons circle_remove"></span></a>
		<img src="{%=img.url_img.replace(/noresize/, 'thumb120x90')%}" alt="" />
	</li>
{%
	}
%}
</script>

<script type="text/x-tmpl" id="group-gallery-video-admin">
{%
	for(var i = 0; i < o.length; i++) {
		var video = o[i].GroupVideo;
%}
	<li>
		<a class="remove" href="javascript: void(0)" onclick="Group.delGalleryVideo({%=video.group_id%}, {%=video.id%})"><span class="glyphicons circle_remove"></span></a>
		<span class="glyphicons play"></span>
		<img src="http://img.youtube.com/vi/{%=video.video_id%}/1.jpg" alt="" />
	</li>
{%
	}
%}
</script>
<div class="allCountries" style="display:none">
<?php
	asort($aAllCountries);
	foreach($aAllCountries as $code=>$name){ ?>
		<option value="<?php echo $code?>"><?php echo $name;?></option>
<?php }?>
</div>
