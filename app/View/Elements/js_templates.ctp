<script type="text/x-tmpl" id="room-tab">
{%

if (o.room.group_id) {
    include('group-tab', {roomID: o.roomID, members: o.members, msg_count: o.msg_count, room: o.ChatRoom, group: o.group, current_user: o.current_user});
} else {
    var count = Chat.Panel.formatUnread(o.msg_count);

%}
<div id="roomTab_{%=o.roomID%}" class="room-tab item" onclick="if(Chat.openEnabled == true) { Chat.Panel.activateTab({%=o.roomID%}) }">
    <span class="badge badge-important">{%=count%}</span>
{%
    count = 0;
    var user;
    for(var id in o.members) {
        count++;
        user = o.members[id];
%}
    <a class="user" href="javascript:void(0)">
        <img class="{%=user.User.rating_class%} ava" src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="" />
{%
        var lCanExclude = Object.keys(o.members).length > 1 <? // можно выкидывать из комнаты только если юзеров больше 2?>
            && id != o.room.initiator_id && id != o.room.recipient_id <? // и это не первоначальные юзеры ?>
            && (<?=$currUserID?> == o.room.initiator_id || <?=$currUserID?> == o.room.recipient_id); <? // и если юзер сам первоначальный, остальным - запрещаем?>
        if (lCanExclude) {
%}
        <span class="glyphicons circle_remove remove-member" onclick="var e = arguments[0] || window.event; if ($(e.target).hasClass('remove-member') ) { e.stopPropagation(); Chat.Panel.removeMember({%=id%}, {%=o.roomID%}); }"></span>
{%
        }
%}
    </a>
{%
    }
%}
    <div class="remove"><a class="glyphicons circle_remove tab-remove" href="javascript: void(0)" onclick="if(Chat.openEnabled == true) { var e = arguments[0] || window.event; if ($(e.target).hasClass('tab-remove') ) { e.stopPropagation(); Chat.Panel.closeTab({%=o.roomID%}); }}"></a></div>
{%
    if (count <= 1) {
%}
    <div class="name">{%=user.User.full_name%}</div>
{%
    }
}
%}
</div>
</script>

<script type="text/x-tmpl" id="group-tab">
{%
    var count = Chat.Panel.formatUnread(o.msg_count);
    if(o.group && o.group.hasOwnProperty('Group')) {

        if(o.group.Group.responsible_id == <?php echo $currUserID; ?>) {
            var userObj = o.members[Object.keys(o.members)[0]];
            var src = userObj.UserMedia.url_img.replace(/noresize/, 'thumb100x100');
            var full_name = userObj.User.full_name;
            var rating = userObj.User.rating_class;
        } else {
            var full_name = o.group.Group.title;
            var src = o.group.GroupMedia.url_img.replace(/noresize/, 'thumb100x100');
            var rating = o.group.Group.rating_class;
        }
    }

%}
<div id="roomTab_{%=o.roomID%}" class="room-tab item" onclick="if(Chat.openEnabled == true) { Chat.Panel.activateTab({%=o.roomID%}) }">
    <span class="badge badge-important">{%=count%}</span>
    <a class="user" href="javascript:void(0)">
        <img class="{%=rating%} ava" src="{%=src%}" alt="" />
    </a>
    <div class="remove"><a class="glyphicons circle_remove tab-remove" href="javascript: void(0)" onclick="if(Chat.openEnabled == true) { var e = arguments[0] || window.event; if ($(e.target).hasClass('tab-remove') ) { e.stopPropagation(); Chat.Panel.closeTab({%=o.roomID%}); }}"></a></div>
    <div class="name">{%=full_name%}</div>
</div>
</script>

<script type="text/x-tmpl" id="room-chat">
<div id="roomChat_{%=o.room_id%}" class="dialog clearfix room-chat">
    <div class="innerDialog" style="padding-bottom: 20px;">
        <div class="eventsDialog"></div>
        <div class="scrollBottom" style="height: 1px"> </div>
    </div>
</div>
</script>

<script type="text/x-tmpl" id="chat-members">
<span id="chatMembers_{%=o.roomID%}" class="chat-members">
{%
    if (Object.keys(o.members).length > 1) {
        var user;
        for(var id in o.members) {
            user = o.members[id];
%}
    <a href="javascript: void(0)">
        <img class="{%=user.User.rating_class%}" src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb50x50')%}" alt="" />
        <span class="shadow glyphicons circle_remove"></span>
    </a>
{%
        }
    }
%}
</span>
</script>

