<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), array('controller' => 'User', 'action' => 'view/'.Hash::get($currUser, 'User.id')));
	$this->Html->addCrumb(__('Time management'), array('controller' => 'User', 'action' => 'timeManagement'));

    $dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
    $lang = Configure::read('Config.language') == 'rus' ? 'ru' : 'en';

    $viewStyles = array(
        'bootstrap/bootstrap-tokenfield'
    );
    $this->Html->css($viewStyles, null, array('inline' => false));

    $viewScripts = array(
        'vendor/jquery.autocomplete',
        'vendor/autosize.min',
        'vendor/bootstrap-datetimepicker.min',
        'vendor/bootstrap-datetimepicker.ru.js',
        'https://www.google.com/jsapi'
    );
    $this->Html->script($viewScripts, array('inline' => false));
?>

<style type="text/css">
    .control { margin-top: 22px; }
    .calandarPeriod { padding: 8px 0px 0; margin-bottom: 20px;}

    .control .jq-selectbox { float: left; margin-right: 8px; }
    .calandarPeriod .dateTime, .calandarPeriod .jq-selectbox  { float: left; margin-right: 8px; display: inline-block; }

    .crmStatistic { padding-right: 18px; }
    .crmStatistic .jq-selectbox, .calandarPeriod .dateTime, .calandarPeriod .jq-selectbox { width: 180px; }
	.btn-delayedEvents {margin-top: 32px; }
    .btn-delayedEvents a .count { display: inline-block; width: 18px; height: 18px; border-radius: 50%; background-color: #ff6363; font-size: 10px; font-weight: 100; color: white; text-align: center; line-height: 17px; margin-left: 12px; }

    @media (max-width: 1366px) {
        .control .jq-selectbox { width: 31%; }
        .calandarPeriod .dateTime, .calandarPeriod .jq-selectbox { width: 31%; }
    }

    @media (max-width: 1023px) {
        .control { margin-top: 16px; }
        .calandarPeriod { float: right; }

        .control .jq-selectbox { float: right; margin-top: 6px; width: 220px; }
        .calandarPeriod .dateTime, .calandarPeriod .jq-selectbox { float: right; margin-top: 6px; width: 220px; }

        .crmStatistic .jq-selectbox { width: 80%; }
    }

    @media (max-width: 767px) {
        .control { margin-top: 16px; }
        .calandarPeriod { width: 100%; padding: 0 15px; }
        .control .jq-selectbox { margin: 1%; float: none; width: 100%; }
        .calandarPeriod .dateTime, .calandarPeriod .jq-selectbox { float: none; margin: 1%; width: 100%; }
        .crmStatistic .jq-selectbox { width: 80%; }
    }

    .groupPackHead {
        font-size: 22px;
        font-weight: 600;
        padding: 0 0 0 5px;
        width: 100%;
        margin-top: 42px;
        height: 50px;
        line-height: 50px;
        cursor: pointer;
    }

    .groupPackHead .arrow {
        height: 32px;
        width: 32px;
        position: relative;
        top: -14px;
    }

    .groupPackHead .arrow:before {
        display: inline-block;
        padding: 5px;
        font-size: 20px;
        color: #bbb;

        -webkit-transition: all .4s cubic-bezier(0,.98,.29,1);
           -moz-transition: all .4s cubic-bezier(0,.98,.29,1);
            -ms-transition: all .4s cubic-bezier(0,.98,.29,1);
             -o-transition: all .4s cubic-bezier(0,.98,.29,1);
                transition: all .4s cubic-bezier(0,.98,.29,1);

        -webkit-transform: rotate(-90deg);
           -moz-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
             -o-transform: rotate(-90deg);
                transform: rotate(-90deg);
    }

    .groupPackHead:hover .arrow:before {
        color: #888;
    }

    .groupPack {
        -webkit-transition: all .4s ease-out;
           -moz-transition: all .4s ease-out;
            -ms-transition: all .4s ease-out;
             -o-transition: all .4s ease-out;
                transition: all .4s ease-out;
    }

    .groupPack.hiding {
        opacity: 0;
        -webkit-transform: translateY(-50px);
           -moz-transform: translateY(-50px);
            -ms-transform: translateY(-50px);
             -o-transform: translateY(-50px);
                transform: translateY(-50px);
    }

    .groupPackHead.collapsed .arrow:before {
        -webkit-transform: rotate(90deg);
           -moz-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
             -o-transform: rotate(90deg);
                transform: rotate(90deg);
    }

    .editTitle { cursor: pointer; }

    .crmStatistic .title { font-size: 16px; font-weight: 300; }
    .crmStatistic .jq-selectbox__select { border-width: 0; padding: 4px 12px 5px 28px; }
    .crmStatistic .jq-selectbox__select .jq-selectbox__trigger { left: 0; }
    .crmStatistic .jq-selectbox__select .jq-selectbox__trigger .jq-selectbox__trigger-arrow { right: 0; }

    .groupPack { -webkit-transform: translate3d(0px,0px,0px); }

        .editableEvent {
        cursor: pointer;
        -webkit-transition: all .2s linear;
           -moz-transition: all .2s linear;
            -ms-transition: all .2s linear;
             -o-transition: all .2s linear;
                transition: all .2s linear;
    }

    .editableEvent:nth-child(2n) { background: #F5FAFA; }
    .editableEvent:nth-child(2n) .title, .editableEvent:nth-child(2n) .task { border-top: 1px solid #B2DFDB; }

    .editableEvent:hover {
        background: #E0F2F1;
        -webkit-transition: all 0s linear;
           -moz-transition: all 0s linear;
            -ms-transition: all 0s linear;
             -o-transition: all 0s linear;
                transition: all 0s linear;
    }
    .editableEvent:hover .title, .editableEvent:hover .task { border-top: 1px solid #80CBC4; }

    .autocomplete-suggestions {
       -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            border: 1px solid #CCC;
    -webkit-border-radius: 4px;
            border-radius: 4px;
            background: #FFF;
    -webkit-box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            font-weight: 600;
            font-family: 'Open Sans';
            font-size: 13px;
            overflow: auto;
    }

    #uList.disabled .token .close { display: none; }
    #uList.disabled .token .token-label { max-width: 156px; }

    .autocomplete-suggestion { margin: 0; padding: 0; width: 100%; color: #231F20; min-height: 18px; padding: 5px 10px 6px; cursor: pointer }
    .autocomplete-selected { background-color: #22B5AE; color: #FFF; }
    .autocomplete-suggestions strong { font-weight: 600; color: #3399FF; }
    .autocomplete-selected strong { color: #FFF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

    .eventTypeModal form .groupAccess {
        -webkit-transition: all .5s ease-out;
           -moz-transition: all .5s ease-out;
            -ms-transition: all .5s ease-out;
             -o-transition: all .5s ease-out;
                transition: all .5s ease-out;
        max-height: 0;
        overflow: hidden; }

    .eventTypeModal form .groupAccess.one { max-height: 80px; }
    .eventTypeModal form .groupAccess.more { max-height: 160px; }

    .groupAccess .item { background: #ffffff;
        -webkit-transition: all .2s ease-out;
           -moz-transition: all .2s ease-out;
            -ms-transition: all .2s ease-out;
             -o-transition: all .2s ease-out;
                transition: all .2s ease-out;
        max-height: 74px;
        overflow: hidden; }
    .groupAccess .item:hover { background: #f5f6f8;
        -webkit-transition: all .1s ease-out;
           -moz-transition: all .1s ease-out;
            -ms-transition: all .1s ease-out;
             -o-transition: all .1s ease-out;
                transition: all .1s ease-out;
        max-height: 74px;
        overflow: hidden; }
    .groupAccess .item.selected { background: #f5f6f8;
        -webkit-transition: all .4s ease-out;
           -moz-transition: all .4s ease-out;
            -ms-transition: all .4s ease-out;
             -o-transition: all .4s ease-out;
                transition: all .4s ease-out;
        max-height: 0;
        opacity: 0;
        margin: 0; }

    #uList {
        padding-top: 1px;
        -webkit-transition: all 1s ease-out, margin .2s ease-out;
           -moz-transition: all 1s ease-out, margin .2s ease-out;
            -ms-transition: all 1s ease-out, margin .2s ease-out;
             -o-transition: all 1s ease-out, margin .2s ease-out;
                transition: all 1s ease-out, margin .2s ease-out;
        max-height: 200px;
        overflow: hidden; }
    #uList.empty { overflow: hidden; max-height: 0; margin: 0; padding: 0; }

    #userEventModal{ z-index: 500000; }
    .jq-selectbox__dropdown ul { max-height: 372px!important; }

    .token { overflow: hidden; position: relative; line-height: 100%; width: 159px; }
    .token img { position: absolute; top: 0; left: 0; height: 100%; padding-right: 5px; }
    .token .token-label { padding-left: 35px!important; max-width: 131px; }
    .token a.close { position: absolute!important; top: 0!important; right: 0!important; }
    .tokenfield .token:nth-child(2n) { margin-right: 0!important; }
</style>

<div class="row">
    <div class="col-sm-4 btn-delayedEvents">
		<a href="<?=$this->html->url(array('controller' => 'User', 'action' => 'delayedEvents'))?>" class="btn btn-default"><?=__('Set aside ')?><span class="count"><?=$delayedCound?></span></a>
    </div>

    <div class="col-sm-8 control" style="margin-top: 32px;">
		<div>
			<?=$this->Form->input('time', array('options' => $aPassOptions, 'label' => false, 'div' => false, 'id' => 'timeList' ))?>
			<?=$this->Form->input('category', array('options' => $aCategoryOptions, 'label' => false, 'div' => false, 'id' => 'categoryList' ))?>
			<?=$this->Form->input('type', array('options' => $aTypeOptions, 'label' => false, 'div' => false, 'id' => 'typeList' ))?>
		</div>


		<div class="calandarPeriod">
			<?=$this->Form->input('type', array('options' => $aGroupOptions, 'label' => false, 'div' => false, 'id' => 'groupList', 'value' => '0' ))?>
			<div class="dateTime date" id="date-from">
				<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
				<input type="text" class="form-control" placeholder="<?= __('From') ?>" readonly="readonly">
				<input type="hidden" id="statistic-from-mirror" name="from">
			</div>
			<div class="dateTime date" id="date-to">
				<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
				<input type="text" class="form-control" placeholder="<?= __('To') ?>" readonly="readonly">
				<input type="hidden" id="statistic-to-mirror" name="to">
			</div>
		</div>
    </div>


</div>

<div id="mainContainer"></div>
<br>
<br>

<div id="editTitle" class="modal fade" tabindex="-1" role="dialog">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="glyphicons circle_remove" data-dismiss="modal"></span>
                <h4><?=__('Edit title')?></h4>
                <form id="msgEditForm">
                    <?=$this->Form->hidden('old_title')?>
                    <div class="form-group">
                        <?=$this->Form->input('new_title', array('label' => false, 'class' => 'form-control', 'required' => 'required'))?>
                    </div>
                    <div class="clearfix">
                        <div id="postTitle" class="btn btn-primary loadBtn"><span><?=__('Save')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});

    editEventPopup = function (sql_date, time, sql_date2, time2, eType, recipientId, objType, objId, event_id, shared, title, descr,price) {
        var aHoursMinutes = time.split(':');
        var hours = aHoursMinutes[0];
        var minutes = aHoursMinutes[1];
        var js_date = Date.fromSqlDate(sql_date);
        js_date.setHours(parseInt(hours));
        js_date.setMinutes(parseInt(minutes));

        var aHoursMinutes2 = time2.split(':');
        var hours2 = aHoursMinutes2[0];
        var minutes2 = aHoursMinutes2[1];
        var js_date2 = Date.fromSqlDate(sql_date2);
        js_date2.setHours(parseInt(hours2));
        js_date2.setMinutes(parseInt(minutes2));
        var duration = js_date2.getTime();
        duration -= js_date.getTime();
        duration = duration / 60000;

        if( ([15, 30, 60, 120, 180, 240, 300]).indexOf(duration) < 0 ) {
            duration = 0;
        }

        var e = $('#user-event_' + event_id).get(0);
        var period = '';

        $('#userEventModal #monthStart').val(js_date.getMonth() + 1).change();
        $('#userEventModal #dayStart').val(js_date.getDate()).change();
        $('#userEventModal #timeStart').val(js_date.getHours()).change();
        $('#userEventModal #minuteStart').val(js_date.getMinutes()).change();
        $('#userEventModal #UserEventYearStart').val(js_date.getFullYear()).change();
        $('#userEventModal #timeDuration').val(duration).change();

        $('#uList .token').remove();
        $(".eventTypeModal .groupAccess").html('');
        if (recipientId.length != 0) {
            var recipients = recipientId.split(',');
            recipients.forEach(function (uid) {
                var postData = {
                    q: ''
                };
                $.post("/UserAjax/getById/" + uid, postData, (function (data) {
                    /*
                    $(".eventTypeModal .groupAccess").html(data);
                    $('.eventTypeModal form .groupAccess').css('height', 80);
                    */
                    item = $.parseHTML(data);
                    item = item[0];
                    var html = tmpl('user-select', {
                        id: $(item).data('user_id'),
                        name: $(".name", item).text(),
                        url: $('img', item).attr('src')
                    });
                    $('#eventUserList').append(html);
                    $('#uList').removeClass('empty');
                }));
            })
        } else {
            $('.eventTypeModal form .groupAccess').removeClass('one').removeClass('more');
            $('#uList').addClass('empty');
        }

        eType = ['meet', 'mail', 'call', 'sport', 'conference', 'task'].indexOf(eType) < 0 ? 'none' : eType;

        $('#UserEventId').val(event_id);
        $('#UserEventType').val(eType);
        $('#UserEventType').change();

        $('#UserEventRecipientId').val(recipientId);
        $('#UserEventObjectType').val('');
        $('#UserEventObjectId').val('');

        $('#UserEventObjectType').val(objType);
        $('#UserEventObjectId').val(objId);

        $('#UserEventTimeEvent').val(Date.HoursMinutes(js_date, locale));
        $('#eventDay input').val(Date.fullDate(js_date, locale));
        //$('#UserEventJsDateEvent').val(Date.fullDate(js_date, locale));
        $('#UserEventTitle').val(title);
        $('#UserEventDescr').val(descr);
        $('#UserEventIsDelayed').val('1');
        $('#UserEventPrice').val(price);
        showEventPopup();
    };

    showEventPopup = function () {
        $('.autocomplete-suggestions').css('z-index', 999999999);
        $('#userEventModal').modal('show');
    };

    closeEventPopup = function () {
        $('#UserEventTitle').autocomplete('hide');
        $('#UserEventTitle').popover('destroy');
        $('#userEventModal').modal('hide');
        $('#shareLinkModal').modal('hide');

        $('#removeEventButton').addClass('loadBtn').removeClass('disabled');
        $('#delayEventButton').addClass('loadBtn').removeClass('disabled');
        $('#saveEventButton').addClass('loadBtn').removeClass('disabled');
    };

    updateEvent = function () {
        if (eventIsValid()) {
            if ($('select.period').val() === 'day') {
                $('#dueDate input').val($('#eventDay input').val());
            }

            var i = 0;
            var str = '';
            $('#uList .token').each(function () {
                i++;
                if (i == 1) {
                    str += $(this).data('user-id');
                } else {
                    str += ',' + $(this).data('user-id')
                }
            })
            $('#UserEventRecipientId').val(str);

            $.post(profileURL.updateEvent, $('.eventTypeModal form').serialize(), function (response) {
                if (checkJson(response)) {
                    updateData();
                }
                closeEventPopup();
            });
        }
    };

    deleteEvent = function () {
        if (eventIsValid()) {
            $.post(profileURL.deleteEvent, $('.eventTypeModal form').serialize(), function (response) {
                if (checkJson(response)) {
                    updateData();
                }
                closeEventPopup();
            });
        }
    };

    var eventList = <?=$eventAutocomplete?>;
    var bindList = <?=$aBindOptions?>;
    var locale = '<?=Hash::get($currUser, 'User.lang')?>';
    $(document).ready(function(){

        /* ----------------------- ВСЁ ЧТО КАСАЕТСЯ ПОПАПА -------------------------------- */
        $('.jq-selectbox__select-text').css('width', '');


        $('.eventTypeModal #saveEventButton.loadBtn').click(function(){
            $('#UserEventIsDelayed').val('0');
            $(this).removeClass('loadBtn');
            $(this).removeClass('disabled').addClass('disabled');
            updateEvent();
        });

        $('.eventTypeModal #delayEventButton.loadBtn').click(function(){
            $('#UserEventIsDelayed').val('1');
            $(this).removeClass('loadBtn');
            $(this).removeClass('disabled').addClass('disabled');
            updateEvent();
        });

        $('.eventTypeModal #removeEventButton.loadBtn').click(function(){
            $(this).removeClass('loadBtn');
            $(this).removeClass('disabled').addClass('disabled');
            $('#delayEventButton').removeClass('disabled').addClass('disabled');
            $('#saveEventButton').removeClass('disabled').addClass('disabled');
            deleteEvent();
        });

        $('#UserEventTitle').autocomplete({
            lookup: bindList,
            groupBy: 'category',
            onSelect: function (suggestion) {
                //$('#UserEventTaskId').val(suggestion.data);
                $('#UserEventObjectType').val(suggestion.data.type);
                $('#UserEventObjectId').val(suggestion.data.id);
            }
        });

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

        $('#userSearch').keypress(function() {
            $('.eventTypeModal .preloader').show();
        });

        $('#userSearch').donetyping(function() {
            var postData = { q: $('#userSearch').val() };
            $.post( "<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'userList'))?>", postData, ( function (data) {
                $(".eventTypeModal .groupAccess").html(data);

                var itemCount = $('.item', '.eventTypeModal' ).length;

                if( itemCount == 1 ) {
                    $('.eventTypeModal form .groupAccess').removeClass('one').removeClass('more').addClass('one');
                } else if( itemCount > 1 ) {
                    $('.eventTypeModal form .groupAccess').removeClass('one').removeClass('more').addClass('more');
                } else if( itemCount == 0 ) {
                    $('.eventTypeModal form .groupAccess').removeClass('one').removeClass('more');
                }
            }));
            $('.eventTypeModal .preloader').hide();
        });

        $('.eventTypeModal .preloader').hide();

        $(document).on('click', '.item.user', function(e) {
            $(this).addClass('selected');
            if( $(".token[data-user-id='"+$(this).data('user_id')+"']").length == 0 ) {
                $('#UserEventRecipientId').val( $(this).data('user_id') );

                var html = tmpl('user-select', {id: $(this).data('user_id'), name: $(".name", this).text(), url: $('img', this).attr('src')});

                $('#eventUserList').append(html);
                if( $('#uList .token').length == 1 ) {
                    $('#uList').removeClass('empty');
                }
            }
        });

        $('.tokenfield').on('click', '.token a.close', function(e) {
            $(this).parents('.token').remove();
            if( $('#uList .token').length == 0 ) {
                $('#uList').addClass('empty');
            }
            $(".item[data-user_id='"+$(this).parents('.token').data('user-id')+"']").removeClass('selected');
        });

        $('select, input.filestyle, .bigCheckBox input').styler();

        //$('.absoluteWrap').height( $(window).height() );

        $('#UserEventDescr').autosize({append:false});
        $('#UserEventDescr').on('keyup copy cut paste change', function() {
            $('#UserEventDescr').trigger('autosize.resize');
        });


        eventIsValid = function () {
            var allow = true;

            if( $('#UserEventTitle').val().length < 1 ) {
                $('#UserEventTitle').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Title can not be empty')?>" });
                $('#UserEventTitle').popover('show');
                allow = false;
            }

            if( $('#UserEventType').val() == 'blank' ) {
                $('#UserEventType').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Event type not selected')?>" });
                $('#UserEventType').popover('show');
                allow = false;
            }

            if( !allow ) {
                $('.eventTypeModal #saveEventButton').removeClass('loadBtn').addClass('loadBtn');
                $('.eventTypeModal #saveEventButton').removeClass('disabled');

                $('.eventTypeModal #delayEventButton').removeClass('loadBtn').addClass('loadBtn');
                $('.eventTypeModal #delayEventButton').removeClass('disabled');
            }

            return allow;
        };

        $('#UserEventTitle, #UserEventType').on('focus', function(){
            $(this).popover('destroy');
        })

        /* ----------------------------------------------- ВСЁ ЧТО КАСАЕТСЯ СТРАНИЦЫ В ЦЕЛОМ -------------------------------------------------- */

        $('input.attachFile, select').styler({fileBrowse: '<span class="glyphicons paperclip"></span>'});
        updateData = function() {
            period = ($('#statistic-from-mirror').val() != '' && $('#statistic-to-mirror').val() != '' && $('#timeList').val() == '0') ? true : false;
            if(period == true) {
                sendData = {
                    dateFrom: $('#statistic-from-mirror').val(),
                    dateTo: $('#statistic-to-mirror').val(),
                    time: $('#timeList').val(),
                    category: $('#categoryList').val(),
                    group: $('#groupList').val(),
                    type: $('#typeList').val()
                };
            } else {
                sendData = {
                    time: $('#timeList').val(),
                    category: $('#categoryList').val(),
                    group: $('#groupList').val(),
                    type: $('#typeList').val()
                };
            }

            var url = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'timeManagementAjax'))?>';

            $.post(url, sendData, function (response) {
                $('#mainContainer').html(response);
                setTimeout( function() {
                    chartEventsRenderAjax();
                    chartSellsRenderAjax();
                    initControls();
                }, 50);
            });
        }

        initControls = function() {
            $('.task select').off('change');
            $('.task select').on('change', function() {
                var category = $(this).val() == 'work' ? '0' : '1';

                $.post(profileURL.changeEventCategory, { title: $(this).data('title'), category: category }, function (response) {
                    if (checkJson(response)) {
                        updateData();
                    }
                });

            });

            $('.groupPackHead').off('tap');
            $('.groupPackHead').on('tap', function() {
                thisElem = $(this);
                $(this).toggleClass('collapsed');
                if( $(this).hasClass('collapsed') ) {
                    $('#groupPack-'+$(this).data('num')).addClass('hiding');
                    setTimeout((function() {
                        $('#groupPack-'+$(thisElem).data('num')).css('display', 'none');
                    }), 450);
                } else {
                    $('#groupPack-'+$(this).data('num')).css('display', '');
                    $('#groupPack-'+$(this).data('num')).removeClass('hiding');
                }
            });

            $('.editTitle').off('tap');
            $('.editTitle').on('tap', function() {
                thisElem = $(this);
                $('#old_title').val( $(this).text() ).change();
                $('#new_title').val( $(this).text() ).change();
                $('#editTitle').modal('show');
            });

            $('#editTitle').modal('hide');
            $('#postTitle').removeClass('loadBtn').addClass('loadBtn');
        }

        $('#postTitle').on('tap', function() {
            $(this).removeClass('loadBtn');

            var url = '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'changeEventTitle'))?>.json';
            sendData = {
                old_title: $('#old_title').val(),
                new_title: $('#new_title').val()
            };

            $.post(url, sendData, function (response) {
                updateData();
            });
        })

        $('.control #typeList, .control #categoryList, .calandarPeriod #groupList').on('change', function() {
            updateData();
        });

        $('.control #timeList').on('change', function() {
            if($('#timeList').val() == '0') {
                $('.calandarPeriod .dateTime.date').show();
                updateData();
            } else {
                $('.calandarPeriod .dateTime.date').hide();
                $('#date-from').datetimepicker("reset");
                $('#date-to').datetimepicker("reset");
                updateData();
            }
        });

        $('#statistic-from-mirror, #statistic-to-mirror').on('change', function() {
            if( $('#statistic-from-mirror').val() != '' && $('#statistic-to-mirror').val() != '' && $('#timeList').val() == '0' ) {
                updateData(true);
            }
        })

        // Calendars
        $('#date-from').datetimepicker({
            format: '<?= $dateFormat?>',
            weekStart: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            language:"<?=$lang?>",
            linkField: 'statistic-from-mirror',
            linkFormat: 'yyyy-mm-dd'
        });
        $('#date-to').datetimepicker({
            format: '<?= $dateFormat?>',
            weekStart: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            language:"<?=$lang?>",
            linkField: 'statistic-to-mirror',
            linkFormat: 'yyyy-mm-dd'
        });

        initControls();
        updateData();
    });
</script>


























<div class="modal fade eventTypeModal" id="userEventModal">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="glyphicons circle_remove" onclick="closeEventPopup();"></span>
                <?=$this->Form->create('UserEvent', array('url' => array('controller' => 'Project', 'action' => 'addUserEvent')))?>
                    <div class="form-group noBorder">
                        <div class="nextLine">
                            <div id="eType" style="width: 100%; display: inline-block; margin: 0; padding: 0;">
                                <select id="UserEventType" name="data[UserEvent][type]">
                                    <option value="blank"><?=__('Event type')?></option>
                                    <option value="meet"><?=__('Meeting')?></option>
                                    <option value="mail"><?=__('Send email')?></option>
                                    <option value="call"><?=__('Call')?></option>
                                    <option value="conference"><?=__('Conference')?></option>
                                    <option value="sport"><?=__('Sport')?></option>
                                    <option value="task"><?=__('Task')?></option>
                                    <option value="purchase"><?=__('Purchase')?></option>
                                    <option value="entertain"><?=__('Entertainment')?></option>
                                    <option value="none"><?=__('Other')?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?=$this->Form->input('UserEvent.title', array('label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => __('Event title')))?>
                    </div>
                    <?=$this->Form->hidden('UserEvent.id')?>
                    <?=$this->Form->hidden('UserEvent.recipient_id')?>
                    <?=$this->Form->hidden('UserEvent.is_delayed')?>
                    <?=$this->Form->hidden('UserEvent.object_type')?>
                    <?=$this->Form->hidden('UserEvent.object_id')?>
                    <!--?=$this->Form->hidden('UserEvent.task_id')?-->

                    <div class="form-group noBorder">
                        <div class="nextLine">
                            <?=$this->Form->hidden('UserEvent.yearStart')?>
                            <div style="width: 200px; display: inline-block; margin: 0; padding: 0;">
                                <select name="data[UserEvent][monthStart]" id="monthStart">
                                    <option value="1"><?=__('January')?></option>
                                    <option value="2"><?=__('February')?></option>
                                    <option value="3"><?=__('March')?></option>
                                    <option value="4"><?=__('April')?></option>
                                    <option value="5"><?=__('May')?></option>
                                    <option value="6"><?=__('June')?></option>
                                    <option value="7"><?=__('July')?></option>
                                    <option value="8"><?=__('August')?></option>
                                    <option value="9"><?=__('September')?></option>
                                    <option value="10"><?=__('October')?></option>
                                    <option value="11"><?=__('November')?></option>
                                    <option value="12"><?=__('December')?></option>
                                </select>
                            </div>
                            <div style="width: 118px; display: inline-block; margin: 0 0 0 6px; padding: 0;">
                                <select name="data[UserEvent][dayStart]" id="dayStart">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                </select>
                            </div>
                        </div>
                        <div class="nextLine">
                            <div style="width: 95px; display: inline-block; margin: 0; padding: 0;">
                                <select name="data[UserEvent][timeStart]" id="timeStart">
                                <? if(Configure::read('Config.language') == 'rus') { ?>
                                    <option value="0">00</option>
                                    <option value="1">01</option>
                                    <option value="2">02</option>
                                    <option value="3">03</option>
                                    <option value="4">04</option>
                                    <option value="5">05</option>
                                    <option value="6">06</option>
                                    <option value="7">07</option>
                                    <option value="8">08</option>
                                    <option value="9">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                <? } else { ?>
                                    <option value="0">12 am</option>
                                    <option value="1">01 am</option>
                                    <option value="2">02 am</option>
                                    <option value="3">03 am</option>
                                    <option value="4">04 am</option>
                                    <option value="5">05 am</option>
                                    <option value="6">06 am</option>
                                    <option value="7">07 am</option>
                                    <option value="8">08 am</option>
                                    <option value="9">09 am</option>
                                    <option value="10">10 am</option>
                                    <option value="11">11 am</option>
                                    <option value="12">12 pm</option>
                                    <option value="13">01 pm</option>
                                    <option value="14">02 pm</option>
                                    <option value="15">03 pm</option>
                                    <option value="16">04 pm</option>
                                    <option value="17">05 pm</option>
                                    <option value="18">06 pm</option>
                                    <option value="19">07 pm</option>
                                    <option value="20">08 pm</option>
                                    <option value="21">09 pm</option>
                                    <option value="22">10 pm</option>
                                    <option value="23">11 pm</option>
                                <? } ?>
                                </select>
                            </div>
                            <div style="width: 95px; display: inline-block; margin: 0 0 0 6px; padding: 0;">
                                <select name="data[UserEvent][minuteStart]" id="minuteStart">
                                    <option value="0">00</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                            <div id="eDuration" style="width: 118px; display: inline-block; margin: 0 0 0 7px; padding: 0;">
                                <select name="data[UserEvent][duration]" id="timeDuration">
                                    <option value="0"><?=__('Period')?></option>
                                    <option value="15">15 <?=__('min')?></option>
                                    <option value="30">30 <?=__('min')?></option>
                                    <option value="60">1 <?=__('h')?></option>
                                    <option value="120">2 <?=__('h')?></option>
                                    <option value="180">3 <?=__('h')?></option>
                                    <option value="240">4 <?=__('h')?></option>
                                    <option value="480">8 <?=__('h')?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group noBorder empty" id="uList">
                        <div id="eventUserList" class="tokenfield"></div>
                        <?=$this->Form->hidden('list')?>
                    </div>

                    <div class="form-group">
                        <input type="text" id="userSearch" class="form-control" placeholder="<?=__('Select user')?>">
                    </div>

                    <div class="form-group noBorder">
                        <img src="/img/ajax_loader.gif" alt="" class="preloader" style="position: absolute; right: 10px;">
                        <div class="groupAccess clearfix" style="overflow: hidden;"></div>
                    </div>

                    <div class="form-group">
                        <?=$this->Form->input('UserEvent.descr', array('type' => 'textarea', 'label' => false, 'div' => false, 'placeholder' => __('Event description'), 'class' => 'form-control', 'onFocus' => "this.style.webkitTransform = 'translate3d(0px,-10000px,0)'; webkitRequestAnimationFrame(function() { this.style.webkitTransform = ''; }.bind(this))"))?>
                    </div>

                    <?=$this->Form->hidden('UserEvent.shared', array('value' => '1'))?>
                    <!--div class="form-group noBorder">
                        <div class="bigCheckBox" style="display: inline;">
                            <input name="data[UserEvent][shared]" id="UserEventShared" type="checkbox" class="checkboxStyle glyphicons ok_2" />
                            <span class="checkboxText"><?=__('share')?></span>
                        </div>
                    </div-->
                    <div id="saveEventButton" class="btn btn-primary loadBtn"><span><?=__('Save')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
                    <div id="removeEventButton" class="btn btn-default loadBtn pull-right" style="margin-left: 10px;"><span><?=__('Delete')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
                    <div id="delayEventButton" class="btn btn-default loadBtn pull-right"><span><?=__('Set aside')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
                    <div class="clearfix"></div>

                <?=$this->Form->end()?>
            </div>
        </div>
    </div>
</div>

<script type="text/x-tmpl" id="user-select">
{%
    var name = o.name;
    var id = o.id;
    var url = o.url;
%}
<div class="token" data-user-id="{%=id%}">
    <span class="name token-label"><img src="{%=url%}" alt="{%=name%}" style="height: 30px;">{%=name%}</span><a href="#" class="close" tabindex="-1">×</a>
</div>
</script>
