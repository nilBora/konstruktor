/// <reference path="../../Scripts/Typings/jquery/jquery.d.ts"/>
/// <reference path="jquery.chatjs.interfaces.ts"/>

var ChatWindowOptions = (function () {
    function ChatWindowOptions() {
    }
    return ChatWindowOptions;
})();

// a generic window that shows in the bottom right corner. It can have any content in it.
var ChatWindow = (function () {
    function ChatWindow(options) {
        var _this = this;
        var defaultOptions = new ChatWindowOptions();
        defaultOptions.isMaximized = true;
        defaultOptions.chatpositioncookie = 'chatjsposition';
        //defaultOptions.chatposition = "chatjs";
        defaultOptions.canClose = true;
        defaultOptions.position = new Object();
        defaultOptions.onCreated = function () {
        };
        defaultOptions.onClose = function () {
        };
        defaultOptions.onMaximizedStateChanged = function () {
        };

        this.options = $.extend({}, defaultOptions, options);

        // window
        this.$window = $("<div/>").addClass("chat-window").appendTo($("body"));

        if (this.options.width)
            this.$window.css("width", this.options.width);

        // title
        this.$windowTitle = $("<div/>").addClass("chat-window-title").appendTo(this.$window);
        var goToAll = $("<div/>").addClass('go-to-all').appendTo(this.windowTitle);
        goToAll.html('<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>');
         //= $("<div/>").addClass('go-to-all').html('<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>').appendTo(this.windowTitle);
        if (this.options.canClose) {
            var $closeButton = $("<div/>").addClass("close").html('<span class="glyphicons remove_2"></span>').appendTo(this.$windowTitle);
            $closeButton.click(function (e) {
                //e.stopPropagation();
                _this.remove();
            });
        }
        var $goButton = $("<div/>").addClass("go-to-chat").html('<span class="glyphicons more_windows"></span>').appendTo(this.$windowTitle);
        $goButton.click(function(e){
            window.location.href= '/Chat/room/'+_this.options.roomId;
        });
        //var $minimizeButton = $("<div/>").addClass("minimize").html('<span class="glyphicons down_arrow"></span>').appendTo(this.$windowTitle);
        //$minimizeButton.click(function () {
        //    _this.toggleMaximizedState();
        //});

        $("<div/>").addClass("text").html( this.options.title ).appendTo(this.$windowTitle);

        // content
        this.$windowContent = $("<div/>").addClass("chat-window-content").appendTo(this.$window);
        //if (this.options.height)
        //    this.$windowContent.css("height", this.options.height);
        this.$windowInnerContent = $("<div/>").addClass("chat-window-inner-content").appendTo(this.$windowContent);

        this.setState(this.options.isMaximized, false);
        this.$window.draggable({
            handle: $('div.chat-window-title'),
            containment:'.wrapper-container',
            scroll:false,
            start: function(event, ui) {
              _this.$window.css("bottom",'initial');
            },
            stop: function( event, ui ) {
                _this.options.position = ui.position;

                var states  = ChatController.prototype.readCookie(_this.options.chatpositioncookie);
                $.each(states.pmWindows, function(index, pmWindow){
                    if(pmWindow.otherUserId == _this.options.userId){
                        states.pmWindows[index].position = _this.options.position;
                    }
                });
                ChatController.prototype.createCookie(_this.options.chatpositioncookie, states);
            }
        })
        var states  = ChatController.prototype.readCookie(this.options.chatpositioncookie);
        var coockieposition = '';
        if(states){
            $.each(states.pmWindows, function(index, pmWindow){
                if(pmWindow.otherUserId == _this.options.userId){
                    coockieposition = pmWindow.position;
                    return;
                }
            });
            if(coockieposition.left && (coockieposition.left < $(window).width() && coockieposition.left > 0)){
                this.$window.css("left", coockieposition.left);
            }
            if(coockieposition.top && (coockieposition.top < $(window).height() && coockieposition.top > 0)){
                this.$window.css("top", coockieposition.top);
                this.$window.css("bottom", 'initial');
            }
        }
        this.options.onCreated(this);
    }

    ChatWindow.prototype.getPosition = function() {
        var states  = ChatController.prototype.readCookie(this.options.chatpositioncookie);
        $.each(states.pmWindows, function(index, pmWindow){
            if(pmWindow.otherUserId == _this.options.userId){
                return pmWindow.position;
            }
        });
        return ;
    }

    ChatWindow.prototype.remove = function () {
        // removes the window
        this.$window.remove();
        // triggers the event
        this.options.onClose(this);
    }

    ChatWindow.prototype.getWidth = function () {
        return this.$window.outerWidth();
    };

    ChatWindow.prototype.setRightOffset = function (offset) {
        this.$window.css("right", offset);
    };

    ChatWindow.prototype.setTitle = function (title) {
        $("div[class=text]", this.$windowTitle).text(title);
    };

    ChatWindow.prototype.setVisible = function (visible) {
        if (visible)
            this.$window.show();
        else
            this.$window.hide();
    };

    // returns whether the window is maximized
    ChatWindow.prototype.getState = function () {
        return !this.$window.hasClass("minimized");
    };

    ChatWindow.prototype.setState = function (state, triggerMaximizedStateEvent) {
        // windows are maximized if the this.$windowContent is visible
        if (typeof triggerMaximizedStateEvent === "undefined") { triggerMaximizedStateEvent = true; }
        if (state) {
            // if it can't expand and is maximized
            this.$window.removeClass("minimized");
            this.$windowContent.show();
        } else {
            // if it can't expand and is minimized
            this.$window.addClass("minimized");
            this.$windowContent.hide();
        }

        if (triggerMaximizedStateEvent)
            this.options.onMaximizedStateChanged(this, state);
    };

    ChatWindow.prototype.toggleMaximizedState = function () {
        this.setState(this.$window.hasClass("minimized"));
    };

    ChatWindow.prototype.focus = function () {
        //todo: Implement
    };
    return ChatWindow;
})();

// The actual plugin
$.chatWindow = function (options) {
    var chatWindow = new ChatWindow(options);
    return chatWindow;
};
//# sourceMappingURL=jquery.chatjs.window.js.map
