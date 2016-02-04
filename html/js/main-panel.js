var lChatOpened = null, panel_list, main_panel_li;

function openMainPanel(open_this){
	if ($(open_this).find('.chatPanel').length){
		lChatOpened = true;
		if (typeof(Chat) != 'undefined' && Chat) {
			Chat.disableUpdate();
		}
	}
    if($('.main-panel-list li.open').length > 0){
        main_panel_li.removeClass('open');
        $(open_this).parent().addClass('open');
        panel_list.removeClass('dropdown-open');
        setTimeout(function() {
            var number_find = $('.main-panel-list li.open').index();
            panel_list.eq(number_find).addClass('dropdown-open');
        }, 650)
    }else{
        // closeMainPanel();
        main_panel_li.removeClass('open');
    	panel_list.removeClass('dropdown-open');
    	
        $(open_this).parent().addClass('open');
        panel_list.eq($(open_this).parent().index()).addClass('dropdown-open');
    }
}

function closeMainPanel(){
	if (lChatOpened) {
		if (typeof(Chat) != 'undefined' && Chat) {
			Chat.enableUpdate();
		}
	}
    main_panel_li.removeClass('open');
    panel_list.removeClass('dropdown-open');
}

function closeMainPanelWrapp(){
    closeMainPanel();
    setTimeout(function() {
        main_panel_li.removeClass('open');
        panel_list.removeClass('dropdown-open');
    }, 650);
}

$(function() {
    panel_list = $('.main-panel-dropdown .dropdown-panel');
    main_panel_li = $('.main-panel-list li');

    // click panel icon jobs
    $('.main-panel-list a').on('click', function(){
        if($(this).parent().hasClass('open')){
            closeMainPanel();
        }else{
            openMainPanel(this);
        }
    });

    $(document).on('touchstart click', function(event){
        if ($(event.target).closest($('.main-panel')).length) return true;
            closeMainPanelWrapp();
        event.stopPropagation();
    });

    // event resize window service menu fixed
    var windows_height = $(window).height(),
        panel_menu_height = $('.main-panel-wrapper .user-image').height() +
                            $('.main-panel-wrapper .main-panel-list').height()+
                            ($('.main-panel-wrapper .service-menu').height()+90);
    if(panel_menu_height >= windows_height){
        $('.main-panel-wrapper .service-menu').removeClass('service-menu-fixed');
    }else{
        $('.main-panel-wrapper .service-menu').addClass('service-menu-fixed');
    }
    $( window ).resize(function() {
        var windows_height = $(window).height(),
            panel_menu_height = $('.main-panel-wrapper .user-image').height() +
                $('.main-panel-wrapper .main-panel-list').height()+
                ($('.main-panel-wrapper .service-menu').height()+90);
        if(panel_menu_height >= windows_height){
            $('.main-panel-wrapper .service-menu').removeClass('service-menu-fixed');
        }else{
            $('.main-panel-wrapper .service-menu').addClass('service-menu-fixed');
        }
    });


    window.scrollBy(0, 1);
});