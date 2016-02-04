<?php
    $viewScripts = array(
        'vendor/bootstrap-datetimepicker.min',
        'vendor/bootstrap-datetimepicker.ru',
    );
    $this->Html->script($viewScripts, array('inline' => false));

    $projectID = Hash::get($project, 'Project.id');
    $tasks = Hash::combine($aTasks, '{n}.Task.id', '{n}');
    $aTasks = Hash::combine($aTasks, '{n}.Task.id', '{n}', '{n}.Task.subproject_id');

    $addMemberOptions = array_diff_key($aMemberOptions, $aProjectMembers);

	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($project, 'Project.group_id')));
	$this->Html->addCrumb(Hash::get($project, 'Project.title'), array('controller' => 'Project', 'action' => 'view/'.$projectID));
?>

<div class="row projectViewTitle fixedLayout">
    <div class="col-sm-4 col-sm-push-8 controlButtons">
		<?php if ($isProjectAdmin || $isGroupAdmin) : ?>
			<a onclick="return confirm('<?php echo __('Are you sure to close this project?'); ?>');" class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'close', $projectID))?>">
				<div class="glyphicons remove_2"></div>
				<div class="caption"><?=__('Close')?></div>
			</a>

			<a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'edit', $projectID))?>">
				<div class="glyphicons wrench"></div>
				<div class="caption"><?=__('Edit')?></div>
			</a>
		<?php endif; ?>
    </div>
</div>

<!--div class="row description-project-page clearfix">
    <div class="col-md-9 col-sm-9 col-xs-12 text-description">
        <p><?=Hash::get($project, 'Project.descr')?></p>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 deadline-date">
        <div class="date">
            <?=(Hash::get($project, 'Project.closed')) ? __('Closed') : ''?><br/>
            <span class="glyphicons anchor"></span>
            <?=__('Deadline')?><?=(Hash::get($project, 'Project.deadline')) ? ': '.Hash::get($project, 'Project.deadline') : ': - '?>
        </div>
    </div>
</div-->

<div class="row projectViewInfo fixedLayout">
    <div class="col-sm-8 description">
        <?=Hash::get($project, 'Project.descr')?>
    </div>
    <div class="col-sm-4 deadline">
<?
    $closed = Hash::get($project, 'Project.closed');
    $deadline = Hash::get($project, 'Project.deadline');
?>
        <?=($closed) ? __('Closed') : ''?><br/>
        <span class="glyphicons anchor"></span>
        <?=__('Deadline')?>: <?=$this->LocalDate->date($deadline)?>
    </div>
</div>

<h3><?=__('Team')?>
<?
    if ($addMemberOptions && ($isProjectAdmin || $isResponsible || $isGroupAdmin)) {
        asort($addMemberOptions);
?>
    <a class="btn btn-primary" href="javascript:void(0)" onclick="addProjectMember(this)">
        <?=__('Add member')?>
    </a>
<?
    }
?>
</h3>

<div class="groupCommand clearfix">
<?
        foreach($aProjectMembers as $member) {
            $user = $aUsers[$member['ProjectMember']['user_id']];
            $_member = $aMembers[$member['ProjectMember']['user_id']];
            $role = $_member['GroupMember']['role'];
?>
    <a href="<?=$this->html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="item">
        <?php echo $this->Avatar->user($user, array(
            'style' => 'width: 100px;',
            'size' => 'thumb200x200'
        )); ?>

        <div class="name"><?=$user['User']['full_name']?></div>
        <div class="position"><?=$role?></div>
    </a>
<?
        }
?>
</div>

<div class="projectLastUpdates">
    <div class="title"><span class="glyphicons clock"></span><?=__('Last updates')?></div>
<?
    foreach($aEvents as $event) {
        $user = $aUsers[$event['ProjectEvent']['user_id']];
        $userLink = $this->element('Project/user_link', compact('user'));
?>
    <div class="item clearfix">
        <span class="date"><?=$this->LocalDate->dateTime($event['ProjectEvent']['created'])?></span>
        <span class="info">
<?
        switch ($event['ProjectEvent']['event_type']) {
            case ProjectEvent::PROJECT_CREATED: echo __('%s created this project', $userLink); break;
            case ProjectEvent::SUBPROJECT_CREATED:
                $subproject = $subprojects[$event['ProjectEvent']['subproject_id']];
                echo __('%s created subproject "%s"', $userLink, $subproject['Subproject']['title']);
                break;
            case ProjectEvent::TASK_CREATED:
                $task = $tasks[$event['ProjectEvent']['task_id']];
                echo __('%s created task %s', $userLink, $this->Html->link($task['Task']['title'], array('action' => 'task', $task['Task']['id'])));
                break;
            case ProjectEvent::PROJECT_CLOSED: echo __('Project is closed'); break;
            case ProjectEvent::SUBPROJECT_CLOSED:
                $subproject = $subprojects[$event['ProjectEvent']['subproject_id']];
                echo __('%s closed subproject "%s"', $userLink, $subproject['Subproject']['title']);
                break;
            case ProjectEvent::TASK_CLOSED:
                $task = $tasks[$event['ProjectEvent']['task_id']];
                echo __('%s closed task %s', $userLink, $this->Html->link($task['Task']['title'], array('action' => 'task', $task['Task']['id'])));
                break;
            case ProjectEvent::SUBPROJECT_DELETED:
                $subproject = $subprojects[$event['ProjectEvent']['subproject_id']];
                echo __('%s removed subproject "%s"', $userLink, $subproject['Subproject']['title']);
                break;
            case ProjectEvent::TASK_DELETED:
                $task = $tasks[$event['ProjectEvent']['task_id']];
                echo __('%s removed task %s', $userLink, $this->Html->link($task['Task']['title'], array('action' => 'task', $task['Task']['id'])));
                break;
            case ProjectEvent::TASK_COMMENT:
                $task = $tasks[$event['ProjectEvent']['task_id']];
                $taskLink = $this->Html->link($task['Task']['title'], array('action' => 'task', $task['Task']['id'], '#' => 'post'.$event['ProjectEvent']['id']), array('class' => 'underlink'));
                echo __('%s commented task %s', $userLink, $taskLink);
                break;
            case ProjectEvent::FILE_ATTACHED:
                $task = $tasks[$event['ProjectEvent']['task_id']];
                $taskLink = $this->Html->link($task['Task']['title'], array('action' => 'task', $task['Task']['id'], '#' => 'post'.$event['ProjectEvent']['id']), array('class' => 'underlink'));
                $file = $files[$event['ProjectEvent']['file_id']];
                $fileLink = $this->Html->link($file['orig_fname'], $file['url_download'], array('class' => 'underlink'));
                echo __('%s attached %s to task %s', $userLink, $fileLink, $taskLink);
                break;
        }
?>
        </span>
    </div>
<?
    }