<script type="text/x-tmpl" id="chat-event">
{%
    var time = Date.fromSqlDate(o.event.created);
    var user;
    if (o.event.event_type == chatDef.incomingMsg || o.event.event_type == chatDef.outcomingMsg) {
        user = (o.event.event_type == chatDef.incomingMsg) ? o.members[o.event.initiator_id] : false;
        include('chat-msg', {time: time, user: user, msg: o.event.msg, group: o.group, eid: o.event.id});
    } else if (o.event.event_type == chatDef.fileDownloadAvail || o.event.event_type == chatDef.fileUploaded) {
        user = (o.event.event_type == chatDef.fileDownloadAvail) ? o.members[o.event.initiator_id] : false;
        include('chat-file', {time: time, event: o.event, user: user, group: o.group});
    } else {
        include('extra-msg', {event: o.event, members: o.members});
    }
%}
</script>

<script type="text/x-tmpl" id="chat-event-first">
{%
    if (o.event.event_type != chatDef.roomOpened) {
%}
<div id="firstEvent_{%=o.event.room_id%}" class="eventData" data-id="{%=o.event.id%}" data-room_id="{%=o.event.room_id%}" style="display: none;"></div>
{%
    }
%}
</script>

<script type="text/x-tmpl" id="chat-msg">
{%
    var locale = '<?=Hash::get($currUser, 'User.lang')?>';
    var js_date = (o.time) ? o.time : new Date();
    var time = Date.fullDate(js_date, locale) + ' ' + Date.HoursMinutes(js_date, locale);
    var msg = $('<div>').html(o.msg).text();
%}
<div class="{%=((o.user) ? 'leftMessage' : 'rightMessage')%}" id="event-{%=o.eid%}">
{%
    if (o.user) {
        var src, name, rating;
        if (o.group && o.group.Group.owner_id == o.user.User.id) {
            src = o.group.GroupMedia.url_img;
            name = o.group.Group.title;
            rating = o.group.Group.rating_class;
        } else {
            src = o.user.UserMedia.url_img;
            name = o.user.User.full_name;
            rating = o.user.User.rating_class;
        }
%}
        <img class="{%=rating%} ava" src="{%=src.replace(/noresize/, 'thumb100x100')%}" alt="{%=name%}" style="width: 50px" />
{%
    } else {
%}
    <div class="editPanel"><span class="glyphicons pencil" data-event="{%=o.eid%}" data-msg="{%=msg%}"></span><span class="glyphicons bin" data-event="{%=o.eid%}"></span></div>
{%
    }
%}
    <div class="time">{%=time%}</div>
    <div class="text">
{%
    msg = msg.split("\n");
    $.each(msg, function( i, val ) {
%}
    {%=val%}<br>
{%
    });
%}

    </div>
</div>
<div class="clearfix"></div>
</script>

<script type="text/x-tmpl" id="chat-file">
{%
    var locale = '<?=Hash::get($currUser, 'User.lang')?>';
    var js_date = (o.time) ? o.time : new Date();
    var time = Date.fullDate(js_date, locale) + ' ' + Date.HoursMinutes(js_date, locale);
%}
<div class="{%=((o.user) ? 'leftMessage' : 'rightMessage')%} clearfix" id="event-{%=o.event.id%}">
{%
    if (o.user) {
        var src, name;
        if (o.group && o.group.Group.owner_id == o.user.User.id) {
            src = o.group.GroupMedia.url_img;
            name = o.group.Group.title;
            rating = o.group.Group.rating_class;
        } else {
            src = o.user.UserMedia.url_img;
            name = o.user.User.full_name;
            rating = o.user.User.rating_class;
        }
%}
    <img class="{%=rating%} ava" src="{%=src.replace(/noresize/, 'thumb100x100')%}" alt="{%=name%}" style="width: 50px" />
{%
    } else {
%}
    <div class="editPanel"><span class="glyphicons bin" data-event="{%=o.event.id%}"></span></div>
{%
    }
%}
    <div class="time">{%=time%}</div>
    <div class="text">
{%
    if (o.event.file.media_type == 'image') {
        var src, w, h, size, url;
        if (o.event.file.orig_w > 200) {
            src = o.event.file.url_img.replace(/noresize/, '400x');
            w = 400; h = Math.ceil(w / o.event.file.orig_w * o.event.file.orig_h);
        } else {
            src = o.event.file.url_img;
            w = o.event.file.orig_w; h = o.event.file.orig_h;
        }
        url = o.event.file.url_download;
        size = o.event.file.orig_w + 'x' + o.event.file.orig_h;
%}
        <a href="{%=o.event.file.url_preview%}" target="_blank">
            <div style="width: {%=w%}px; height: {%=h%}px">
                <img src="{%=src%}" alt="{%=o.event.file.file_name%}" data-type="media" data-size="{%=size%}" data-url="{%=url%}"/>
            </div>
        </a>
{%
    } else if (o.event.file.media_type == 'video'){

        //if (o.event.file.converted > 0) {
%}
			<a href="{%=o.event.file.url_preview%}" target="_blank" class="video-pop-this" data-url-down="{%=o.event.file.url_download%}" data-converted="{%=o.event.file.converted%}">
				<span class="filetype {%=o.event.file.ext.replace(/\./, '')%}"></span>
				<span class="fileLink">{%=o.event.file.orig_fname%}</span>
			</a>
{%
		//} else {
%}
			<!--<span class="chat-video-soon"><?php echo __('Video has been successfully added to the site and soon will be available') ?></span>-->
{%
		//}
    } else {
%}
        <a href="{%=o.event.file.url_preview%}" target="_blank">
            <span class="filetype {%=o.event.file.ext.replace(/\./, '')%}"></span>
            <span class="fileLink">{%=o.event.file.orig_fname%}</span>
        </a>
{%
	}
%}
    </div>
