<style type="text/css">
  .usersScroll .item.selected { background-color: #e5e6e8 }

  /*div.btn span{ display: none } */
  div.btn.loadBtn span{ display: inline }

  div.btn img{ display: inline }
  div.btn.loadBtn img{ display: none }
</style>

<div id="SubprojectPopup" class="modal fade" tabindex="-1" role="dialog">
  <div class="outer-modal-dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <span class="glyphicons circle_remove" data-dismiss="modal"></span>
        <h4><?=__('New subproject')?></h4>
        <?=$this->Form->create('Subproject', array('url' => array('controller' => 'Project', 'action' => 'addCrmSubproject')))?>

          <?=$this->Form->hidden('project_id', array('value' => $projectID))?>
          <?=$this->Form->hidden('id')?>

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
        <?=$this->Form->create('Task', array('url' => array('controller' => 'Project', 'action' => 'addCrmTask', $projectID)))?>
          <?=$this->Form->hidden('creator_id', array('value' => $currUserID))?>
          <?=$this->Form->hidden('subproject_id')?>
          <?=$this->Form->hidden('id')?>
          <div class="form-group">
            <?=$this->Form->input('Task.title', array('label' => __('Title'), 'placeholder' => __('Title').'...', 'class' => 'form-control'));?>
          </div>
          <div class="form-group">
            <?=$this->Form->input('Task.descr', array('label' => __('Description'), 'placeholder' => __('Description').'...', 'class' => 'form-control', 'type' => 'text'))?>
          </div>
          <div class="form-group">
            <label><?=__('Deadline')?></label>

            <div class="input-group">
              <div class="input-group-addon glyphicons calendar"></div>
<?
    $dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
?>
              <?=$this->Form->input('Task.js_deadline', array('type' => 'text', 'label' => false, 'class' => 'form-control datetimepicker', 'data-date-format' => $dateFormat))?>
              <?=$this->Form->hidden('Task.deadline')?>
            </div>
          </div>
          <?=$this->Form->hidden('Task.crm', array('value' => 1))?>
          <div class="form-group">
            <label><?=__('Responsible')?></label>
            <?=$this->Form->input('Task.manager_id', array('options' => $aProjectMemberOptions, 'class' => 'formstyler', 'label' => false))?>
          </div>
          <div class="form-group">
            <label><?=__('Performer')?></label>
            <?=$this->Form->input('Task.user_id', array('options' => $aProjectMemberOptions, 'class' => 'formstyler', 'label' => false))?>
          </div>

          <div class="form-group summ">
            <label><?=__('Amount planned')?></label>
            <?=$this->Form->input('CrmTask.money', array('label' => false, 'div' => false, 'value' => '0.00', 'class' => 'form-control', 'type' => 'number', 'step' => '0.01'))?>
            <select name="data[CrmTask][currency]" class="currency formstyler" id="finance-chart-balance-currency">
              <? foreach ($this->Money->symbols() as $code => $symbol) { ?>
                <option value="<?=$code?>"><?=$symbol?></option>
              <? } ?>
            </select>
          </div>

          <div class="clearfix">
            <div class="btn btn-default disabled" onClick="$('#TaskViewForm').submit();"><?=__('Add')?></div>
          </div>
        <?=$this->Form->end()?>
      </div>
    </div>
  </div>
</div>

<!--div id="ProjectMemberPopup" class="modal fade" tabindex="-1" role="dialog">
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
</div-->

<div class="modal fade" id="ProjectMemberPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="outer-modal-dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <span class="glyphicons circle_remove" data-dismiss="modal"></span>

        <form>
          <h4><?=__('Add member')?></h4>
          <div class="usersScroll">
<?
  foreach( $addMemberOptions as $uid => $member ) {
?>
            <div class="item clearfix user" data-user_id="<?=$uid?>">
                <?php echo $this->Avatar->user($member, array(
                    'class' => 'ava',
                    'size' => 'thumb100x100'
                )); ?>
              <div class="info">
                <span class="name"><?=$member['User']['full_name']?></span>
                <span class="position"><?=$this->Avatar->skills($member['User']['skills'])?></span>
              </div>
            </div>
<?
  }
?>
          </div>
          <div class="clearfix">
            <div id="addMember" class="btn btn-primary loadBtn disabled" onclick="inviteUser()"><span><?=__('Add')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">



  $('#ProjectMemberPopup').on('shown.bs.modal', function (e) {
    $('.usersScroll').niceScroll({cursorwidth:"7px",cursorcolor:"#23b5ae", cursorborder:"none", autohidemode:"false", background: "#f1f1f1"})
    $('.usersScroll').getNiceScroll().show();
    $('body').css("position","fixed");
  })

  $('#ProjectMemberPopup').on('hide.bs.modal', function (e) {
    $('.usersScroll').getNiceScroll().hide();
    $('body').css("position","static");
  });
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

    $('#addMember.loadBtn').on('click', function (event) {
      ///////////////////////
      $(this).removeClass('loadBtn');
      var aUsers = $('.usersScroll .item.selected').map(function(){ return $(this).data('user_id'); }).get();

      $.post('/Project/addMember', {
        data: {
          project_id: '<?=$projectID?>',
          users: aUsers
        }
      }, function (response) {
        console.log(response);
        var obj = jQuery.parseJSON(response);
        if( obj !== null ) {
          if(obj.status == "ERROR") {
            $(this).removeClass('loadBtn').addClass('loadBtn');
            alert( obj.message );
          }
          if(obj.status == "OK") {
            location.reload();
          }
        }
      });

    });

    $('.usersScroll .item').on('click', function (event) {
      $(this).toggleClass('selected');

      if( $('.usersScroll .item.selected').length > 0 ) $('#addMember').removeClass('disabled');
      else $('#addMember').removeClass('disabled').addClass('disabled');
    });

    $('.editSubproject').on('click', function (event) {
      $('#SubprojectPopup #SubprojectId').val($(this).data('id'));
      $('#SubprojectPopup #SubprojectTitle').val($(this).data('title'));
      $('#SubprojectPopup').modal('show');
    });

    $('#addSubproject').on('click', function (event) {
      $('#SubprojectPopup #SubprojectId').val('');
      $('#SubprojectPopup #SubprojectTitle').val('');
    });

    $('.editTask').on('click', function (event) {
      $('#TaskPopup #TaskId').val($(this).data('id'));
      $('#TaskTitle').val($(this).data('title'));
      $('#TaskDescr').val($(this).data('descr'));
      $('#TaskManagerId').val($(this).data('manager'));
      $('#TaskManagerId').change();
      $('#TaskUserId').val($(this).data('user'));
      $('#TaskUserId').change();
      $('#TaskDeadline').val($(this).data('deadline'));
      $('#TaskJsDeadline').val($(this).data('js-deadline'));

      $('#CrmTaskMoney').parents('.form-group').hide();

      $('#TaskPopup').modal('show');
      checkTaskForm();
    });

    $('.newTask').on('click', function (event) {
      $('#TaskPopup #TaskId').val('');

      $('#CrmTaskMoney').parents('.form-group').show();

      $('#TaskTitle').val('');
      $('#TaskDescr').val('');
      $('#TaskManagerId').val('<?=Hash::get($project, 'Project.owner_id')?>');
      $('#TaskManagerId').change();
      $('#TaskUserId').val('<?=Hash::get($project, 'Project.owner_id')?>');
      $('#TaskUserId').change();
      $('#TaskDeadline').val($(this).data(''));
      $('#TaskJsDeadline').val($(this).data(''));
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

    $('#TaskPopup .form-control').on("keyup change", function() {
      checkTaskForm();
    });

    $('#TaskPopup select').on("change", function() {
      checkTaskForm();
    });
  });

  checkTaskForm = function() {
    if( ($('#TaskTitle').val().length > 0) ) {
      $('#TaskPopup .btn-default').removeClass('disabled');
    } else {
      $('#TaskPopup .btn-default').removeClass('disabled').addClass('disabled');
    }
    console.log( $('#TaskPopup .btn-default').hasClass('disabled') );
    console.log( $('#TaskTitle').val().length );
  }

</script>
