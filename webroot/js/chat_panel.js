var ChatPanel = function(container, userID, roomID, groupID){ // static object
    var self = this;
    self.panel = container;
    self.userID = userID;
    self.roomID = roomID;
    self.groupID = groupID;
    self.rooms = {};
    self.activeRoom = null;

    this.init = function () {
        $.post(chatURL.contactList, null, function(response){
            if (checkJson(response)) {
                self.render(response.data);
                if (self.userID || self.roomID || self.groupID) {
                    self.openRoom(self.roomID, self.userID, self.groupID);
                }
            }
        });
    }

    this.initHandlers = function() {
        $('#searchChatForm').ajaxForm({
            url: chatURL.contactList,
            dataType: 'json',
            beforeSubmit: function(){
                // Chat.disableUpdate();
            },
            success: function(response) {
                if (checkJson(response)) {
                    self.render(response.data);
                    self.initHandlers(); // DOM elements were recreated
                    // Chat.enableUpdate();
                }
            }
        });
    }

    this.formatUnread = function(count) {
        if (count > 10) {
            count = '10+';
        } else if (!count) {
            count = '';
        }
        return count;
    }

    this.render = function (data) {
        var count, totalCount = 0;
        unreadCount = {};
        if (data.aUsers && data.aUsers.length) {
            for(var i = 0; i < data.aUsers.length; i++) {
                user = data.aUsers[i];
                if (user.ChatContact) {
                    count = parseInt(user.ChatContact.active_count);
                    totalCount+= count;
                    if (self.rooms[user.ChatContact.room_id]) {
                        self.rooms[user.ChatContact.room_id].setUnread(self.formatUnread(count));
                    }
                }
            }
        }
        $('#chatTotalUnread').html(self.formatUnread(totalCount));

        //var canAddMember = (self.activeRoom && self.rooms[self.activeRoom].ChatRoom.initiator_id);
        $(self.panel).html(tmpl('chat-panel', {
            innerCall: self.userID || self.groupID || self.roomID,
            q: $(".searchBlock .searchInput", self.panel).val(),
            aUsers: data.aUsers,
            aGroups: data.aGroups,
        }));
        self.updateAddMembers();
        self.updateDelContacts();
        self.initHandlers();
    }

    this.removeContact = function(contact_id, roomID) {
        Chat.disableUpdate();
        self.closeTab(roomID);
        $.post(chatURL.delContact, {data: {contact_id: contact_id}}, function(response){
            self.render(response.data);
            self.initHandlers();
            Chat.enableUpdate();
        });
    }

    this.openRoom = function(roomID, userID, groupID) {
        if (self.rooms[roomID]) {
            self.activateTab(roomID);
        } else if( Chat.openEnabled ) {
            Chat.openEnabled = false;
            Chat.disableUpdate();
            $.post(chatURL.openRoom, {data: {room_id: roomID, user_id: userID, group_id: groupID}}, function(response){
                if (checkJson(response)) {
                    var roomID = response.data.room.ChatRoom.id;
                    var room = new ChatRoom();

                    room.init(response.data.room, response.data.members, response.data.all_members, response.data.group);
                    self.rooms[roomID] = room; // add room into tabs stack
                    self.dispatchEvents(response.data.events);
                    self.activateTab(roomID);
                    Chat.enableUpdate();
                    self.initEventHandlers();
                    Chat.openEnabled = true;
                }
            }, 'json');
        }
    }

    this.initEventHandlers = function () {

        $('.dialog.room-chat').off('touchend');
        $('.dialog.room-chat').on('touchend', function() {
            if( $(this).scrollTop() < 0 ) {
                $(this).scrollTop(0);
            }
        });

        $('.dialog.room-chat').off('scroll');
        $('.dialog.room-chat').on('scroll', function (event) {

            if (self.disableScroll) {
                e.preventDefault();
                return false;
            }

            if(Chat.isUpdateEnabled()) {

                if(event.target.scrollTop < 100 && event.target.scrollTop >= 0 && $(event.target).find( ".eventData" ).length) {

                    $(document).on("touchmove", function(event){
                        event.preventDefault();
                    });

                    $('body').on('touchstart touchend touchcancel touchleave touchmove scroll', function(event) {
                        event.stopPropagation();
                        return false;
                    });

                    // $('#processRequest').show();

                    // $('.eventsDialog').prepend('<div class="preloader">Подождите</div>');
                }

                if(event.target.scrollTop <= 100 && $(event.target).find( ".eventData" ).length) {
                    Chat.disableUpdate();
                    disableScroll = true;
                    var inner = $(event.target).find( ".innerDialog" );
                    var offset = inner.height();

                    var x = $(event.target).find( ".eventData" );
                    var id = x.data('id');
                    var room_id = x.data('room_id');
                    //console.log(event.target);

                    $('.chat-preloader').css('opacity', 1);

                    Chat.Panel.rooms[room_id].loadMore(id, event.target);
                    Chat.enableUpdate();
                }
            }
        });

        if (is_touch_device()) {
/*
            $('.dialog.room-chat').on('touchstart', function (e) {
                if (disableScroll) {
                    e.preventDefault();
                    return false;
                }
            });

            $('.dialog.room-chat').on('touchmove', function (e) {
                if (disableScroll) {
                    e.preventDefault();
                    return false;
                }
            });

            $('.dialog.room-chat').on('touchend', function (e) {
                if (disableScroll) {
                    e.preventDefault();
                    return false;
                }
            });
*/
        }

    }

    this.disableCloseTabs = function () {
        $(".room-tab").addClass('disable-remove');
    }

    this.enableCloseTabs = function () {
        $(".room-tab").removeClass('disable-remove');
    }

    this.checkMember = function(members) {
        var activeRoom = self.rooms[Chat.Panel.activeRoom];
        if (members && members.split(',').length) {
            members = members.split(',');
            if (members.length > 1) {
                return false;
            }
            var memberID = members[0];
            var activeRoom = self.rooms[Chat.Panel.activeRoom];
            for(var id in activeRoom.members) {
                if (id == memberID) { // already in this room
                    return false;
                }
            }
        }
        return true;
    }

    this.updateAddMembers = function() {
        if (self.activeRoom) {
            if (Chat.Panel.rooms[Chat.Panel.activeRoom].ChatRoom.canAddMember) {
                $('.add-member', self.panel).each(function(){
                    if (self.checkMember($(this).data('members').toString())) {
                        $(this).show();
                    }
                });
            }
        }
    }

    this.updateDelContacts = function() {
        if (self.activeRoom) {
            $('.remove-contact', self.panel).show();
            $('#removeContact_' + self.activeRoom, self.panel).hide();
        }
    }

    this.updateTabs = function() {
        if ($(".openChats .room-tab").length > 1) { // single tab must be not closed
            self.enableCloseTabs();
        } else {
            self.disableCloseTabs();
        }
    }

    this.activateTab = function(roomID) {
        self.updateTabs();
        if (roomID) {
            self.activeRoom = roomID;
        }
        $('.add-member', self.panel).hide();
        if (self.activeRoom) {
            self.updateAddMembers();
            self.updateDelContacts();
            self.rooms[self.activeRoom].activate();
        }
    }

    this.closeTab = function (roomID) {
        var aRoomID = Object.keys(self.rooms);
        var nextRoom = 0;
        var prevRoom = 0;
        if (aRoomID.length > 1) { // disable to close single tab
            for(var i = 0; i < aRoomID.length; i++) {
                nextRoom = i + 1;
                prevRoom = i - 1;
                if (aRoomID[i] == roomID) {
                    break;
                }
            }

            var newID;
            if (nextRoom < aRoomID.length) {
                newID = aRoomID[nextRoom];
            } else {
                newID = aRoomID[prevRoom];
            }
            self.rooms[roomID].close();
            delete self.rooms[roomID];
            self.activateTab(newID);
        }
    }

    this.dispatchEvents = function (data) {
        var msg, user, file;
        for(var roomID in self.rooms) {
            if (roomID != self.activeRoom) {
                self.rooms[roomID].events = [];
            }
        }
        for(var roomID in data.updateRooms) {
            if (self.rooms[roomID]) {
                self.rooms[roomID].updateMembers(data.updateRooms[roomID]);
            }
        }
        for(var i = 0; i < data.events.length; i++) {
            var event = data.events[i].ChatEvent;
            if (self.rooms[event.room_id]) { // tab could be async-ly closed
                if (event.event_type == chatDef.incomingMsg || event.event_type == chatDef.outcomingMsg) {
                    msg = data.messages[event.msg_id];
                    event.msg = msg.message;
                } else if (event.event_type == chatDef.fileDownloadAvail || event.event_type == chatDef.fileUploaded) {
                    file = data.files[event.file_id];
                    /*
                    event.url = file.url_download;
                    event.file_name = file.orig_fname;
                    */
                    event.file = file;
                }
                self.rooms[event.room_id].events.push(event);
            }
        }
    }

    this.update = function(data) {
        self.render(data.aUsers);
        self.dispatchEvents(data);
        self.activateTab();
    }

    this.addMember = function(userID) {
        self.rooms[self.activeRoom].addMember(userID);
    }

    this.removeMember = function(userID, roomID) {
        self.rooms[roomID].removeMember(userID);
    }

}

function is_touch_device() {
    return (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
}

var disableScroll = false;