</div>
<div class="clearfix"></div>
</script>

<script type="text/x-tmpl" id="extra-msg">
{%
    if (o.event.event_type == chatDef.invitedUser) {
        var msg = '<?=__('You invited user "%s" in this room', '~user_name')?>';
        msg = msg.replace(/~user_name/, o.members[o.event.recipient_id].User.full_name);
%}
    <div class="date">{%=msg%}</div>
{%
    } else if (o.event.event_type == chatDef.wasInvited) {
        var msg = '<?=__('You were invited into this room')?>';
%}
    <div class="date">{%=msg%}</div>
{%
    } else if (o.event.event_type == chatDef.joinedRoom) {
        var msg = '<?=__('User "%s" joined this room', '~user_name')?>';
        msg = msg.replace(/~user_name/, o.members[o.event.recipient_id].User.full_name);
%}
    <div class="date">{%=msg%}</div>
{%
    } else if (o.event.event_type == chatDef.excludedUser) {
        var msg = '<?=__('You excluded user "%s" from this room', '~user_name')?>';
        msg = msg.replace(/~user_name/, o.members[o.event.recipient_id].User.full_name);
%}
    <div class="date">{%=msg%}</div>
{%
    } else if (o.event.event_type == chatDef.wasExcluded) {
        var msg = '<?=__('You was excluded from this room')?>';
%}
    <div class="date">{%=msg%}</div>
{%
    } else if (o.event.event_type == chatDef.leftRoom) {
        var msg = '<?=__('User "%s" left this room', '~user_name')?>';
        msg = msg.replace(/~user_name/, o.members[o.event.recipient_id].User.full_name);
%}
    <div class="date">{%=msg%}</div>
{%
    }
%}

<div class="clearfix"></div>
</script>

<script type="text/x-tmpl" id="chat-panel">
<div class="dropdown-panel-scroll">
    <div class="messages-list allMessages">

