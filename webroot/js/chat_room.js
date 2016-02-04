var ChatRoom = function() {
    var self = this;
    self.ChatRoom = null;
    self.group = null;
    self.roomID = null;
    self.members = {};
    self.all_members = {};
    self.events = [];
    self.dialog = null;
    self.unread = [];
    self.lFirstRun = true;
    self.lCanSend = true;

    this.init = function(room, members, all_members, group) {
        self.ChatRoom = room.ChatRoom;
        self.group = group;
        self.roomID = room.ChatRoom.id;
        self.members = members;
        self.all_members = all_members;
        self.render();
        Chat.fixPanelHeight();

        $('.eventsDialog').prepend('<div class="chat-preloader"><span></span>Подождите...</div>');
    }

    this.initHandlers = function() {

        $('.rightMessage .editPanel .pencil').off('click');
        $('.rightMessage .editPanel .pencil').on('click', function(){
            $('#editMessage #message').val( $(this).data('msg') );
            $('#editMessage #event_id').val( $(this).data('event') );
            $('#editMessage #message').trigger('autosize.resize');

            $('#editMessage').css('opacity', '0');
            $('#editMessage').css('display', 'block');
            $('#editMessage #message').trigger('autosize.resize');
            $('#editMessage').css('display', 'none');
            $('#editMessage').css('opacity', '1');

            $('#editMessage').modal('show');
        });

        $('#editMessage').on('shown.bs.modal', function(){
            $('#editMessage #message').trigger('autosize.resize');
        });

        $('.rightMessage .editPanel .bin').off('click');
        $('.rightMessage .editPanel .bin').on('click', function(){

            if(!confirm('Are you sure ?')) {
                return;
            }
            var event_id = $(this).data('event');
            $.post( chatURL.removeMessage, {data: {event_id: event_id} },
                function (response) {
                obj = response;
                if( obj !== null ) {
                    if(obj.status == "ERROR") {
                        alert( obj.message );
                    }
                    if(obj.status == "OK") {
                        $('#event-'+event_id).remove();
                    }
                }
            });

        });

        $('.rightMessage .text, .leftMessage .text').linkify({
            tagName: 'a',
            target: '_blank',
            newLine: '\n',
            linkClass: 'underlink',
            linkAttributes: null
        });
    }

    this.scrollBottom = function () {
        setTimeout(function(){
            $('.scrollBottom', $(self.dialog).parent().get(0)).get(0).scrollIntoView(false);
        }, 100);
    }

    this.close = function() {
        $('#roomTab_' + self.roomID).remove();
        $('#roomChat_' + self.roomID).remove();
        $('#chatMembers_' + self.roomID).remove();
    }

    this.render = function() {
        // render room container

        $('.chat-dialogs').append(tmpl('room-chat', {room_id: self.roomID}));
        self.dialog = $('.chat-dialogs #roomChat_' + self.roomID + ' .innerDialog .eventsDialog').get(0);

        // render room tab
        $('.chat-tabs').append(tmpl('room-tab', {roomID: self.roomID, members: self.members, msg_count: 0, room: self.ChatRoom, group: self.group}));

        // $('.chat-members').append(tmpl('chat-members', {roomID: self.roomID, members: self.members}));

        setTimeout(function(){
            self.initHandlers();
        }, 100);
    }

    this.renderEvents = function(aEvents) {
        var html = '', event;
        // self.firstEvent = (aEvents[0]) ? aEvents[0].id : 0;
        for(var i = 0; i < aEvents.length; i++) {
            var event = aEvents[i];
            if (i == 0) {
                html+= tmpl('chat-event-first', {event: event});
            }
            if (event.active) {
                self.unread.push(event.id);
            }
            if( $('#event-'+event.id).length == 0 && $(html).filter('#event-'+event.id).length == 0 )
                html+= tmpl('chat-event', {event: event, members: self.all_members, group: ((self.ChatRoom.group_id) ? self.group : false)});
            if (event.event_type == chatDef.wasExcluded) {
                self.lCanSend = false;
            } else if (event.event_type == chatDef.wasInvited) {
                self.lCanSend = true;
            }
        }

        return html;
    },

    this.activate = function() {
        // activate tab
        $('.room-tab').removeClass('active');
        $('#roomTab_' + self.roomID).addClass('active');

        // activate dialog
        $('.room-chat').hide();
        $('#roomChat_' + self.roomID).show();

        // activate users
        $('.chat-members').hide();
        $('#chatMembers_' + self.roomID).show();
        $('#roomUnread_' + self.roomID).html('');

        if (self.events) {

            $(self.dialog).append(self.renderEvents(self.events));
            if (self.lCanSend) {
                $('.sendForm').show();
            } else {
                $(".sendForm").hide();
            }

            self.events = [];
            if (self.unread.length) {
                Chat.disableUpdate();
                //self.setUnread(Chat.Panel.formatUnread(self.unread.length));
                co = parseInt($('#chatTotalUnread').html());
                count = co - self.unread.length;
                if (count > 10) {
                    count = '10+';
                } else if (!count) {
                    count = '';
                }

                $('#chatTotalUnread').html(count);
                $.post(chatURL.markRead, {data: {ids: self.unread}}, function(response){
                    if (checkJson(response)) {
                        self.unread = [];
                        self.scrollBottom();
                        Chat.enableUpdate();
                    }
                }, 'json');
            } else if (self.lFirstRun) {
                self.lFirstRun = false;
                Chat.fixPanelHeight();
                self.scrollBottom();
            }
        }
    }

    this.sendMsg = function () {
        Chat.openEnabled = false;
        var msg = $('.sendForm textarea').val();
        if (msg) {
            $('.sendForm textarea').val('');
            $('#processRequest').show();
            $.post(chatURL.sendMsg, {data: {msg: msg, roomID: self.roomID}}, function(response){
                if (checkJson(response)) {
                    $(self.dialog).append(tmpl('chat-msg', {msg: msg, eid: response.data}));
                    self.scrollBottom();
                    $('#processRequest').hide();

                    setTimeout(function(){
                        self.initHandlers();
                    }, 100);
                }
                Chat.openEnabled = true;
            }, 'json');
        }

        $('.preloadArea').each(function(){
            $('.circle_remove', this).hide();
            // $('.abortload-chat-file', this).show();
            $('.progress', this).show();
            $e = ($('img', this).length) ? $('img', this) : $('span.filetype', this);
            $e.data().submit();
        });
    }

    this.sendFile = function (fileData) {
        Chat.openEnabled = false;
        $.post(chatURL.sendFile, {data: {id: fileData.id, roomID: self.roomID}}, function(response){

            if (checkJson(response)) {
                $('.preloadThumb img').remove();
                $('.preloadFile span').remove();
                $('.preloadArea .circle_remove').hide();
                $('.preloadArea .process').hide();
                $('.preloadArea').hide();
                Chat.fixPanelHeight();

                var event = {
                    event_type: chatDef.fileUploaded,
                    file: fileData
                };
                $(self.dialog).append(tmpl('chat-file', {event: event}));
                self.scrollBottom();
            }
            Chat.openEnabled = true;
        }, 'json');
    }

    this.sendFiles = function (fileData) {
        Chat.openEnabled = false;
        $('#processFile').show();
        $.post(chatURL.sendFiles, {data: {files_data: fileData, roomID: self.roomID}}, function(response){
            if (checkJson(response)) {
                $('#processFile').hide();
                if(response['data'].hasOwnProperty('Error')) {
                    $('.preloadArea').empty().html('<div style="color: red;">' + response['data']['Error'] + '</div>');
                    setTimeout(function() {
                        $('.preloadArea').fadeOut('slow', function() {
                            $(this).remove();
                        })
                    }, 3000);
                }
                else
                    $('.preloadArea').remove();
                Chat.fixPanelHeight();

                for(var i = 0; i < response.data.length; i++) {
                    var event = {
                        event_type: chatDef.fileUploaded,
                        file: response.data[i].Media,
                        id: response.data[i].Media.object_id
                    };
                    $(self.dialog).append(tmpl('chat-file', {event: event}));

                    setTimeout(function(){
                        self.initHandlers();
                    }, 100);
                }
                self.scrollBottom();
            }
            Chat.openEnabled = true;
        }, 'json');
    }

    this.setUnread = function(count) {
        $('#roomTab_' + self.roomID + ' span.badge').html(count);
    }

    this.updateMembers = function(members) {
        self.members = members;
        $('#roomTab_' + self.roomID).replaceWith(tmpl('room-tab', {roomID: self.roomID, members: self.members, msg_count: 0, room: self.ChatRoom}));
        Chat.Panel.updateTabs();
    }

    this.addMember = function(userID) {
        Chat.disableUpdate();
        $('#processRequest').show();
        $.post(chatURL.addMember, {data: {room_id: self.roomID, user_id: userID}}, function(response){
            if (checkJson(response)) {
                if (response.data.newRoom) {
                    Chat.enableUpdate();
                    $('#processRequest').hide();
                    Chat.Panel.openRoom(response.data.newRoom.ChatRoom.id);
                    return;
                }
                self.updateMembers(response.data.members, response.data.all_members);

                // update dialog
                var event = {
                    event_type: chatDef.invitedUser,
                    recipient_id: userID
                };
                $(self.dialog).append(tmpl('extra-msg', {event: event, members: self.all_members}));
                $('#processRequest').hide();
                self.scrollBottom();

            }
        }, 'json');
    }

    this.removeMember = function(userID) {
        Chat.disableUpdate();
        $('#processRequest').show();
        $.post(chatURL.removeMember, {data: {room_id: self.roomID, user_id: userID}}, function(response){
            if (checkJson(response)) {
                if (response.data.newRoom) {
                    Chat.enableUpdate();
                    $('#processRequest').hide();
                    Chat.Panel.openRoom(response.data.newRoom.ChatRoom.id);
                    return;
                }
                self.updateMembers(response.data.members, response.data.all_members);

                // update dialog
                var event = {
                    event_type: chatDef.excludedUser,
                    recipient_id: userID
                };
                $(self.dialog).append(tmpl('extra-msg', {event: event, members: self.all_members}));
                $('#processRequest').hide();


            }
        }, 'json');
    }

    this.loadMore = function(event_id, roomDiv) {
        Chat.disableUpdate();
        // $('#processRequest').show();
        $.post(chatURL.loadMore, {data: {room_id: self.roomID, id: event_id}}, function(response){
            if (checkJson(response)) {
                if (response.data.events) {
                    Chat.Panel.dispatchEvents(response.data);
                    var newChatData = self.renderEvents(self.events);
                    $('#loadMoreTemp .eventsDialog').html(newChatData);

                    setTimeout( function() {
                        $('#loadMoreTemp').css('display', 'block');
                        var offset = $('#loadMoreTemp .innerDialog').height();
                        $('#loadMoreTemp').css('display', 'none');
                        $('.eventData', self.dialog).replaceWith( $('#loadMoreTemp .eventsDialog').html() );

                        // var scrlTop = $(roomDiv).scrollTop();

                        $(roomDiv).scrollTop(offset);

                        // console.log(offset);

                        // $('#loadMoreTemp .eventsDialog').html('');
                        self.events = [];

                        $('.chat-preloader').css('opacity', 0);

                        // $('#processRequest').hide();
                        setTimeout( function() {
                            Chat.enableUpdate();
                            disableScroll = false;
                            $(document).off("touchmove");
                            $('body').off('touchstart touchend touchcancel touchleave touchmove');
                        });
                        setTimeout(function(){
                            self.initHandlers();
                        }, 100);
                    }, 200);
                }
                // Chat.Panel.activateTab();
            }
        }, 'json');
    }
}
