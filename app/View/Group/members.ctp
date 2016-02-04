<?php

    $this->Html->script('vendor/jquery.nicescroll.min', array('inline' => false));
    $groupID = Hash::get($group, 'Group.id');
	$title = Hash::get($group, 'Group.title');

	/* Breadcrumbs */
	$this->Html->addCrumb($title, array('controller' => 'Group', 'action' => 'view/'.$groupID));
	$this->Html->addCrumb(__('Members'), array('controller' => 'Group', 'action' => 'members'));

    $src = $this->Media->imageUrl(Hash::get($group, 'GroupMedia'), 'thumb200x200');
    //echo $this->element('group_header', array( 'group' => $group));

    // Какого хрена groupAccess нужен 2 раза - не понятно,  но с 1 разом блок не рендерится
	// Закрывать блоки нужно, тогда будет рендериться, к примеру в group_header последний </div> был без закрывающей скобки
?>
<div class="groupAccess clearfix">
<?
    if ($isGroupAdmin || $isGroupResponsible) {
?>
<?
        foreach($aMembers as $member) {
            if (!$member['GroupMember']['approved'] && !$member['GroupMember']['is_invited']) {
                $userID = Hash::get($member, 'GroupMember.user_id');
                if(empty($userID)){
                    continue;
                }
                $user = $aUsers[$userID];
                $urlView = $this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']));
                $urlJoinApprove = $this->Html->url(array('controller' => 'Group', 'action' => 'memberApprove', $groupID, $userID));
                $urlRemove = $this->Html->url(array('controller' => 'Group', 'action' => 'memberRemove', $groupID, $userID));
            ?>
                <div class="item clearfix">

                    <a href="<?=$urlView?>">
                        <?php echo $this->Avatar->user($user, array(
                            'class' => 'ava',
                    		'size' => 'thumb50x50'
                    	)); ?>
                        <div class="info">
                            <span class="name"><?=$user['User']['full_name']?></span>
                            <span class="position"><?=Hash::get($user, 'User.skills')?></span>
                        </div>
                    </a>

                    <div class="buttonsControls">
                        <div class="accept" onclick="showAddUser(<?=$member['GroupMember']['id']?>, <?=$userID?>)" ><span class="glyphicons ok_2"></span></div>
                        <div class="remove" onclick="window.location.href='<?=$urlRemove?>'; return true;" ><span class="glyphicons bin"></span></div>
                    </div>

                </div>
            <?
            }
        }
?>

<?
    }
?>
</div>

<br />
<br />
<?
    echo $this->Form->create('GroupMember');
?>
<div class="groupCommand members clearfix fixedLayout">
<?
    if ($isGroupAdmin || $isGroupResponsible) {
?>
    <a href="javascript:void(0)" class="item" onclick="showInviteMember()">
        <img src="/img/add-user.png" alt="" />
        <div class="name"></div>
        <div class="position"><?=__('Add member')?></div>
    </a>
<?
    }
    foreach($aMembers as $i => $member) {
        $member = $member['GroupMember'];
        if ($member['approved']) {
            $user = $aUsers[$member['user_id']];
            $userID = Hash::get($user, 'User.id');
            $profileID = Hash::get($user, 'User.id');
            $urlView = ($profileID) ? $this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id'])) : 'javascript:void(0)';
            $urlRemove = $this->Html->url(array('controller' => 'Group', 'action' => 'memberRemove', $groupID, $userID));
?>
    <div class="item">
<?
            if (($isGroupAdmin || $isGroupResponsible) && $userID != Hash::get($group, 'Group.owner_id')) {
?>
        <a href="<?=$urlRemove?>" onclick="return confirm('<?=__('Are you sure to delete this user')?>?');" class="remove"><span class="glyphicons circle_remove"></span></a>
<?
            }
?>
		<a href="<?=$urlView?>">
            <?php echo $this->Avatar->user($user, array(
        		'size' => 'thumb200x200'
        	)); ?>
        </a>
		<a href="<?=$urlView?>" class="name"><?=$user['User']['full_name']?></a>
		<a href="<?=$urlView?>"> <p style="dysplay: block;  white-space: nowrap; overflow: hidden; padding: 5px;  text-overflow: ellipsis;"><?=$member['role']?>	</p></a>
<?
            if ($isGroupAdmin || $isGroupResponsible) {
?>
                <?=$this->Form->hidden('id', array('name' => 'data[GroupMember]['.$i.'][id]', 'value' => $member['id']))?>
                <?=$this->Form->hidden('uid', array('name' => 'data[GroupMember]['.$i.'][uid]', 'value' => $member['user_id']))?>
                <?=$this->Form->input('show_main', array('label' => false, 'div' => false, 'name' => 'data[GroupMember]['.$i.'][show_main]', 'checked' => $member['show_main']))?> <?=__('Team')?>
                <div class="wages">
                    <?=$this->Form->input('wages', array('label' => false, 'div' => false, 'name' => 'data[GroupMember]['.$i.'][wages]', 'value'=>$member['wages']))?>    <?=__('$/hr')?>
                </div>
<?
                if ($isGroupAdmin) {
?>
                <?=$this->Form->input('responsible', array('label' => false, 'div' => false, 'class' => 'responsible', 'name' => 'data[GroupMember]['.$i.'][responsible]', 'checked' => ($member['user_id'] == Hash::get($group, 'Group.responsible_id')) ))?> <?=__('Responsible')?>
<?
                }
            }
?>
    </div>
<?
        }
    }
