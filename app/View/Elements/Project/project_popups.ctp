<div id="SubprojectPopup" class="modal fade" tabindex="-1" role="dialog">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<h4><?=__('New subproject')?></h4>
				<?=$this->Form->create('Subproject', array('url' => array('controller' => 'Project', 'action' => 'addSubproject')))?>
					
					<?=$this->Form->hidden('project_id', array('value' => $projectID))?>
					
					<div class="form-group">
						<label><?=__('Title')?></label>
						<?=$this->Form->input('title', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Title'), 'required' => 'required'))?>
					</div>
					<div class="clearfix">
						<button type="submit" class="btn btn-default disabled"><?=__('Add subproject')?></button>
					</div>
				<?=$this->Form->end()?>
			</div>
		</div>
	</div>
</div>
	
<div id="TaskPopup" class="modal fade" tabindex="-1" role="dialog">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<h4><?=__('New task')?></h4>
				<?=$this->Form->create('Task', array('url' => array('controller' => 'Project', 'action' => 'addTask', $projectID)))?>
					<?=$this->Form->hidden('creator_id', array('value' => $currUserID))?>
					<?=$this->Form->hidden('subproject_id')?>
					<div class="form-group">
						<?=$this->Form->input('title', array('label' => __('Title'), 'placeholder' => __('Title').'...', 'class' => 'form-control'));?>
					</div>
					<div class="form-group">
						<?=$this->Form->input('descr', array('label' => __('Description'), 'placeholder' => __('Description').'...', 'class' => 'form-control', 'type' => 'text'))?>
					</div>
					<div class="form-group">
						<label><?=__('Deadline')?></label>
						
						<div class="input-group">
							<div class="input-group-addon glyphicons calendar"></div>
<?
		$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
?>
							<?=$this->Form->input('Task.js_deadline', array('type' => 'text', 'label' => false, 'class' => 'form-control datetimepicker', 'data-date-format' => $dateFormat))?>
							<!--span id=""></span>
							<span id="TaskDeadline" class="form-control"></span-->
							<?=$this->Form->hidden('Task.deadline')?>	
						</div>
					</div>
					<div class="form-group">
						<label><?=__('Manager')?></label>
						<?=$this->Form->input('manager_id', array('options' => $aProjectMemberOptions, 'class' => 'formstyler', 'label' => false))?>
					</div>
					<div class="form-group">
						<label><?=__('Assigned to')?></label>
						<?=$this->Form->input('user_id', array('options' => $aProjectMemberOptions, 'class' => 'formstyler', 'label' => false))?>
					</div>
					<div class="clearfix">
						<button type="submit" class="btn btn-default disabled"><?=__('Add task')?></button>
					</div>
				<?=$this->Form->end()?>
			</div>
		</div>
	</div>
</div>
	
<div id="ProjectMemberPopup" class="modal fade" tabindex="-1" role="dialog">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<h4><?=__('Add member')?></h4>
					<?=$this->Form->create('ProjectMember', array('url' => array('controller' => 'Project', 'action' => 'addMember', $projectID)))?>
					<?=$this->Form->hidden('project_id', array('value' => $projectID))?>					
					<div class="form-group">
						<label><?=__('Add member')?></label>
						<?=$this->Form->input('user_id', array('options' => $addMemberOptions, 'class' => 'formstyler', 'label' => false, 'empty' => __('-- Select list --'), 'required' => 'required'))?>
					</div>
				
					<div class="clearfix">
						<button type="submit" class="btn btn-default disabled"><?=__('Add member')?></button>
					</div>
				
				<?=$this->Form->end()?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
/*	
	$('#TaskPopup').on('shown.bs.modal', function (e) {
		$(document).on('touchmove', 'body', function(event) {
			event.preventDefault();
		});
	})

	$('#TaskPopup').on('hide.bs.modal', function (e) {
		$(document).off('touchmove');
	});
*/	
	function addNewTask(e, subprojectID) {
		$('#TaskPopup').hide();
		$('#TaskSubprojectId').val(subprojectID);
		$('#TaskPopup').modal({backdrop:false});
		$('#TaskPopup').modal();
	}

	function addProjectMember(e) {
		$('#ProjectMemberPopup').hide();
		$('#ProjectMemberPopup').modal({backdrop:false});
		$('#ProjectMemberPopup').modal();
	}

	$(document).ready(function(){
		$('.new-under-project-btn').on('click', function(){
			$('.popup-block').hide();
			$('.drop-add-sub-project').show().css({'top': $('.new-under-project-btn').offset().top});
		});
		$('.popup-block .close-block, .popup-block .close-block').on('click', function(){
			$('.popup-block').hide();
		});

<?
	$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'DD.MM.YYYY' : 'MM/DD/YYYY';
?>
<?php 

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
	
		$('#TaskJsDeadline').change(function(){
			$('#TaskDeadline').val(Date.local2sql($(this).val()));
		});

		$('#TaskJsDeadline').on('keydown cut', function (event) {
			event.preventDefault();
			event.stopPropagation();
			return false;
		});
		
		$('#TaskPopup, #ProjectMemberPopup, #SubprojectPopup').on('shown.bs.modal', function (e) {
			$('body').css("position","fixed");
		})
		
		$('#TaskPopup, #ProjectMemberPopup, #SubprojectPopup').on('hidden.bs.modal', function (e) {
			$('body').css("position","static");
		});
		
		$('#SubprojectPopup .form-control').bind("keyup change", function() {
			if($(this).val().length > 3) {
				$('#SubprojectPopup button').removeClass('disabled');
			} else {
				$('#SubprojectPopup button').removeClass('disabled').addClass('disabled');
			}
		});
  
		$('#ProjectMemberPopup #ProjectMemberUserId').change( function() {
			if($('#ProjectMemberUserId').val()) {
				$('#ProjectMemberPopup button').removeClass('disabled');
			} else {
				$('#ProjectMemberPopup button').removeClass('disabled').addClass('disabled');
			}
		});

		$('#TaskPopup .form-control').bind("keyup change", function() {
			if( ($('#TaskTitle').val().length > 0) ) {
				$('#TaskPopup button').removeClass('disabled');
			} else {
				$('#TaskPopup button').removeClass('disabled').addClass('disabled');
			}
		});
	});

</script>