<ul>
{%
    if (o.aUsers && o.aUsers.length) {
        var user, user_id, name, message, time, count, media, members, url, group, group_id;
        for(var i = 0; i < o.aUsers.length; i++) {
            user = o.aUsers[i];
            user_id = user.User.id;
            room_id = (user.ChatContact) ? user.ChatContact.room_id : 0;
            if(user.ChatContact && user.ChatContact.group_id) {
                group_id = user.ChatContact.group_id;
            }
            else {
                group_id = 0;
                responsible_id = 0;
            }
            group = (group_id) ? o.aGroups[group_id] : false;
            if(group_id && user.ChatContact.user_id != user.ChatContact.responsible_id) {
                name = group.Group.title;
                src = group.GroupMedia.url_img;
                rating = group.Group.rating_class;
            }
            else {
                name = user.User.full_name;
                src = user.UserMedia.url_img;
                rating = user.User.rating_class;
            }
<!--            name = (group_id) ? group.Group.title : user.User.full_name;-->
            message = (user.ChatContact) ? user.ChatContact.msg : '';

            time = (user.ChatContact) ? user.ChatContact.modified : '';
            // TODO: format time to local

            if (o.q && !message) {
                message = user.User.skills; <?// потому что поиск идет еще и по скилам?>
            }
            if (o.innerCall) {
                var onclick = (room_id) ? 'Chat.Panel.openRoom(' + room_id + ')' : 'Chat.Panel.openRoom(null, ' + user_id + ')';
%}
            <li class="messages-new clearfix" onclick="{%=onclick%}">
{%
            } else {
                if (room_id) {
                    url = '<?=$this->Html->url(array('controller' => 'Chat', 'action' => 'room', '~room_id'))?>';
                    url = url.replace(/~room_id/, room_id);
                } else if (group_id) {
                    url = '<?=$this->Html->url(array('controller' => 'Chat', 'action' => 'group', '~group_id'))?>';
                    url = url.replace(/~group_id/, group_id);
                } else {
                    url = '<?=$this->Html->url(array('controller' => 'Chat', 'action' => 'index', '~user_id'))?>';
                    url = url.replace(/~user_id/g, user_id);
                }
%}
            <li class="messages-new clearfix" onclick="window.location.href='{%=url%}'">
{%
            }
            src = src.replace(/noresize/, 'thumb100x100');
            members = (user.ChatContact && user.ChatContact.members.length > 1) ? ' (+' + (user.ChatContact.members.length - 1) + ')' : '';
%}

                <figure class="messages-user"><img class="{%=rating%} ava rounded" src="{%=src%}" alt="{%=name%}"/></figure>
                <div class="text">
                    <div class="name">{%=name%}{%=members%}</div>
                    <div class="message clearfix">
                        <span class="time">{%=message%}</span>
{%
            if (user.ChatContact) {
                count = Chat.Panel.formatUnread(parseInt(user.ChatContact.active_count));
%}
                        <span id="roomUnread_{%=room_id%}" class="count">{%=count%}</span>
{%
            }
%}
                    </div>
                </div>
                <div class="aside-block">
{%
            members = '';
            if (user.ChatContact) {
                members = user.ChatContact.members.join(',');
                if (!(Chat.Panel.activeRoom && Chat.Panel.activeRoom == user.ChatContact.room_id)) {
<?
/**
    Контакт создается при открытии комнаты. Поэтому нельзя удалять контакт для активной комнаты.
    Его можно только скрывать, если в данный момент именно эта комната активна
**/
?>
%}

                    <span id="removeContact_{%=user.ChatContact.room_id%}" class="halflings trash remove-contact" onclick="var e = arguments[0] || window.event; if ($(e.target).hasClass('remove-contact')) { e.stopPropagation(); Chat.Panel.removeContact({%=user.ChatContact.id%}, {%=user.ChatContact.room_id%}) }"></span>
{%
                }
            } else {
                members = user_id;
            }
            /*
            if (Chat.Panel.activeRoom) {
                var activeRoom = Chat.Panel.rooms[Chat.Panel.activeRoom];
                if (activeRoom.ChatRoom.canAddMember && !activeRoom.members[user_id]) {
                */
            if (!group_id) {
%}
                    <span class="halflings plus add-member" data-members="{%=members%}" onclick="var e = arguments[0] || window.event; if ($(e.target).hasClass('add-member')) { e.stopPropagation(); Chat.Panel.addMember({%=user_id%}) }" style="display: none;"></span>
{%
            }
/*
                }
            }
            */
%}
                </div>
            </li>
{%
        }
    } else {
%}
        <li class="messages-new clearfix">
            <?=__('No user found')?>
        </li>
{%
    }
%}
</ul>

    </div>
</div>
</script>

<script type="text/x-tmpl" id="preload-chat-file">
{%
    if ($.inArray(o.type, ['jpg', 'jpeg', 'png', 'gif']) > -1) {
%}
<div id="{%=o.id%}" class="preloadArea preloadThumb">
    <div class="tempImg" style="width: 84px; height: 84px; background: #eee"></div>
{%
    } else {
%}
<div id="{%=o.id%}" class="preloadArea preloadFile">
    <span class="filetype {%=o.type%}"></span>
{%
    }
%}
    <a href="javascript: void(0)" class="glyphicons circle_remove remove-chat-file" onclick="$('#{%=o.id%}').remove()"></a>
    <a href="javascript: void(0)" class="glyphicons circle_remove abortload-chat-file" style="display: none;"></a>
    <div class="progress" style="display: none;">
        <div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress-bar progress-bar-info"></div>
    </div>
</div>
</script>