?>
</div>
<?
    if ($isGroupAdmin || $isGroupResponsible) {
?>
    <?=$this->Form->hidden('action', array('name' => 'data[action]', 'value' => 'edit_main'))?>
    <button class="btn btn-primary"><?=__('Save')?></button>
    <?=$this->Form->end()?>
<?
    }
?>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

<div class="modal fade" id="approveMember" tabindex="-1" role="dialog">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="glyphicons circle_remove" data-dismiss="modal"></span>
            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

<div class="modal fade" id="InviteMember" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="glyphicons circle_remove" data-dismiss="modal"></span>

                <form>
                    <div class="form-group">
                        <label><?=__('Enter user name or email...')?></label>
                        <input type="text" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                        <label><?=__('Role')?></label>
                        <input type="text" class="form-control" id="role">
                    </div>
                    <img src="/img/ajax_loader.gif" alt="" class="preloader">
                    <div class="usersScroll">
                        <!-- USERLIST GOES HERE -->
                    </div>
                    <div class="clearfix">
                        <button type="button" id="sendInvite" class="btn btn-primary disabled" onclick="inviteUser()"><?=__('Add')?></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

<script type="text/javascript">
var aUsers = null;

;(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 1e3;
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                $el.is(':input') && $el.on('keyup keypress',function(e){
                    if (e.type=='keyup' && e.keyCode!=8) return;
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        doneTyping(el);
                    }, timeout);
                }).on('blur',function(){
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);

$('#InviteMember #name').keypress(function() {
    $('#InviteMember .preloader').show();
});

$('#InviteMember #name').donetyping(function() {
    var postData = { q: $('#InviteMember #name').val() };
    $.post( "<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'userList'))?>", postData, ( function (data) {
        $("#InviteMember .usersScroll").html(data);
    }));
    $('#InviteMember .preloader').hide();
});

function inviteUser() {
    if( $('.item.user.active').length > 0 ) {
        var postData = { group_id: '<?=$groupID?>', user_id: $('.item.user.active').data('user_id'), role: $('#InviteMember #role').val() };
        $.post( groupURL.invite, postData, ( function (data) {
            $("#InviteMember .usersScroll").html(data);
            $('#InviteMember').modal('hide');
            $("#InviteMember .usersScroll").html('');
            $('#InviteMember #name').val('');
            $('#InviteMember #role').val('');
            alert( '<?=__('Invite had been sended')?>' );
        }));
    }
};

function showAddUser(memberID, userID) {
    var user = aUsers[userID];
    user.GroupMember = {id: memberID};
    $('#approveMember').hide();
    $('#approveMember .modal-content form').remove();
    $('#approveMember .modal-content').append(tmpl('group-member', user).replace(/~userID/g, user.User.id));
    $.get('<?php echo $this->Html->url(array('controller' => 'GroupAjax', 'action' => 'checkLimits', $groupID), true)?>', {}, function( data ) {
        if(data.allowed == false){
            $('#approveMember .modal-content').html(tmpl('subscribe-for-member'));
        }
    }, 'json');
    $('#approveMember').modal({backdrop:false});
    $('#approveMember').modal();

    $('#approveMember .form-control').bind("keyup change", function() {
        if( $(this).val().length > 3 ) {
            $('#approveMember button').removeClass('disabled');
        } else {
            $('#approveMember button').removeClass('disabled').addClass('disabled');
        }
    });
};

function showInviteMember() {
    $('#InviteMember').hide();
    $('#InviteMember button').removeClass('disabled').addClass('disabled');
    $("#InviteMember .usersScroll").html('');
    $('#InviteMember #name').val('');
    $('#InviteMember #role').val('');
    $.get('<?php echo $this->Html->url(array('controller' => 'GroupAjax', 'action' => 'checkLimits', $groupID), true)?>', {}, function( data ) {
        if(data.allowed == false){
            $('#InviteMember .modal-content').html(tmpl('subscribe-for-member'));
        }
    }, 'json');
    $('#InviteMember').modal({backdrop:false});
    $('#InviteMember').modal();
};

function checkInviteModal() {
    if($('.usersScroll .item.user.active').length && ($('#role').val().length > 1)) {
        $('#InviteMember button').removeClass('disabled');
    } else {
        $('#InviteMember button').removeClass('disabled').addClass('disabled');
    }
}

$(document).ready(function(){
    aUsers = <?=json_encode($aUsers)?>;
    $('#InviteMember .preloader').hide();

    $(document).on('click', '.item.user', function() {
        $('.item.user.active').removeClass('active');
        $(this).addClass('active');
        checkInviteModal();
    });

    $('#InviteMember #role').on('keyup', function() {
        checkInviteModal();
    });

    $('#InviteMember').on('shown.bs.modal', function (e) {
        $('.usersScroll').niceScroll({cursorwidth:"7px",cursorcolor:"#23b5ae", cursorborder:"none", autohidemode:"false", background: "#f1f1f1"})
        $('.usersScroll').getNiceScroll().show();
        $('body').css("position","fixed");
    })

    $('#InviteMember').on('hide.bs.modal', function (e) {
        $('.usersScroll').getNiceScroll().hide();
        $('body').css("position","static");
    });

    $('#approveMember').on('shown.bs.modal', function (e) {
        $('body').css("position","fixed");
    })

    $('#approveMember').on('hidden.bs.modal', function (e) {
        $('body').css("position","static");
    });

    $('.responsible').on('change', function(){
        $('.responsible').prop('checked', false);
        $(this).prop('checked', true);
    })
});
</script>

<script type="text/x-tmpl" id="group-member">
<?=$this->Form->create('GroupMember')?>
    <?=$this->Form->hidden('id', array('value' => '{%=o.GroupMember.id%}'))?>
    <div class="form-group">
        <label><?=__('Role')?></label>
        <?=$this->Form->input('role', array('div' => false, 'label' => false, 'class' => 'form-control'))?>
    </div>
    <div class="usersScroll" style="height: auto">
        <div class="item clearfix">
            <img alt="{%=o.User.full_name%}" src="{%=o.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" class="{%=user.User.rating_class%} ava">
            <div class="info">
                <span class="name">{%=o.User.full_name%}</span>
                <span class="position">{%=(o.User) ? o.User.skills : ''%}</span>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <button class="btn btn-default disabled"><?=__('Add member')?></button>
    </div>
<?=$this->Form->end()?>
</script>

<script type="text/x-tmpl" id="subscribe-for-member">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Members Limit Notification</h4>
    </div>
    <div class="modal-body">
        <div class="text-center">
            <h4>You must upgrade your subscription to add new members in groups</h4>
            <p>
                <?php
                    echo $this->Html->link(__("Buy More Members", true),
                        array('controller' => "GroupLimits", 'action' => "buyMoreMembers"),
                        array('class' => 'btn btn-default textIconBtn')
                    );
                ?>
            </p>
        </div>
    </div>
</script>