?>
</div>

<?
    if ($isProjectAdmin || $isResponsible || $isGroupAdmin) {
?>
    <button class="btn btn-default" id="addSubproject" type="button" data-toggle="modal" data-target="#SubprojectPopup" data-backdrop="false"><?=__('New subproject')?></button>
<?
    }
    foreach($subprojects as $subprojectID => $subproject) {
        // $subprojectID = Hash::get($subproject, 'Subproject.id')
        if(!$subproject['Subproject']['deleted']) {
?>


<h3 class="userList">
    <?=Hash::get($subproject, 'Subproject.title')?>
<?
    if ($isProjectAdmin || $isResponsible || $isGroupAdmin) {
?>
    <a class="btn btn-default smallBtn editSubproject" href="javascript:void(0)" data-id="<?=Hash::get($subproject, 'Subproject.id')?>" data-title="<?=Hash::get($subproject, 'Subproject.title')?>"><span class="glyphicons pencil"></span></a>
    <a class="btn btn-default smallBtn removeSubproject" href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'removeSubproject', Hash::get($subproject, 'Subproject.id')))?>"><span class="glyphicons bin"></span></a>
<?
    }
?>
</h3>
<div class="fixedLayout subProject">
    <div class="row">
        <div class="col-sm-3 hidden-xs addTd"><?=__('Title')?></div>
        <div class="col-sm-6 hidden-xs addTd"><?=__('Assigned to')?></div>
        <div class="col-sm-2 hidden-xs addTd"><span class="glyphicons anchor"></span><?=__('Deadline')?></div>
        <div class="col-sm-1 hidden-xs addTd"><?=__('Amount')?></div>
    </div>

<?
        $openTasks = 0;
        $closedTasks = 0;
        if (isset($aTasks[$subprojectID]) && ($tasks = $aTasks[$subprojectID])) {
            foreach($tasks as $taskID => $task) {
                if (!$task['Task']['closed'] && !$task['Task']['deleted']) {
                    $user = $aUsers[$task['Task']['user_id']];
                    $openTasks++;
                    echo $this->element('Project/crm_project_tasks', compact('task', 'user'));
                } else {
                    if (!$task['Task']['deleted']) {
                        $closedTasks++;
                    }
                }
            }
        }
        if (!$openTasks) {
?>
        <div class="row item"><div class="col-sm-6"><?=__('No open tasks')?></div></div>
<?
        }
        if ($isProjectAdmin || $isResponsible || $isGroupAdmin) {
?>
    <div class="newTask" onclick="addNewTask(this, <?=$subprojectID?>)">
        <a href="javascript:void(0)">
            <span class="btn btn-default smallBtn"><span class="glyphicons plus"></span></span>
            <?=__('New task')?>
        </a>
    </div>
<?
        }
        if ($closedTasks) {
?>
        <br /><br />
        <?=__('Closed tasks')?>
<?
            if (isset($aTasks[$subprojectID]) && ($tasks = $aTasks[$subprojectID])) {
                foreach($tasks as $taskID => $task) {
                    if ($task['Task']['closed'] && !$task['Task']['deleted']) {
                        $user = $aUsers[$task['Task']['user_id']];
                        echo $this->element('Project/crm_project_tasks', compact('task', 'user'));
                    }
                }
            }
        }
?>

</div>
<br /><br /><br />
<?
        }
    } // $aSubproject
    if ($isProjectAdmin || $isResponsible || $isGroupAdmin) {
        echo $this->element('Project/crm_project_popups', compact('projectID', 'addMemberOptions'));
?>
<script type="text/javascript">
    $('.removeTask').on('click', function(e) {
        return confirm('<?=__('Do you really want to delete this task?')?>');
    })

    $('.removeSubproject').on('click', function(e) {
        return confirm('<?=__('Do you really want to delete this subproject?')?>');
    })
</script>
<?
    }
?>
