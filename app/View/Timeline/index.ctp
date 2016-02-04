<div id="mainContainer" class="timeLine gpuAccel"></div>
<div id="tempContainer" class="timeLine gpuAccel" style="position: absolute; visibility: hidden; display: block; z-index: -5000"></div>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<div class="hide" id="tpl-file-upload">
	<a href="#" class="item">
		<span class="filetype"></span>
		<div class="title"></div>
		<div class="progress">
			<div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-info"><span class="percentage"></span></div>
		</div>
	</a>
</div>

<div class="modal fade eventTypeModal" id="userEventModal">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div id="eventModalContent" class="modal-content eventModalContent">
                <span class="glyphicons circle_remove" onclick="Timeline.closeEventPopup();"></span>
                <?=$this->Form->create('UserEvent', array('url' => array('controller' => 'Project', 'action' => 'addUserEvent'), 'style' => 'width: 340px;'))?>
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
                                    <option value="pay"><?=__('Payment')?></option>
                                    <option value="none"><?=__('Other')?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->select('UserEvent.event_category_id', $aCategories, array('empty' => false)); ?>
                    </div>
                    <!--?=$this->Form->hidden('UserEvent.type')?-->
                    <div class="form-group">
                        <?=$this->Form->input('UserEvent.title', array('label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => __('Event title')))?>
                    </div>
                    <?=$this->Form->hidden('UserEvent.id')?>
                    <?=$this->Form->hidden('UserEvent.recipient_id')?>
                    <?=$this->Form->hidden('UserEvent.is_delayed')?>
                    <?=$this->Form->hidden('UserEvent.object_type')?>
                    <?=$this->Form->hidden('UserEvent.object_id')?>
                    <?//=$this->Form->hidden('UserEvent.event_category_id')?>
                    <?=$this->Form->hidden('UserEvent.external')?>
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
                            <div style="width: 120px; display: inline-block; margin: 0 0 0 6px; padding: 0;">
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
                    </div>

                    <div id="commonEventData">
                        <div class="form-group noBorder">
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
                                        <option value="00">00</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
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
                                        <option value="32">32</option>
                                        <option value="33">33</option>
                                        <option value="34">34</option>
                                        <option value="35">35</option>
                                        <option value="36">36</option>
                                        <option value="37">37</option>
                                        <option value="38">38</option>
                                        <option value="39">39</option>
                                        <option value="40">40</option>
                                        <option value="41">41</option>
                                        <option value="42">42</option>
                                        <option value="43">43</option>
                                        <option value="44">44</option>
                                        <option value="45">45</option>
                                        <option value="46">46</option>
                                        <option value="47">47</option>
                                        <option value="48">48</option>
                                        <option value="49">49</option>
                                        <option value="50">50</option>
                                        <option value="51">51</option>
                                        <option value="52">52</option>
                                        <option value="53">53</option>
                                        <option value="54">54</option>
                                        <option value="55">55</option>
                                        <option value="56">56</option>
                                        <option value="57">57</option>
                                        <option value="58">58</option>
                                        <option value="59">59</option>
                                    </select>
                                </div>
                                <div id="eDuration" style="width: 120px; display: inline-block; margin: 0 0 0 7px; padding: 0;">
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

                        <div class="form-group user-list-form-group-block">
                            <input type="text" id="userSearch" class="form-control" placeholder="<?=__('Select user')?>">

                            <div class="global-tascks-button" onclick="return false;"></div>
                        </div>

                        <div class="form-group noBorder">
                            <img src="/img/ajax_loader.gif" alt="" class="preloader" style="position: absolute; right: 10px; display: none">
                            <div class="groupAccess clearfix"></div>
                        </div>

                        <div class="form-group">
                            <?=$this->Form->input('UserEvent.descr', array('type' => 'textarea', 'label' => false, 'div' => false, 'placeholder' => __('Event description'), 'class' => 'form-control', 'onFocus' => "this.style.webkitTransform = 'translate3d(0px,-10000px,0)'; webkitRequestAnimationFrame(function() { this.style.webkitTransform = ''; }.bind(this))"))?>
                        </div>
                        <div class="form-group">
                            <?=$this->Form->input('UserEvent.price', array('type' => "text", 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => __('Price')))?>
                        </div>
                        <?=$this->Form->hidden('UserEvent.shared', array('value' => '1'))?>

                        <div class="form-group">
                            <?=$this->Form->input('UserEvent.place_name', array('label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => __('Event place')))?>
                        </div>
                        <?=$this->Form->hidden('UserEvent.place_coords')?>
                        <div id="map-canvas" style="width: 330px; height: 200px; margin-bottom: 20px; display: none;"></div>
                    </div>

                    <div id="paymentEventData" style="display: none;">
                        <div class="form-group">
                            <input name="data[UserEvent][amount]" class="form-control" placeholder="<?=__('Amount')?>" type="text" id="UserEventAmount"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 8 || event.charCode == 27 || event.charCode == 46 ">
                        </div>
                        <div class="form-group">
                            <?=$this->Form->input('UserEvent.finance_category', array('label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => __('Finance category')))?>
                        </div>
                        <div class="form-group noBorder">
                            <div class="nextLine">
                                <div style="width: 100%; display: inline-block; margin: 0; padding: 0;">
                                    <?=$this->Form->input('UserEvent.finance_project', array('label' => false, 'div' => false, 'options' => $aFinanceProjectOptions, 'empty' => __('Select finance project') ))?>
                                </div>
                            </div>
                            <div class="nextLine">
                                <div style="width: 100%; display: inline-block; margin: 0; padding: 0;">
                                    <?=$this->Form->input('UserEvent.finance_account', array('label' => false, 'div' => false, 'options' => array(), 'empty' => __('Select an account') ))?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="eventFilesAttach">
                    </div>

                    <div id="saveEventButton" class="btn btn-primary loadBtn"><span class="caption"><?=__('Save')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
                    <div id="removeEventButton" class="btn btn-default loadBtn pull-right" style="margin-left: 10px;"><span class="caption"><?=__('Delete')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
                    <div id="delayEventButton" class="btn btn-default loadBtn pull-right"><span class="caption"><?=__('Set aside')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
                    <div class="clearfix"></div>
                <?=$this->Form->end()?>
            </div>
        </div>
    </div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript">
	;(function($){
        $('#UserEventType').on('change', function(){
            var dCssBlock = $(this).val() == 'task' ? 'block' : 'none';
            $('#UserEventEventCategoryId').parent().parent().css('display', dCssBlock);
        });
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

	var iOS = /iPad|iPhone|iPod/.test( navigator.userAgent );
	var docFiles = ['.doc','.docx','.pdf','.xls','.xlsx','.txt'];

	var marker;
	var map;
	var geocoder = new google.maps.Geocoder();
	var mapInitialized = false;

	function initializeMap( location ) {
		mapInitialized = true;
		map = new google.maps.Map(document.getElementById('map-canvas'), {
			zoom: 13,
			center: location,
			disableDefaultUI: true
		});
		marker = undefined;
		google.maps.event.addListener(map, 'click', function(event) {
			placeMarker(event.latLng, true);
		  });
		google.maps.event.trigger(map, 'resize');
	}

	//Реверсивная геолокация
	function placeMarker(location, reverse) {
		if ( marker ) {
			marker.setPosition(location);
		} else {
			marker = new google.maps.Marker({
				position: location,
				map: map
			});
		}
		$('#UserEventPlaceCoords').val(location).change();
		if(reverse) {
			codeLatLng(location);
		}
	}

	function codeAddress(place) {
		if(place) {
			geocoder.geocode( { 'address': place}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if(!mapInitialized) {
						initializeMap(results[0].geometry.location);
					}
					map.setCenter(results[0].geometry.location);
					placeMarker(results[0].geometry.location, false);
				} else {
					alert('Geocode was not successful for the following reason: ' + status);
				}
			});
		}
	}

	function coordAddress(LatLng) {
		if(!mapInitialized) {
			initializeMap(LatLng);
		}
		map.setCenter(LatLng);
		placeMarker(LatLng, false);
	}

	function codeLatLng(latlng) {
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[1]) {
					$('#UserEventPlaceName').val(results[1].formatted_address);
				} else {
					alert('No results found');
				}
			} else {
				  alert('Geocoder failed due to: ' + status);
			}
		});
	}

	var clickDOM = '';
	var controlClicked = false;
	function is_touch_device() {
		return (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
	}

	var aMonths = <?=json_encode(array(__(' January'), __(' February'), __(' March'), __(' April'), __(' May'), __(' June'), __(' July'), __(' August'), __(' September'), __(' October'), __(' November'), __(' December')))?>;

	var aMonthsFull = <?=json_encode(array(__('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December')))?>;

	var aDays = <?=json_encode(array(__('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday')))?>;
	var todayDate, now;
	var startDay, startDate, locale;
	var currUser = <?=Hash::get($currUser, 'User.id')?>;

	<?php
	if(Configure::read('Config.language') == 'rus'){
		$lang = 'ru';
		$timeformat = '
		format:"hh:ii",';
		$dateformat = '
		format:"dd.mm.yyyy",';
	} else {
		$lang = 'en';
		$timeformat = '
		format:"HH:iip",
		showMeridian: "day",';
		$dateformat = '
		format:"mm/dd/yyyy",
		showMeridian: "day",';
	}
	?>

	var indexOf = function(needle) {
		if(typeof Array.prototype.indexOf === 'function') {
			indexOf = Array.prototype.indexOf;
		} else {
			indexOf = function(needle) {
				var i = -1, index = -1;

				for(i = 0; i < this.length; i++) {
					if(this[i] === needle) {
						index = i;
						break;
					}
				}
				return index;
			};
		}

		return indexOf.call(this, needle);
	};

	$(document).ready(function(){
		$('.jq-selectbox__select-text').css('width', '');
		/*
		$(window).on('resize', function() {
			$('.modal-content').css('margin-left', $(window).width()/2 - 145);
		});
		$('.modal-content').css('margin-left', $(window).width()/2 - 145);
		*/
		locale = '<?=Hash::get($currUser, 'User.lang')?>';
		todayDate = new Date;
		todayDate = todayDate.toSqlDate(); //
		todayDate = '<?=date('Y-m-d')?>';
		now = new Date(); //
		now = now.toSqlDate();
		now = Date.fromSqlDate('<?=date('Y-m-d H:i:s')?>');
		startDay = <?=-floor((strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', strtotime(Hash::get($currUser, 'User.created'))))) / DAY)?>;
		startDate = Date.fromSqlDate(todayDate).addDays(startDay).toSqlDate();

		$('#mainContainer').css('opacity', 0);

		var timelineData = <?php echo json_encode($aTimeline) ?>;

<?php if( isset($this->request->query['search']) ) { ?>
		Timeline.setSearch('<?=$this->request->query['search']?>');
		$('#searchInput').val('<?=$this->request->query['search']?>');
		$('.headerTimeline .btn-default.active').removeClass('active');
<?php } ?>

		//$.post(profileURL.timelineEvents, {
		//	date: '2016-02-06',
		//	date2: '2016-01-10',
		//	view: Timeline.VIEW_STATE_DAY,
		//	search: ''
		//},
		//function (result) {
			Timeline.init({
				canvas: $('#mainContainer').get(0),
				tempCanvas: $('#tempContainer').get(0),
	<?php if( isset($this->request->query['search']) ) { ?>
				topDay: 1,
				bottomDay: -1,
	<?php } else { ?>
				topDay: <?=$topDay?>,
				bottomDay: <?=$bottomDay?>,
	<?php } ?>
				loadPeriodSmall: <?=Configure::read('timeline.loadPeriod.small')?>,
				loadPeriodNormal: <?=Configure::read('timeline.loadPeriod.normal')?>,
				updateTime: <?=Configure::read('timeline.updateTime')?>,
				language: locale,
				viewState: Timeline.VIEW_STATE_DAY
			}, timelineData);
			//timelineData = result;

			setTimeout(function(){
				if(Timeline.search.length == 0) {
					Timeline.scrollCurrentDay();
				} else {
					Timeline.scrollTarget('.searchHeader:first');
				}
				$('#mainContainer').animate({ opacity: 1    }, 2000, 'easeOutExpo');
			}, 1000);

		//});

		$( window ).resize(function() {
			$('.absoluteWrap').height( $(window).height() );
		});

		Timeline.initEventPopup();

		$('#showDay').on('click', function() {
			Timeline.setSearch('');
			$('#searchInput').val('');
			if( !$('#showDay').hasClass('active') ) {
				$('#showDay').removeClass('active').addClass('active');
				$('#showWeek').removeClass('active');
				$('#showMonth').removeClass('active');
				$('#showYear').removeClass('active');
				Timeline.setViewState(Timeline.VIEW_STATE_DAY);
			} else {
				if( $('.calendar.currentDate').length == 0 ) {
					Timeline.setViewState(Timeline.VIEW_STATE_DAY);
				} else {
					Timeline.scrollCurrentDayAnimated();
				}
			}
		});

		$('#showWeek').on('click', function() {
			Timeline.setSearch('');
			$('#searchInput').val('');
			if( !$('#showWeek').hasClass('active') ) {
				$('#showWeek').removeClass('active').addClass('active');
				$('#showDay').removeClass('active');
				$('#showMonth').removeClass('active');
				$('#showYear').removeClass('active');
				Timeline.setViewState(Timeline.VIEW_STATE_WEEK);
			} else {
				if( $('.calendar.currentDate').length == 0 ) {
					Timeline.setViewState(Timeline.VIEW_STATE_WEEK);
				} else {
					Timeline.scrollCurrentDayAnimated();
				}
			}
		});

		$('#showMonth').on('click', function() {
			Timeline.setSearch('');
			$('#searchInput').val('');
			if( !$('#showMonth').hasClass('active') ) {
				$('#showMonth').removeClass('active').addClass('active');
				$('#showDay').removeClass('active');
				$('#showWeek').removeClass('active');
				$('#showYear').removeClass('active');
				Timeline.setViewState(Timeline.VIEW_STATE_MONTH);
			} else {
				Timeline.scrollCurrentDayAnimated();
			}
		});

		$('#showYear').on('click', function() {
			Timeline.setSearch('');
			$('#searchInput').val('');
			if( !$('#showYear').hasClass('active') ) {
				$('#showYear').removeClass('active').addClass('active');
				$('#showDay').removeClass('active');
				$('#showWeek').removeClass('active');
				$('#showMonth').removeClass('active');
				Timeline.setViewState(Timeline.VIEW_STATE_YEAR);
			} else {
				Timeline.scrollCurrentDayAnimated();
			}
		});

		$('.event .ava, .event .userLink, .event .taskLink').on('click', function(e) {
			e.stopPropagation();
		});

		setTimeout(function(){
			Timeline.addDrag();
		}, 500);

	});

	$('.absoluteWrap').height( $(window).height() );

	var bindList = <?=$aBindOptions?>;
	$('#UserEventDescr').autocomplete({
		lookup: bindList,
		groupBy: 'category',
		onSelect: function (suggestion) {
			//$('#UserEventTaskId').val(suggestion.data);
			$('#UserEventObjectType').val(suggestion.data.type);
			$('#UserEventObjectId').val(suggestion.data.id);
		}
	});
	  var bindListCat = <?=$aBindCategories?>;
	$('#UserEventTitle').autocomplete({
		lookup: bindListCat,
		groupBy: 'category',
		onSelect: function (suggestion) {
			//$('#UserEventTaskId').val(suggestion.data);
			$('#UserEventEventCategoryId').val(suggestion.data.id);
		}
	});

	$('#userSearch').keypress(function() {
		$('.eventModalContent .preloader').show();
	});

	$('#userSearch').donetyping(function() {
		var postData = { q: $('#userSearch').val() };
		$.post( "<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'userList'))?>", postData, ( function (data) {
			$(".eventModalContent .groupAccess").html(data);

			var itemCount = $('.item', '.eventModalContent' ).length;

			if( itemCount == 1 ) {
				$('.eventModalContent form .groupAccess').removeClass('one').removeClass('more').addClass('one');
			} else if( itemCount > 1 ) {
				$('.eventModalContent form .groupAccess').removeClass('one').removeClass('more').addClass('more');
			} else if( itemCount == 0 ) {
				$('.eventModalContent form .groupAccess').removeClass('one').removeClass('more');
			}
		}));
		$('.eventModalContent .preloader').hide();
	});

	$('#UserEventPlaceName').donetyping(function() {
		if( $(this).val().length > 2 ) {
			$('#map-canvas').show();
			codeAddress( $(this).val() );
		} else {
			$('#map-canvas').hide();
			$('#UserEventPlaceCoords').val('').change();
		}
	});

	$('#searchInput').donetyping(function() {
		var searchLine = '';
		if( $(this).val().length > 0 ) {
			searchLine = $(this).val();
			$('.headerTimeline .btn-default.active').removeClass('active');
			Timeline.setSearch(searchLine);
			Timeline.setViewState(Timeline.VIEW_STATE_DAY);
		} else {
			Timeline.setSearch('');
			$('.headerTimeline #showDay').removeClass('active').addClass('active');
			Timeline.setViewState(Timeline.VIEW_STATE_DAY);
		}
	});

	$(document).on('click', '.user.item', function(e) {
		// выбор пользователей в попапе
		$(this).addClass('selected');
		if( $(".token[data-user-id='"+$(this).data('user_id')+"']").length == 0 ) {
			$('#UserEventRecipientId').val( $(this).data('user_id') );

			var html = tmpl('user-select', {id: $(this).data('user_id'), name: $(".name", this).text(), url: $('img', this).attr('src')});

			$('#eventUserList').append(html);
			if( $('#uList .token').length == 1 ) {
				$('#uList').removeClass('empty');
			}
			$('.eventModalContent form .groupAccess').removeClass('one').removeClass('more');
			$('#userSearch').val('');
		}
	});

	$('.tokenfield').on('click', '.token a.close', function(e) {
		$(this).parents('.token').remove();
		if( $('#uList .token').length == 0 ) {
			$('#uList').addClass('empty');
		}
		$(".item[data-user_id='"+$(this).parents('.token').data('user-id')+"']").removeClass('selected');
	});

	$('#userEventModal select').styler();

	$('.modal').on('hide.bs.modal', function (e) {
		$('body').css("position","static");
		$('body, html').off('touchmove scroll');
	});

	$('#UserEventDescr').autosize({append:false});
	$('#UserEventDescr').on('keyup copy cut paste change', function() {
		$('#UserEventDescr').trigger('autosize.resize');
	});


	eventIsValid = function () {
		var allow = true;

		$('#UserEventTitle').popover('destroy');
		$('#UserEventType').popover('destroy');
		$('#UserEventAmount').popover('destroy');
		$('#UserEventFinanceCategory').popover('destroy');
		$('#UserEventFinanceProject').popover('destroy');

		side = $('#UserEventTitle').parents('.leftSide').length == 0 ? 'left' : 'right';

		if( $('#UserEventTitle').val().length < 1 ) {
			setTimeout( function() {
				$('#UserEventTitle').popover({ toggle: 'popover', placement: side, content: "<?=__('Title can not be empty')?>" });
				$('#UserEventTitle').popover('show');
			}, 300);
			allow = false;
		}

		if( $('#UserEventType').val() == 'blank' ) {
			setTimeout( function() {
				$('#UserEventType').popover({ toggle: 'popover', placement: side, content: "<?=__('Event type not selected')?>" });
				$('#UserEventType').popover('show');
			}, 300);
			allow = false;
		} else if(['pay','purchase'].indexOf($('#UserEventType').val())+1) {
			if($('#UserEventAmount').val() == '') {
				$('#UserEventAmount').popover({ toggle: 'popover', placement: side, content: "<?=__('Amount can not be empty')?>" });
				$('#UserEventAmount').popover('show');
				allow = false;
			}
			if($('#UserEventFinanceCategory').val() == '') {
				$('#UserEventFinanceCategory').popover({ toggle: 'popover', placement: side, content: "<?=__('Category can not be empty')?>" });
				$('#UserEventFinanceCategory').popover('show');
				allow = false;
			}
			if($('#UserEventFinanceProject').val() == null || $('#UserEventFinanceProject').val().length == 0) {
				$('#UserEventFinanceProject').popover({ toggle: 'popover', placement: side, content: "<?=__('Project can not be empty')?>" });
				$('#UserEventFinanceProject').popover('show');
				allow = false;
			}
			if($('#UserEventFinanceAccount').val() == null || $('#UserEventFinanceAccount').val().length == 0) {
				$('#UserEventFinanceAccount').popover({ toggle: 'popover', placement: side, content: "<?=__('Account can not be empty')?>" });
				$('#UserEventFinanceAccount').popover('show');
				allow = false;
			}
		}

		if( !allow ) {
			$('.eventModalContent #saveEventButton').removeClass('loadBtn').addClass('loadBtn');
			$('.eventModalContent #saveEventButton').removeClass('disabled');

			$('.eventModalContent #delayEventButton').removeClass('loadBtn').addClass('loadBtn');
			$('.eventModalContent #delayEventButton').removeClass('disabled');
		}

		return allow;
	};

	$('#UserEventTitle, #UserEventType, #UserEventAmount, #UserEventFinanceCategory, #UserEventFinanceProject').on('focus', function(){
		$(this).popover('destroy');
	})

	var eventAccounts = <?=$aProjectAccounts?>;

	$('#UserEventFinanceProject').on('change', function(){
		var name, select, option;

	// Get the raw DOM object for the select box
		select = document.getElementById('UserEventFinanceAccount');

	// Clear the old options
		select.options.length = 0;

		var data = eventAccounts[$(this).val()];
	// Load the new options
		select.options.add(new Option('<?=__('Select an account')?>', '', true, true));
		for (name in data) {
			if (data.hasOwnProperty(name)) {
				select.options.add(new Option(data[name], name));
			  }
		}
		$(select).trigger('refresh');
		$(select).trigger('change');
	})

	$('#UserEventType').on('change', function(){
		if(['pay','purchase'].indexOf($(this).val()) +1) {
			$('#paymentEventData').show();
			$('#commonEventData').hide();
		} else {
			$('#paymentEventData').hide();
			$('#commonEventData').show();
		}
	})

	cloudSave = function(id) {
		if(confirm('<?=__('Do you want to save this file to your cloud?')?>')) {
			$.post( profileURL.saveToCloud, {file_id: id}, ( function (response) {
				if(response.status != 'OK') {
					alert('Error while saving file: '+response.data);
				}
			}));
		}
	}

</script>

<style type="text/css">

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

	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) and (-webkit-min-device-pixel-ratio: 2) {
		.b-order-bottom-device { position: absolute; bottom: 60px!important; }
	}

	.autocomplete-suggestion { margin: 0; padding: 0; width: 100%; color: #231F20; min-height: 18px; padding: 5px 10px 6px; cursor: pointer }
	.autocomplete-selected { background-color: #22B5AE; color: #FFF; }
	.autocomplete-suggestions strong { font-weight: 600; color: #3399FF; }
	.autocomplete-selected strong { color: #FFF; }
	.autocomplete-group { padding: 2px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

	.eventModalContent form .groupAccess {
		-webkit-transition: all .5s ease-out;
		   -moz-transition: all .5s ease-out;
			-ms-transition: all .5s ease-out;
			 -o-transition: all .5s ease-out;
				transition: all .5s ease-out;
		max-height: 0;
		overflow: hidden; }

	.eventModalContent form .groupAccess.one { max-height: 80px; }
	.eventModalContent form .groupAccess.more { max-height: 160px; }

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

	.main-panel.hideIpad { left: -100px }
	.main-panel-before.hideIpad { left: -100px!important }
	body > .wrapper-container.hideIpad { padding-left: 0 }
	.headerTimeline.hideIpad { top: -100px }

	.rightSide .event.eng .articleText { padding-left: 10px }
	.leftSide .event.eng .articleText { padding-right: 10px }

	.rightSide .event.eng .additionalBlock .ava { margin-left: 58px!important }
	.leftSide .event.eng .additionalBlock .ava { margin-right: 58px!important }

	.rightSide .event.eng .additionalBlock .taskFile .ava { margin-left: 15px!important }
	.leftSide .event.eng .additionalBlock .taskFile .ava { margin-right: 84px!important }
	.rightSide .event.eng .additionalBlock .taskFile .filetype { margin-left: 15px!important }
	.leftSide .event.eng .additionalBlock .taskFile .filetype { margin-right: 78px!important }
	.rightSide .event.eng .additionalBlock .cloudSave { left: -60px!important }
	.leftSide .event.eng .additionalBlock .cloudSave { right: -60px!important }

	.leftSide .event.eng .text { padding-right: 15px }
	.rightSide .event.eng .text { padding-left: 15px }

	.event.eng .chatText { margin-left: 122px!important; }

	@media (max-width: 767px) {
		.event.eng .chatText {
			margin-left: 0!important;
		}
	}

	div.nicescroll-rails { z-index: 500!important; }

	.absoluteWrap {
		overflow: hidden;
		position: absolute;
		width: 100%;
		left:0;
		right:0;
	}

	.main-panel{
		position: absolute;
		height: 100%;
		width: 90px;
		-webkit-overflow-scrolling: touch;
		/*overflow: auto;*/
		float: left;
		z-index: 10;
		background: transparent!important;
	}

	.main-panel-block{ position: absolute; width: 45px; }
	#mainContainer{ width: 100%!important; }
	.headerTimeline{ z-index: 5; }
	.wrapper-container{ position: absolute; height: 100%; width: 100%; overflow-x: hidden; overflow-y: auto; }
	#userEventModal{ z-index: 500000; }
	.gpuAccel { -webkit-transform: translate3d(0, 0, 0); }

	/*.jq-selectbox__dropdown ul { max-height: 372px!important; }*/

	.joinBlock.lastArticle .title { font-weight: 100!important;    text-decoration: none; }
	.joinBlock.lastArticle { padding: 9px!important; margin-bottom: 10px; }

	.lastArticle .articleText { margin-left: 65px!important; }
	.lastArticle .ava { margin-left: 0!important; }

	.token { overflow: hidden; position: relative; line-height: 100%; width: 159px; }
	.token img { position: absolute; top: 0; left: 0; height: 100%; padding-right: 5px; }
	.token .token-label { padding-left: 35px!important; max-width: 131px; }
	.token a.close { position: absolute!important; top: 0!important; right: 0!important; }
	.tokenfield .token:nth-child(2n) { margin-right: 0!important; }

	.eventModalContent { z-index: 9999; }
	.leftSide .eventModalContent { left: 20%; }
	.rightSide .eventModalContent { right: 20%; }

	@media (max-width: 767px) {
		.leftSide .eventModalContent, .rightSide .eventModalContent { right: 0; left: 0; background: white; }
	}

	/* ----- Event-selector ----- */

    .event-select {
      position: relative;
      display: inline-block;
      padding-top: 100px;
      padding-left: 100px;
      width: 315px;
      height: 315px;
      box-sizing: border-box;
      font-size: 20px;
      text-align: left;
      transition: 800ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -webkit-transition: 800ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -o-transition: 800ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -moz-transition: 800ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      transform-origin: top;
      transition-delay: 200ms;
        z-index: 9999;
    }

	.event-select.collapsed {
	  /*transform: translate3d(0, -80px, 0) scale(0.8);
	  /*transition-duration: 400ms;*/
	  opacity: 0;
	}

	.leftSide .event-select {
	  float: right;
	  margin-left: -190px;
	}

	.rightSide .event-select {
	  float: left;
	  margin-right: -190px;
	}

	@media (max-width: 767px) {
		.leftSide .event-select, .rightSide .event-select { float: none; margin: 0 auto; padding-left: 170px; /*background: white;*/ }
	}


	/* ----- Event-selector ----- */

    .event-select {
      position: relative;
      display: inline-block;
      padding-top: 100px;
      padding-left: 100px;
      width: 260px;
      height: 260px;
      box-sizing: border-box;
      font-size: 20px;
      text-align: left;
      transition: 800ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -webkit-transition: 800ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -o-transition: 800ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -moz-transition: 800ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      transform-origin: top;
    /*  -ms-transform-origin: top;
      -webkit-transform-origin: top;
      -o-transform-origin: top;
      -moz-transform-origin: top;*/
      transition-delay: 200ms;
      -webkit-transition-delay: 200ms;
      -o-transition-delay: 200ms;
      -moz-transition-delay: 200ms;
    }

	.event-select.collapsed {
	/*  transform: translate3d(0, -80px, 0) scale(0.8);
	  -ms-transform: translate3d(0, -80px, 0) scale(0.8);
	  -webkit-transform: translate3d(0, -80px, 0) scale(0.8);
	  -o-transform: translate3d(0, -80px, 0) scale(0.8);
	  -moz-transform: translate3d(0, -80px, 0) scale(0.8);*/
	  transition-duration: 400ms;
	  -webkit-transition-duration: 400ms;
	  -o-transition-duration: 400ms;
	  -moz-transition-duration: 400ms;
	  opacity: 0;
	}

	.leftSide .event-select {
	  float: right;
	  margin-left: -190px;
	}

	.rightSide .event-select {
	  float: left;
	  margin-right: -190px;
	}

	@media (max-width: 767px) {
		.leftSide .event-select, .rightSide .event-select { float: none; margin: 0 auto; padding-left: 170px; /*background: white;*/ }
	}

	/* ----- CENTRAL BUTTON ----- */

	/* checkbox */
	.event-select .menu-open {
	  display: none;
	}

    .event-select .menu-open-button {
      position: absolute;
      width: 60px;
      height: 60px;
      margin-left: 0px;


      background: #fbba00;
      border-radius: 100%;
      color: white;
      text-align: center;
      line-height: 60px;
      border: 0px solid #fbba00;
      /*transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);*/
      transition-duration: 400ms;
      -webkit-transition-duration: 400ms;
      -o-transition-duration: 400ms;
      -moz-transition-duration: 400ms;
      transform: scale(1, 1) translate3d(0, 0, 0);
      -ms-transform: scale(1, 1) translate3d(0, 0, 0);
      -webkit-transform: scale(1, 1) translate3d(0, 0, 0);
      -o-transform: scale(1, 1) translate3d(0, 0, 0);
      -moz-transform: scale(1, 1) translate3d(0, 0, 0);
      box-sizing: border-box;
      z-index: 2;
      cursor: pointer;
    }

    .event-select .menu-open-button:hover {
      transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      -ms-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      -webkit-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      -o-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      -moz-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      border: 30px solid #fbba00;
    }

    .event-select .menu-open:checked + .menu-open-button {
      transition-timing-function: linear;
      -webkit-transition-timing-function: linear;
      -o-transition-timing-function: linear;
      -moz-transition-timing-function: linear;
      transition-duration: 200ms;
      -webkit-transition-duration: 200ms;
      -o-transition-duration: 200ms;
      -moz-transition-duration: 200ms;
      transition-delay: 400ms;
      -webkit-transition-delay: 400ms;
      -o-transition-delay: 400ms;
      -moz-transition-delay: 400ms;
      transform: scale(1, 1) translate3d(0, 0, 0);
      -ms-transform: scale(1, 1) translate3d(0, 0, 0);
      -webkit-transform: scale(1, 1) translate3d(0, 0, 0);
      -o-transform: scale(1, 1) translate3d(0, 0, 0);
      -moz-transform: scale(1, 1) translate3d(0, 0, 0);
      border: 30px solid #fbba00;
      box-shadow: 0 0px 4px 2px rgba(0, 0, 0, .25);
      margin: 0;
    }

	/* ----- MENU ICON ----- */


    .event-select .hamburger {
      width: 20px;
      height: 3px;
      background: white;
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -1.5px;
      transition: transform 200ms;
      -webkit-transition: transform 200ms;
      -o-transition: transform 200ms;
      -moz-transition: transform 200ms;
    }

    .event-select .hamburger-1 {
      transform: translate3d(0, -6px, 0);
      -ms-transform: translate3d(0, -6px, 0);
      -webkit-transform: translate3d(0, -6px, 0);
      -o-transform: translate3d(0, -6px, 0);
      -moz-transform: translate3d(0, -6px, 0);
    }

    .event-select .hamburger-2 {
      transform: translate3d(0, 0, 0);
      -ms-transform: translate3d(0, 0, 0);
      -webkit-transform: translate3d(0, 0, 0);
      -o-transform: translate3d(0, 0, 0);
      -moz-transform: translate3d(0, 0, 0);
    }

    .event-select .hamburger-3 {
      transform: translate3d(0, 6px, 0);
      -ms-transform: translate3d(0, 6px, 0);
      -webkit-transform: translate3d(0, 6px, 0);
      -o-transform: translate3d(0, 6px, 0);
      -moz-transform: translate3d(0, 6px, 0);
    }

	.event-select .menu-open:checked + .menu-open-button .hamburger-1 {
	  transform: translate3d(0, 0, 0) rotate(45deg);
	  -ms-transform: translate3d(0, 0, 0) rotate(45deg);
	  -webkit-transform: translate3d(0, 0, 0) rotate(45deg);
	  -o-transform: translate3d(0, 0, 0) rotate(45deg);
	  -moz-transform: translate3d(0, 0, 0) rotate(45deg);
	}
	.event-select .menu-open:checked + .menu-open-button .hamburger-2 {
	  transform: translate3d(0, 0, 0) scale(0.1, 1);
	  -ms-transform: translate3d(0, 0, 0) scale(0.1, 1);
	  -webkit-transform: translate3d(0, 0, 0) scale(0.1, 1);
	  -o-transform: translate3d(0, 0, 0) scale(0.1, 1);
	  -moz-transform: translate3d(0, 0, 0) scale(0.1, 1);
	}
	.event-select .menu-open:checked + .menu-open-button .hamburger-3 {
	  transform: translate3d(0, 0, 0) rotate(-45deg);
	  -ms-transform: translate3d(0, 0, 0) rotate(-45deg);
	  -webkit-transform: translate3d(0, 0, 0) rotate(-45deg);
	  -o-transform: translate3d(0, 0, 0) rotate(-45deg);
	  -moz-transform: translate3d(0, 0, 0) rotate(-45deg);
	}

	/* ----- SELECTABLES ----- */

    .event-select .menu-item {
      border-radius: 100%;
      width: 60px;
      height: 60px;
      position: absolute;
      transform: translate3d(0, 0, 0);
      -ms-transform: translate3d(0, 0, 0);
      -webkit-transform: translate3d(0, 0, 0);
      -o-transform: translate3d(0, 0, 0);
      -moz-transform: translate3d(0, 0, 0);
      border: 0px solid #00B6AF;
      transition-duration: 310ms;
      -webkit-transition-duration: 310ms;
      -o-transition-duration: 310ms;
      -moz-transition-duration: 310ms;
    }

    .event-select .menu-open:checked ~ .menu-item {
      transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
      -webkit-transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
      -o-transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
      -moz-transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
    }
    .event-select .menu-open:checked ~ .menu-item:nth-child(3) {
      transition-delay: 80ms;
      -webkit-transition-delay: 80ms;
      -o-transition-delay: 80ms;
      -moz-transition-delay: 80ms;
            transform: translate3d(0px, -100px, 0);
            -ms-transform: translate3d(0px, -100px, 0);
            -webkit-transform: translate3d(0px, -100px, 0);
            -o-transform: translate3d(0px, -100px, 0);
            -moz-transform: translate3d(0px, -100px, 0);
    }
    .event-select .menu-open:checked ~ .menu-item:nth-child(4) {
      transition-delay: 120ms;
      -webkit-transition-delay: 120ms;
      -o-transition-delay: 120ms;
      -moz-transition-delay: 120ms;
            transform: translate3d(70px, -70px, 0);
            -ms-transform: translate3d(70px, -70px, 0);
            -webkit-transform: translate3d(70px, -70px, 0);
            -o-transform: translate3d(70px, -70px, 0);
            -moz-transform: translate3d(70px, -70px, 0);
    }
    .event-select .menu-open:checked ~ .menu-item:nth-child(5) {
      transition-delay: 160ms;
      -webkit-transition-delay: 160ms;
      -o-transition-delay: 160ms;
      -moz-transition-delay: 160ms;
                transform: translate3d(100px, 0px, 0);
                -ms-transform: translate3d(100px, 0px, 0);
                -webkit-transform: translate3d(100px, 0px, 0);
                -o-transform: translate3d(100px, 0px, 0);
                -moz-transform: translate3d(100px, 0px, 0);
    }
    .event-select .menu-open:checked ~ .menu-item:nth-child(6) {
      transition-delay: 200ms;
      -webkit-transition-delay: 200ms;
      -o-transition-delay: 200ms;
      -moz-transition-delay: 200ms;
      transform: translate3d(70px, 70px, 0);
      -ms-transform: translate3d(70px, 70px, 0);
      -webkit-transform: translate3d(70px, 70px, 0);
      -o-transform: translate3d(70px, 70px, 0);
      -moz-transform: translate3d(70px, 70px, 0);
    }
    .event-select .menu-open:checked ~ .menu-item:nth-child(7) {
      transition-delay: 240ms;
      -webkit-transition-delay: 240ms;
      -o-transition-delay: 240ms;
      -moz-transition-delay: 240ms;
      transform: translate3d(0px, 100px, 0);
      -ms-transform: translate3d(0px, 100px, 0);
      -webkit-transform: translate3d(0px, 100px, 0);
      -o-transform: translate3d(0px, 100px, 0);
      -moz-transform: translate3d(0px, 100px, 0);
    }
    .event-select .menu-open:checked ~ .menu-item:nth-child(8) {
      transition-delay: 280ms;
      -webkit-transition-delay: 280ms;
      -o-transition-delay: 280ms;
      -moz-transition-delay: 280ms;
            transform: translate3d(-70px, 70px, 0);
            -ms-transform: translate3d(-70px, 70px, 0);
            -webkit-transform: translate3d(-70px, 70px, 0);
            -o-transform: translate3d(-70px, 70px, 0);
            -moz-transform: translate3d(-70px, 70px, 0);
    }
    .event-select .menu-open:checked ~ .menu-item:nth-child(9) {
      transition-delay: 320ms;
      -webkit-transition-delay: 320ms;
      -o-transition-delay: 320ms;
      -moz-transition-delay: 320ms;
            transform: translate3d(-100px, 0px, 0);
            -ms-transform: translate3d(-100px, 0px, 0);
            -webkit-transform: translate3d(-100px, 0px, 0);
            -o-transform: translate3d(-100px, 0px, 0);
            -moz-transform: translate3d(-100px, 0px, 0);
    }

	.container-fluid .periodItem .event-select .menu-inner-item.selected {

		background-color: #fff;
		-webkit-transition: 0ms linear;
		-moz-transition: 0ms linear;
		-ms-transition: 0ms linear;
		-o-transition: 0ms linear;
		transition: 0ms linear;
	}
	.user-list-item.ui-droppable, .user-list-item img.ui-droppable {
		-webkit-transition: 200ms linear;
		-moz-transition: 200ms linear;
		-ms-transition: 200ms linear;
		-o-transition: 200ms linear;
		transition: 200ms linear;
	}
	.user-list-item.ui-droppable.ui-hover, .user-list-item img.ui-droppable.ui-hover {
		opacity: 0;
	}

    .event-select .menu-open:checked ~ .menu-item:nth-child(10) {
        transition-delay: 360ms;
        -webkit-transition-delay: 360ms;
        -o-transition-delay: 360ms;
        -moz-transition-delay: 360ms;
            transform: translate3d(-70px, -70px, 0);
            -ms-transform: translate3d(-70px, -70px, 0);
            -webkit-transform: translate3d(-70px, -70px, 0);
            -o-transform: translate3d(-70px, -70px, 0);
            -moz-transform: translate3d(-70px, -70px, 0);
    }

	/*
	.event-select .menu-open:checked ~ .menu-item:nth-child(11) {
	  transition-delay: 400ms;
	  transform: translate3d(-39.15105px, -108.13045px, 0);
	}
	*/
    .event-select .menu-inner-item {
      background-color: #fff;
      border-radius: 100%;
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      color: #00B6AF;
      text-align: center;
      line-height: 48px;
      box-sizing: border-box;
      border: 2px solid #00B6AF;
      transition: box-shadow 0.2s ease;
    }

	.event-select .menu-inner-item:hover {
	  box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .25);
	}


	.event-select .menu-inner-item.selected {
	  transition: 200ms linear;
	  background-color: #fff;
	}

	.event-select .menu-inner-item i.glyphicons {
		position: absolute;
		font-size: 24px;
		top: -3px;
		left: 24px;
	}

	.event-select .menu-inner-item i.glyphicons:before {
		margin: 0;
	}


	@media only screen and (device-width: 768px){
    .event-select {
      position: relative;
      display: inline-block;
      padding-top: 100px;
      padding-left: 100px;
      width: 315px;
      height: 315px;
      box-sizing: border-box;
      font-size: 20px;
      text-align: left;
      transition: 10ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -webkit-transition: 10ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -o-transition: 10ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      -moz-transition: 10ms all cubic-bezier(0.175, 0.885, 0.32, 1.0);
      transform-origin: top;
    /*  -ms-transform-origin: top;
      -webkit-transform-origin: top;
      -o-transform-origin: top;
      -moz-transform-origin: top;*/
      transition-delay: 10ms;
      -webkit-transition-delay: 10ms;
      -o-transition-delay: 10ms;
      -moz-transition-delay: 10ms;
    }

	.event-select.collapsed {
	/*  transform: translate3d(0, -80px, 0) scale(0.8);
	  -ms-transform: translate3d(0, -80px, 0) scale(0.8);
	  -webkit-transform: translate3d(0, -80px, 0) scale(0.8);
	  -o-transform: translate3d(0, -80px, 0) scale(0.8);
	  -moz-transform: translate3d(0, -80px, 0) scale(0.8);*/
	  transition-duration: 0ms;
	  -webkit-transition-duration: 0ms;
	  -o-transition-duration: 0ms;
	  -moz-transition-duration: 0ms;
	  opacity: 0;
	}
    .event-select .menu-open-button {
      position: absolute;
      width: 60px;
      height: 60px;
      margin-left: 0px;

      background: #818181;
      border-radius: 100%;
      color: white;
      text-align: center;
      line-height: 60px;
      border: 0px solid #00B6AF;
      /*transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);*/
      transition-duration: 0ms;
      -webkit-transition-duration: 0ms;
      -o-transition-duration: 0ms;
      -moz-transition-duration: 0ms;
      transform: scale(1, 1) translate3d(0, 0, 0);
      -ms-transform: scale(1, 1) translate3d(0, 0, 0);
      -webkit-transform: scale(1, 1) translate3d(0, 0, 0);
      -o-transform: scale(1, 1) translate3d(0, 0, 0);
      -moz-transform: scale(1, 1) translate3d(0, 0, 0);
      box-sizing: border-box;
      z-index: 2;
      cursor: pointer;
    }

    .event-select .menu-open-button:hover {
      transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      -ms-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      -webkit-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      -o-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      -moz-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      border: 30px solid #fbba00;
    }

    .event-select .menu-open:checked + .menu-open-button {
      transition-timing-function: linear;
      -webkit-transition-timing-function: linear;
      -o-transition-timing-function: linear;
      -moz-transition-timing-function: linear;
      transition-duration: 0ms;
      -webkit-transition-duration: 0ms;
      -o-transition-duration: 0ms;
      -moz-transition-duration: 0ms;
      transition-delay: 0ms;
      -webkit-transition-delay: 0ms;
      -o-transition-delay: 0ms;
      -moz-transition-delay: 0ms;
      transform: scale(1, 1) translate3d(0, 0, 0);
      -ms-transform: scale(1, 1) translate3d(0, 0, 0);
      -webkit-transform: scale(1, 1) translate3d(0, 0, 0);
      -o-transform: scale(1, 1) translate3d(0, 0, 0);
      -moz-transform: scale(1, 1) translate3d(0, 0, 0);
      border: 30px solid #fbba00;
      box-shadow: 0 0px 4px 2px rgba(0, 0, 0, .25);
      margin: 0;
    }

	/* ----- MENU ICON ----- */


    .event-select .hamburger {
      width: 20px;
      height: 3px;
      background: white;
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -1.5px;
      transition: transform 0ms;
      -webkit-transition: transform 0ms;
      -o-transition: transform 0ms;
      -moz-transition: transform 0ms;
    }

    .event-select .hamburger-1 {
      transform: translate3d(0, -6px, 0);
      -ms-transform: translate3d(0, -6px, 0);
      -webkit-transform: translate3d(0, -6px, 0);
      -o-transform: translate3d(0, -6px, 0);
      -moz-transform: translate3d(0, -6px, 0);
    }

    .event-select .hamburger-2 {
      transform: translate3d(0, 0, 0);
      -ms-transform: translate3d(0, 0, 0);
      -webkit-transform: translate3d(0, 0, 0);
      -o-transform: translate3d(0, 0, 0);
      -moz-transform: translate3d(0, 0, 0);
    }

    .event-select .hamburger-3 {
      transform: translate3d(0, 6px, 0);
      -ms-transform: translate3d(0, 6px, 0);
      -webkit-transform: translate3d(0, 6px, 0);
      -o-transform: translate3d(0, 6px, 0);
      -moz-transform: translate3d(0, 6px, 0);
    }


	.event-select .menu-open:checked + .menu-open-button .hamburger-1 {
	  transform: translate3d(0, 0, 0) rotate(45deg);
	  -ms-transform: translate3d(0, 0, 0) rotate(45deg);
	  -webkit-transform: translate3d(0, 0, 0) rotate(45deg);
	  -o-transform: translate3d(0, 0, 0) rotate(45deg);
	  -moz-transform: translate3d(0, 0, 0) rotate(45deg);
	}
	.event-select .menu-open:checked + .menu-open-button .hamburger-2 {
	  transform: translate3d(0, 0, 0) scale(0.1, 1);
	  -ms-transform: translate3d(0, 0, 0) scale(0.1, 1);
	  -webkit-transform: translate3d(0, 0, 0) scale(0.1, 1);
	  -o-transform: translate3d(0, 0, 0) scale(0.1, 1);
	  -moz-transform: translate3d(0, 0, 0) scale(0.1, 1);
	}
	.event-select .menu-open:checked + .menu-open-button .hamburger-3 {
	  transform: translate3d(0, 0, 0) rotate(-45deg);
	  -ms-transform: translate3d(0, 0, 0) rotate(-45deg);
	  -webkit-transform: translate3d(0, 0, 0) rotate(-45deg);
	  -o-transform: translate3d(0, 0, 0) rotate(-45deg);
	  -moz-transform: translate3d(0, 0, 0) rotate(-45deg);
	}

	/* ----- SELECTABLES ----- */

    .event-select .menu-item {
      border-radius: 100%;
      width: 60px;
      height: 60px;
      position: absolute;
      transform: translate3d(0, 0, 0);
      -ms-transform: translate3d(0, 0, 0);
      -webkit-transform: translate3d(0, 0, 0);
      -o-transform: translate3d(0, 0, 0);
      -moz-transform: translate3d(0, 0, 0);
      border: 0px solid #00B6AF;
      transition-duration: 0ms;
      -webkit-transition-duration: 0ms;
      -o-transition-duration: 0ms;
      -moz-transition-duration: 0ms;
    }

    .event-select .menu-open:checked ~ .menu-item {
      transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
      -webkit-transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
      -o-transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
      -moz-transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
    }
        .event-select .menu-open:checked ~ .menu-item:nth-child(3) {
            transition-delay: 0ms;
            -webkit-transition-delay: 0ms;
            -o-transition-delay: 0ms;
            -moz-transition-delay: 0ms;
            transform: translate3d(0px, -100px, 0);
            -ms-transform: translate3d(0px, -100px, 0);
            -webkit-transform: translate3d(0px, -100px, 0);
            -o-transform: translate3d(0px, -100px, 0);
            -moz-transform: translate3d(0px, -100px, 0);
        }
        .event-select .menu-open:checked ~ .menu-item:nth-child(4) {
            transition-delay: 0ms;
            -webkit-transition-delay: 0ms;
            -o-transition-delay: 0ms;
            -moz-transition-delay: 0ms;
            transform: translate3d(70px, -70px, 0);
            -ms-transform: translate3d(70px, -70px, 0);
            -webkit-transform: translate3d(70px, -70px, 0);
            -o-transform: translate3d(70px, -70px, 0);
            -moz-transform: translate3d(70px, -70px, 0);
        }
        .event-select .menu-open:checked ~ .menu-item:nth-child(5) {
            transition-delay: 0ms;
            -webkit-transition-delay: 0ms;
            -o-transition-delay: 0ms;
            -moz-transition-delay: 0ms;
                transform: translate3d(100px, 0px, 0);
                -ms-transform: translate3d(100px, 0px, 0);
                -webkit-transform: translate3d(100px, 0px, 0);
                -o-transform: translate3d(100px, 0px, 0);
                -moz-transform: translate3d(100px, 0px, 0);
        }
        .event-select .menu-open:checked ~ .menu-item:nth-child(6) {
            transition-delay: 0ms;
            -webkit-transition-delay: 0ms;
            -o-transition-delay: 0ms;
            -moz-transition-delay: 0ms;
                transform: translate3d(70px, 70px, 0);
                -ms-transform: translate3d(70px, 70px, 0);
                -webkit-transform: translate3d(70px, 70px, 0);
                -o-transform: translate3d(70px, 70px, 0);
                -moz-transform: translate3d(70px, 70px, 0);
        }
        .event-select .menu-open:checked ~ .menu-item:nth-child(7) {
            transition-delay: 0ms;
            -webkit-transition-delay: 0ms;
            -o-transition-delay: 0ms;
            -moz-transition-delay: 0ms;
                transform: translate3d(0px, 100px, 0);
                -ms-transform: translate3d(0px, 100px, 0);
                -webkit-transform: translate3d(0px, 100px, 0);
                -o-transform: translate3d(0px, 100px, 0);
                -moz-transform: translate3d(0px, 100px, 0);
        }
        .event-select .menu-open:checked ~ .menu-item:nth-child(8) {
            transition-delay: 0ms;
            -webkit-transition-delay: 0ms;
            -o-transition-delay: 0ms;
            -moz-transition-delay: 0ms;
            transform: translate3d(-70px, 70px, 0);
            -ms-transform: translate3d(-70px, 70px, 0);
            -webkit-transform: translate3d(-70px, 70px, 0);
            -o-transform: translate3d(-70px, 70px, 0);
            -moz-transform: translate3d(-70px, 70px, 0);
        }
        .event-select .menu-open:checked ~ .menu-item:nth-child(9) {
            transition-delay: 0ms;
            -webkit-transition-delay: 0ms;
            -o-transition-delay: 0ms;
            -moz-transition-delay: 0ms;
            transform: translate3d(-100px, 0px, 0);
            -ms-transform: translate3d(-100px, 0px, 0);
            -webkit-transform: translate3d(-100px, 0px, 0);
            -o-transform: translate3d(-100px, 0px, 0);
            -moz-transform: translate3d(-100px, 0px, 0);
        }

        .event-select .menu-open:checked ~ .menu-item:nth-child(10) {
            transition-delay: 0ms;
            -webkit-transition-delay: 0ms;
            -o-transition-delay: 0ms;
            -moz-transition-delay: 0ms;
            transform: translate3d(-70px, -70px, 0);
            -ms-transform: translate3d(-70px, -70px, 0);
            -webkit-transform: translate3d(-70px, -70px, 0);
            -o-transform: translate3d(-70px, -70px, 0);
            -moz-transform: translate3d(-70px, -70px, 0);
        }
	}

</style>

<script type="text/x-tmpl" id="user-select">
{%
	var name = o.name;
	var id = o.id;
	var url = o.url;
%}
<div class="token" data-user-id="{%=id%}">
	<span class="name token-label"><img src="{%=url%}" alt="{%=name%}">{%=name%}</span><a href="#" class="close" tabindex="-1">×</a>
</div>
</script>

<script type="text/x-tmpl" id="row-day-event">
{%
	var js_date = Date.fromSqlDate(o.sql_date);
	var red_day = (js_date.getDay() == 0 || js_date.getDay() == 6) ? 'dayOff' : '';
	var curr_date = (o.sql_date == todayDate) ? 'currentDate' : '';
%}
<div id="row-day_{%=o.sql_date%}" class="periodItem">
{%
	if(Timeline.viewState == Timeline.VIEW_STATE_DAY) {
%}
	<div id="day{%=o.sql_date%}" class="calendar {%=red_day%} {%=curr_date%}" data-type="day" data-date="{%=o.sql_date%}">
		<div class="date">{%=js_date.getDate()%} {%=aMonths[js_date.getMonth()]%}</div>
		<i class="fa fa-circle divider"></i>
		<div class="weekday">{%=aDays[js_date.getDay()]%}</div>
	</div>
{%
	}
	for(hour = 23; hour >= 0; hour--) {
		include('time-line-cell', {
			globalData: o.globalData,
			hour: hour,
			sql_date: o.sql_date,
			data: (o.data && o.data[zeroFormat(hour)]) ? o.data[zeroFormat(hour)] : {}
		});
	}
%}

{%
	var event = null;
	if (o.data) {
		var firstHour = Object.keys(o.data)[0];
		if (firstHour) {
			var firstEvent = o.globalData.events[o.data[firstHour][0]];
			if (firstEvent.KonstructorCreation) {
				event = firstEvent.KonstructorCreation;
			}
		}
	}
	if (event) {
		include('konstructor-creation', {event: event});
	} else {
		var red_day = (js_date.getDay() == 0 || js_date.getDay() == 6) ? 'dayOff' : '';
		var curr_date = (o.sql_date == todayDate) ? 'currentDate' : '';
	}
%}
</div>
</script>

<script type="text/x-tmpl" id="row-week-event">
{%
	var js_date = Date.fromSqlDate(o.sql_date);
	var red_day = (js_date.getDay() == 0 || js_date.getDay() == 6) ? 'dayOff' : '';
	var curr_date = (o.sql_date == todayDate) ? 'currentDate' : '';
%}
<div id="row-week_{%=o.sql_date%}" class="periodItem">
	<div id="day{%=o.sql_date%}" class="calendar {%=red_day%} {%=curr_date%}" data-type="week" data-date="{%=o.sql_date%}">
		<div class="date">{%=js_date.getDate()%} {%=aMonths[js_date.getMonth()]%}</div>
		<i class="fa fa-circle divider"></i>
		<div class="weekday">{%=aDays[js_date.getDay()]%}</div>
	</div>
{%
		var id = 'timeline_' + js_date;
		var event = null;
		for(var i = 0; i < o.data.length; i++) {
			event = o.globalData.events[o.data[i]];
			if (event.SelfRegistration) {
				break;
			}
		}
%}
	<div id="{%=id%}" class="eventListBlock clearfix">
		<div class="leftSide">
{%
	for(var i = 0; i < o.data.length; i++) {
		event = o.globalData.events[o.data[i]];
		if (event.UserEvent) {
			include('user-event', {globalData: o.globalData, event: event.UserEvent});
		} else if (event.ChatEvent && event.ChatEvent.file_id) {
			include('chat-event-file', {globalData: o.globalData, event: event.ChatEvent});
		} else if (event.GroupMember) {
			include('joined-group', {globalData: o.globalData, event: event.GroupMember});
		} else if (event.ProjectMember) {
			include('joined-project', {globalData: o.globalData, event: event.ProjectMember});
		} else if (event.Order) {
			include('order-created', {globalData: o.globalData, event: {Order: event.Order, OrderType: event.OrderType}});
		} else if (event.OrderProduct) {
			include('given-device', {globalData: o.globalData, event: {OrderProduct: event.OrderProduct, Product: event.Product}});
		} else if (event.SelfRegistration) {
			//include('last_groups', {globalData: o.globalData});
		}
	}
%}
		</div>
		<div class="rightSide">
{%
	for(var i = 0; i < o.data.length; i++) {
		event = o.globalData.events[o.data[i]];
		if (event.ChatEvent && event.ChatEvent.msg_id) {
			include('chat-event-msg', {globalData: o.globalData, event: event.ChatEvent});
		} else if (event.Article) {
			include('article-event', {globalData: o.globalData, event: event.Article});
		} else if (event.SelfRegistration) {
			// include('self-registered', {globalData: o.globalData, event: event.SelfRegistration});
			include('last-registered', {globalData: o.globalData});
		} else if (event.Share) {
			include('cloud-share', {globalData: o.globalData, share: event});
		}
	}
%}
		</div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="row-month-event">
{%
	var js_date = Date.fromSqlDate(o.sql_date);
	var js_today = Date.fromSqlDate(todayDate);

	var kStartDate =  Date.fromSqlDate(startDate);

	var curr_date = (js_date.getMonth() == js_today.getMonth() && js_date.getFullYear() == js_today.getFullYear()) ? 'currentDate' : '';
	var curr_year = js_date.getFullYear() == js_today.getFullYear();

	if( js_date.getFullYear() > kStartDate.getFullYear() || ( js_date.getFullYear() == kStartDate.getFullYear() &&  js_date.getMonth() >= kStartDate.getMonth()) ) {
%}
<div id="row-month_{%=o.sql_date%}" class="periodItem">
	<div id="day{%=o.sql_date%}" class="calendar {%=curr_date%}" data-type="month" data-date="{%=o.sql_date%}">
		<div class="date">{%=aMonthsFull[js_date.getMonth()]%}</div>
		<i class="fa fa-circle divider"></i>
{%
	if(!curr_year) {
%}
		<div class="weekday">{%=js_date.getFullYear()%}</div>
{%
	}
%}
	</div>
{%
		var id = 'timeline_' + js_date;
		var event = null;
		for(var i = 0; i < o.data.length; i++) {
			event = o.globalData.events[o.data[i]];
			if (event.SelfRegistration) {
				break;
			}
		}
%}
	<div id="{%=id%}" class="eventListBlock clearfix">
		<div class="leftSide">
{%
		for(var i = 0; i < o.data.length; i++) {
			event = o.globalData.events[o.data[i]];
			if (event.ChatEvent && event.ChatEvent.file_id) {
				include('chat-event-file', {globalData: o.globalData, event: event.ChatEvent});
			} else if (event.GroupMember) {
				include('joined-group', {globalData: o.globalData, event: event.GroupMember});
			} else if (event.Order) {
				include('order-created', {globalData: o.globalData, event: {Order: event.Order, OrderType: event.OrderType}});
			} else if (event.OrderProduct) {
				include('given-device', {globalData: o.globalData, event: {OrderProduct: event.OrderProduct, Product: event.Product}});
			}
		}
%}
		</div>
		<div class="rightSide">
{%
		for(var i = 0; i < o.data.length; i++) {
			event = o.globalData.events[o.data[i]];
			if (event.ChatEvent && event.ChatEvent.msg_id) {
				include('chat-event-msg', {globalData: o.globalData, event: event.ChatEvent});
			} else if (event.Article && event.Article.owner_id == currUser ) {
				include('article-event', {globalData: o.globalData, event: event.Article});
			} else if (event.Share) {
				include('cloud-share', {globalData: o.globalData, share: event});
			}
		}
%}
		</div>
	</div>
</div>
{%
	}
%}
</script>

<script type="text/x-tmpl" id="row-year-event">
{%
	var js_date = Date.fromSqlDate(o.sql_date);
	var js_today = Date.fromSqlDate(todayDate);

	var kStartDate =  Date.fromSqlDate(startDate);

	var curr_date = (js_date.getFullYear() == js_today.getFullYear()) ? 'currentDate' : '';

	if( js_date.getFullYear() >= kStartDate.getFullYear() ) {
%}
<div id="row-year_{%=o.sql_date%}" class="periodItem">
	<div id="day{%=o.sql_date%}" class="calendar {%=curr_date%}" data-type="year" data-date="{%=js_date.getFullYear()%}">
		<div class="date">{%=js_date.getFullYear()%}</div>
		<i class="fa fa-circle divider"></i>
	</div>
{%
		var id = 'timeline_' + js_date;
		var event = null;
		for(var i = 0; i < o.data.length; i++) {
			event = o.globalData.events[o.data[i]];
			if (event.SelfRegistration) {
				break;
			}
		}
%}
	<div id="{%=id%}" class="eventListBlock clearfix">
		<div class="leftSide">
{%
		for(var i = 0; i < o.data.length; i++) {
			event = o.globalData.events[o.data[i]];
			if (event.GroupMember) {
				include('joined-group', {globalData: o.globalData, event: event.GroupMember});
			} else if (event.Order) {
				include('order-created', {globalData: o.globalData, event: {Order: event.Order, OrderType: event.OrderType}});
			}
		}
%}
		</div>
		<div class="rightSide">
{%
		for(var i = 0; i < o.data.length; i++) {
			event = o.globalData.events[o.data[i]];
			if (event.ChatEvent && event.ChatEvent.msg_id) {
				include('chat-event-msg', {globalData: o.globalData, event: event.ChatEvent});
			} else if (event.Article && event.Article.owner_id == currUser ) {
				include('article-event', {globalData: o.globalData, event: event.Article});
			} else if (event.Share) {
				include('cloud-share', {globalData: o.globalData, share: event});
			}
		}
%}
		</div>
	</div>
</div>
{%
	}
%}
</script>

<script type="text/x-tmpl" id="threeDots">
<div class="threeDots"><span class="value">...</span></div>
</script>

<script type="text/x-tmpl" id="curr-time">
	<div class="currentTime" style="font-weight: bold;"><span class="value">{%=o.time%}</span></div>
</script>

<script type="text/x-tmpl" id="time-line-cell">
{%

	var js_date = Date.fromSqlDate(o.sql_date);
	js_date.setHours(o.hour);
	js_date.setMinutes(0);
	var id = 'timeline_' + o.sql_date + '_' + zeroFormat(js_date.getHours()) + zeroFormat(js_date.getMinutes());
	var event = null;
	for(var i = 0; i < o.data.length; i++) {
		event = o.globalData.events[o.data[i]];
		if (event.SelfRegistration) {
			break;
		}
	}

    var date = new Date();
    var testDate = date.getFullYear() + '-' + zeroFormat(date.getMonth()+1) + '-' + zeroFormat(date.getDate());
    var testHour = zeroFormat(date.getHours());
    var searchTime = (testDate == o.sql_date && testHour == o.hour);

%}
<div id="{%=id%}" class="eventListBlock clearfix">

    <div class="leftSide">

    {%
		if (event && event.SelfRegistration) {
	%}
			<div class="text-center">
				<p><img src="/img/house-left-mytime.png"></p>
			</div>
			<div class="text-center text-uppercase">
				<p class="info-dream"><?=__('Choose and join in interesting projects')?></p>
			</div>
	{%
		}
	%}

    {%
		if (event && event.SelfRegistration) {
			include('interesting-project', {globalData: o.globalData});
			include('popular_articles', {globalData: o.globalData});
        }
    %}

{%
    if(typeof o.globalData.users != 'undefined'){
        user_reg = o.globalData.users['<?=$currUserID?>'];
        var d = new Date(user_reg.User.created);
        var h = d.getHours();
        if(user_reg.User.is_confirmed === false && (o.hour == h)){
        %}
        <div class="confirmation_block" style="text-align: justify; position: relative; background: rgba(251, 186, 0, 0.15);padding: 10px; z-index: 999999999;">
            <p> <?php echo __('Please confirm your e-mail. Confirmation email has been sent to your mailbox.')?></p>
            <div class="clearfix">
                <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'userConfirm', 'plugin' => false))?>" style="background: #FFF;" class="btn btn-default save-button pull-right" ><?php echo __('Resend confirmation email');?> </a>
            </div>
            <!--div class="clearfix">
                <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'changeEmail', 'plugin' => false))?>" class="btn-default pull-right" ><?php echo __('Change email address') ?></a>
            </div-->
        </div>
        {%
        }
    }

    //вывод групп при поиске
    if (searchTime && o.globalData.search_groups) {
        if( o.globalData.search_groups.length > 0 ) {
%}
        <div class="searchHeader"><?=__('Founded groups')?></div>
{%
            for(var i = 0; i < o.globalData.search_groups.length; i++) {
                include('group-search', {globalData: o.globalData, group: o.globalData.search_groups[i]});
            }
        }
    }

    //вывод статей при поиске
    if (searchTime && o.globalData.search_articles) {
        if( o.globalData.search_articles.length > 0 ) {
%}
        <div class="searchHeader"><?=__('Founded articles')?></div>
{%
            for(var i = 0; i < o.globalData.search_articles.length; i++) {
                include('article-search', {globalData: o.globalData, article: o.globalData.search_articles[i]});
            }
        }
    }

        //Show files during search
    if (searchTime && o.globalData.search_files) {
        if( o.globalData.search_files.length > 0 ) {
%}
        <div class="searchHeader"><?=__('Founded files')?></div>
{%
            include('file-search', {globalData: o.globalData});
        }
    }

    if (searchTime && o.globalData.invites) {
        if( o.globalData.invites.length > 0 ) {
            for(var i = 0; i < o.globalData.invites.length; i++) {
                include('invite-group', {globalData: o.globalData, GroupMember: o.globalData.invites[i].GroupMember});
            }
        }
    }
    for(var i = 0; i < o.data.length; i++) {
        event = o.globalData.events[o.data[i]];
        //render items byt template type
        if (event.UserEvent && event.UserEvent.user_id != '<?=$currUserID?>') {
            include('user-event', {globalData: o.globalData, event: event.UserEvent});
        } else if (event.ChatEvent && event.ChatEvent.file_id) {
            include('chat-event-file', {globalData: o.globalData, event: event.ChatEvent});
        } else if (event.ProjectEvent && event.ProjectEvent.file_id) {
            include('project-task-file', {globalData: o.globalData, event: event.ProjectEvent});
        } else if (event.ProjectEvent && event.ProjectEvent.msg_id && event.ProjectEvent.user_id != '<?=$currUserID?>' ) {
            include('project-task-msg', {globalData: o.globalData, event: event.ProjectEvent});
        } else if (event.ProjectMember) {
            include('joined-project', {globalData: o.globalData, event: event.ProjectMember});
        } else if (event.Article && event.Article.owner_id != '<?=$currUserID?>') {
            include('article-event', {globalData: o.globalData, event: event.Article});
        } else if (event.ArticleEvent && event.ArticleEvent.user_id != '<?=$currUserID?>') {
            include('article-comment-event', {globalData: o.globalData, event: event.ArticleEvent});
        } else if (event.Order) {
            include('order-created', {globalData: o.globalData, event: {Order: event.Order, OrderType: event.OrderType}});
        } else if (event.OrderProduct) {
            include('given-device', {globalData: o.globalData, event: {OrderProduct: event.OrderProduct, Product: event.Product}});
        } else if (event.SelfRegistration || o.sql_date==startDay) {
            //include('last_groups', {globalData: o.globalData});
        }
    }
%}
    </div>
    <div class="rightSide">

    {%
		if (event && event.SelfRegistration) {
	%}
			<div class="text-center" style="margin-top:49px;">
				<p><img src="/img/house-right-mytime.png"></p>
			</div>
			<div class="text-center text-uppercase">
				<p class="info-dream"><?=__('You can build your own dream')?></p>
			</div>
			<div class="text-center">
				<img src="/img/group/crown-l.png" alt="" class="crown-img"/>
				<p class="crown-activate"><?=__('Activate your dream')?></p>
			</div>
	{%
		}
	%}

{%
    //вывод пользователей при поиске
    if (searchTime && o.globalData.search_users) {
        if( o.globalData.search_users.length > 0 ) {
%}
        <div class="searchHeader"><?=__('Founded users')?></div>
{%
            for(var i = 0; i < o.globalData.search_users.length; i++) {
                include('user-search', {globalData: o.globalData, user: o.globalData.search_users[i]});
            }
        }
    }

    // Запросы на вступление в группу
    if (searchTime && o.globalData.group_join_requests) {
        if( o.globalData.group_join_requests.length > 0 ) {
            for(var i = 0; i < o.globalData.group_join_requests.length; i++) {
                include('join-group-request', {globalData: o.globalData, GroupMember: o.globalData.group_join_requests[i]});
            }

        }
    }

    if (searchTime && parseInt(o.globalData.stats) > 0) {
        %}
        <a href="<?=$this->Html->url(array('controller' => 'Statistic', 'action' => 'index'))?>" class="event clearfix views">
            <div class="description" style="font-weight: 900;"><?=__('Views in last 24 hours:')?></div>
            <div class="value">{%=o.globalData.stats%}</div>
        </a>
        {%
    }

    for(var i = 0; i < o.data.length; i++) {
        event = o.globalData.events[o.data[i]];
        if (event.UserEvent && event.UserEvent.user_id == '<?=$currUserID?>') {
            include('user-event', {globalData: o.globalData, event: event.UserEvent});
        } else if (event.ChatEvent && event.ChatEvent.msg_id) {
            include('chat-event-msg', {globalData: o.globalData, event: event.ChatEvent});
        } else if (event.Article && event.Article.owner_id == '<?=$currUserID?>') {
            include('article-event', {globalData: o.globalData, event: event.Article});
        } else if (event.ArticleEvent && event.ArticleEvent.user_id == '<?=$currUserID?>') {
            include('article-comment-event', {globalData: o.globalData, event: event.ArticleEvent});
        } else if (event.GroupMember && event.GroupMember.approved == '1' && event.GroupMember.is_invited == '0') {
            include('joined-group', {globalData: o.globalData, event: event.GroupMember});
        } else if (event.ProjectEvent && event.ProjectEvent.msg_id && event.ProjectEvent.user_id == '<?=$currUserID?>' ) {
            include('project-task-msg', {globalData: o.globalData, event: event.ProjectEvent});
        } else if (event.VacancyResponse) {
            include('vacancy-response', {globalData: o.globalData, event: event.VacancyResponse});
        } else if (event.SelfRegistration) {
            // include('self-registered', {globalData: o.globalData, event: event.SelfRegistration});
            include('last-registered', {globalData: o.globalData});
           // include('last_articles', {globalData: o.globalData});
        } else if (event.Share) {
            include('cloud-share', {globalData: o.globalData, share: event});
        }
    }
%}
    </div>
{%
    if (js_date.getHours() > 0) {
%}
    <div class="clearfix"></div>
    <div id="time-{%=zeroFormat(js_date.getHours())%}" class="time" data-date="{%=o.sql_date%}" data-hour="{%=zeroFormat(js_date.getHours())%}">
        <span class="value">{%=Date.HoursMinutes(js_date, locale)%}</span>
    </div>
{%
    }
%}
</div>
</script>

<script type="text/x-tmpl" id="user-event">
{%
	var js_date = Date.fromSqlDate(o.event.event_time);
	var js_prev_date = null;
	var event_time = o.event.event_time;
	var previous_event_time = o.event.previous_event_time;

	if( (event_time.substring(0, event_time.length - 3) !== previous_event_time.substring(0, previous_event_time.length - 3)) && (previous_event_time !== '0000-00-00 00:00:00') ) {
		js_prev_date = Date.fromSqlDate(o.event.previous_event_time);
	}

	var time = zeroFormat(js_date.getHours()) + ':' + zeroFormat(js_date.getMinutes());

	var owned = o.event.user_id === '<?=$currUserID?>';
	var js_date2 = Date.fromSqlDate(o.event.event_end_time);
	var time2 = zeroFormat(js_date2.getHours()) + ':' + zeroFormat(js_date2.getMinutes());

	var eType = o.event.type;
	var icon = '';
	var recipient = owned ? o.event.recipient_id : o.event.user_id;
	var display_time = Date.HoursMinutes(js_date, locale);
	var recipientClass = owned ? '' : 'recipient';



	if( o.event.accepted[<?=$currUserID?>].UserEventShare.acceptance == 0 ) {
		var real_time = Date.fromSqlDate(o.event.event_time);

		if (locale == 'rus') {
			real_time = ' ' + zeroFormat(real_time.getDate()) + '.' + zeroFormat(real_time.getMonth()+1) + ' ' + Date.HoursMinutes(real_time, locale);
		} else {
			real_time = ' ' + zeroFormat(real_time.getMonth()) + '/' + zeroFormat(real_time.getDate()+1) + ' ' + Date.HoursMinutes(real_time, locale);
		}
		display_time = ' ';
	}

/*
	var taskId = o.event.task_id;
	var taskUrl = '';
	if( taskId != null ) {
		taskUrl = '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'task', '~task_id'))?>';
		taskUrl = taskUrl.replace(/~task_id/, taskId);
	};
*/

	var objId = o.event.object_id;
	var objType = o.event.object_type;
	var objUrl = '';

	// Do not use switch statement
	if( objId != null && objType != "") {
		if(objType == 'subproject') {
		  objUrl = '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'subproject', '~obj_id'))?>';
		  objUrl = objUrl.replace(/~obj_id/, objId);
		}else if(objType == 'task'){
		  objUrl = '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'task', '~obj_id'))?>';
		  objUrl = objUrl.replace(/~obj_id/, objId);
		}else if(objType == 'group'){
		  objUrl = '<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', '~obj_id'))?>';
		  objUrl = objUrl.replace(/~obj_id/, objId);
		}else if(objType == 'project'){
		  objUrl = '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'view', '~obj_id'))?>';
		  objUrl = objUrl.replace(/~obj_id/, objId);
		}

	}

	// Do not remove next closing and opening tags. Ipad bug garanteed
%}

{%

	if(eType == "meet") { icon = 'user'; }
	else if(eType == "call") { icon = 'phone_alt'; }
	else if(eType == "mail") { icon = 'send'; }
	else if(eType == "conference") { icon = 'group'; }
	else if(eType == "sport") { icon = 'rugby'; }
	else if(eType == "task") { icon = 'check'; }
	else if(eType == "purchase") { icon = 'shopping_cart'; }
	else if(eType == "entertain") { icon = 'gamepad'; }
	else if(eType == "pay") { icon = 'credit_card'; }
	else { icon = 'calendar'; }
%}
<div id="user-event_{%=o.event.id%}" class="userEvent event clearfix {%=recipientClass%}" onclick="Timeline.editEventPopup('{%=js_date.toSqlDate()%}', '{%=time%}', '{%=js_date2.toSqlDate()%}', '{%=time2%}', '{%=eType%}', '{%=recipient%}', '{%=objType%}', '{%=objId%}', {%=o.event.id%}, {%=o.event.shared%}, {%=owned%}, '{%=o.event.place_name%}', '{%=o.event.place_coords%}', '{%=o.event.finance_operation_id%}', '{%=o.event.price%}', '{%=o.event.event_category_id%}')">
	<div class="eventTime">{%=display_time%}</div>
	<div class="glyphicons {%=icon%}"></div>
	<div class="text">
{%
	switch(icon) {
	  case 'phone_alt':
		%}<?=__('You have to make a call: ')?>{%
		break;
	  case 'send':
		%}<?=__('You have to send an email: ')?>{%
		break;
	  case 'user':
		%}<?=__('You have a meetup: ')?>{%
		break;
	  default:
		%}<?=__('You have an event: ')?>{%
		break;
	}
%}
		{% if(objUrl != '') { %} <a class='title taskLink' href="{%=objUrl%}">{%=o.event.title%}</a>
		{% } else { %} <div class='title' style="display: inline-block;">{%=o.event.title%}</div> {% } %}
			<span style="font-weight: 600">{%=real_time%}</span>
		{%
			if(recipient != null && recipient.length > 0) {
		%}
			<div class="additionalBlock">
		{%
			recipient = recipient.split(',');
			for(var i = 0; i<recipient.length; i++) {
				var user = o.globalData.users[recipient[i]];
				var username = user.User.full_name;

				var userUrl = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
				userUrl = userUrl.replace(/~user_id/, recipient[i]);
			%}
				<div class="clearfix" style="margin: 4px 0">
					<img class="{%=user.User.rating_class%} ava rounded" src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=user.User.full_name%}"  onClick="window.location.href='{%=userUrl%}'">

{%
				if( o.event.accepted[recipient[i]] ) {
					if( o.event.accepted[recipient[i]].UserEventShare.acceptance === '1' ) {
%}
	<span class="glyphicons ok"></span>
{%
				} else if( o.event.accepted[recipient[i]].UserEventShare.acceptance === '-1' ) {
%}
	<span class="glyphicons remove"></span>
{%
				}
			}
%}
					<a class="userLink" href="{%=userUrl%}">{%=username%}</a>
				</div>
{%
		}
%}
			</div>
{%
	}
	if(o.event.descr.length) {
%}
		<div class="additionalBlock">
			<div class='description'><?=__('Event comment:')?></div>
			<div class='description eDescr' style="font-style: italic; word-wrap: break-word;">{%#o.event.descr%}</div>
		</div>
{%
			}
			if(js_prev_date != null) {
				var prevDate = null;
				if (locale == 'rus') {
					prevDate = zeroFormat(js_prev_date.getDate()) + '.' + zeroFormat(js_prev_date.getMonth()+1) + ' ' + Date.HoursMinutes(js_prev_date, locale);
				} else {
					prevDate = zeroFormat(js_prev_date.getMonth()) + '/' + zeroFormat(js_prev_date.getDate()+1) + ' ' + Date.HoursMinutes(js_prev_date, locale);
				}
%}
			<div class='description' style="font-style: italic; font-weight: 600;"><?=__('date moved from')?> {%=prevDate%}</div>
{%
			}
			if(o.event.place_name) {
%}
			<div class="additionalBlock">
				<div class='description eDescr'><?=__('Event place:')?></div>
				<div class='description eDescr' style="font-style: italic;">{%=o.event.place_name%}</div>
				<img src="https://maps.googleapis.com/maps/api/staticmap?center={%=o.event.place_coords%}&zoom=14&scale=2&size=400x200&markers=size:mid%7Ccolor:0x22b5ae%7Clabel:no%7C{%=o.event.place_coords%}" alt="{%=o.event.place_name%}" style="width: 100%; margin-top: 10px; margin-bottom: 10px; box-shadow: 0 2px 3px 2px rgba(0, 0, 0, .25);">
			</div>
{%
			}
%}
	</div>

{%
	if(recipient != null && recipient.length > 0) {
		if( o.event.accepted[<?=$currUserID?>].UserEventShare.acceptance == 0 ) {
%}
	<div class="control glyphicons circle_ok" onclick="controlClicked = true; return Timeline.acceptEvent('{%=o.event.id%}','<?=$currUserID?>')"></div>
	<div class="control glyphicons circle_remove" onclick="controlClicked = true; return Timeline.declineEvent('{%=o.event.id%}','<?=$currUserID?>')"></div>
{%
		} else if( o.event.accepted[<?=$currUserID?>].UserEventShare.acceptance == -1 ) {
%}
	<!--div class="control glyphicons circle_remove" style="color: #FF6363;"></div-->
{%
		} else if( o.event.accepted[<?=$currUserID?>].UserEventShare.acceptance == 1 ) {
%}
	<!--div class="control glyphicons circle_ok" style="color: #23B5AE;"></div-->
{%
		}
	}
%}
</div>
</script>

<script type="text/x-tmpl" id="order-created">
{%
	var js_date = Date.fromSqlDate(o.event.Order.created);
	var time = zeroFormat(js_date.getHours()) + ':' + zeroFormat(js_date.getMinutes());
	var order = o.event.Order;
	var url = '<?=$this->Html->url(array('controller' => 'Device', 'action' => 'view', '~order_id'))?>';
	url = url.replace(/~order_id/, order.id);
	var order_id = '000-000-' + ((order.id < 100) ? '0' + order.id : order.id);
%}
<div class="userEvent event clearfix">
	<div class="eventTime">{%=Date.HoursMinutes(js_date, locale)%}</div>
	<div class="text">
		<a class='title taskLink' href="{%=url%}">{%=order_id%}</a>
		<div class='descr'><?=__('You created an order')?></div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="given-device">
{%
	var js_date = Date.fromSqlDate(o.event.OrderProduct.distrib_date);
	var time = zeroFormat(js_date.getHours()) + ':' + zeroFormat(js_date.getMinutes());
	var product = o.event.Product;
	var order_id = o.event.OrderProduct.order_id;
	var url = '<?=$this->Html->url(array('controller' => 'Device', 'action' => 'view', '~order_id'))?>';
	url = url.replace(/~order_id/, order_id);
	var order_id = '000-000-' + ((order_id < 100) ? '0' + order_id : order_id);
	var icon = o.globalData.productTypes[product.product_type_id].ProductType.icon;
%}
<div class="userEvent event clearfix">
	<div class="eventTime">{%=Date.HoursMinutes(js_date, locale)%}</div>
	<div class="glyphicons {%=icon%}"></div>
	<div class="text">
		<a class='title taskLink' href="{%=url%}">{%=order_id%}</a>
		<div class='descr'><?=__('You received a device due to this order')?></div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="chat-event-msg">
{%
	var js_date = Date.fromSqlDate(o.event.created);
	var room = (o.globalData.rooms[o.event.room_id]);
	var roomId = o.event.room_id;
	var url = '<?=$this->Html->url(array('controller' => 'Chat', 'action' => 'room', '~room_id'))?>';
	url = url.replace(/~room_id/, o.event.room_id);
	msg = o.globalData.messages[o.event.msg_id].message;
	replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
	msg = jQuery('<div/>').append(msg.replace(replacePattern1, '<a href="$1" target="_blank"><span>$1</span></a>')).html();
%}
{%

   var date_day = Date.HoursMinutes(js_date, locale);
   setDateMessage(date_day);

  $('#showMonth').on('click', function() {
	setDateMessage(convertDate(new Date(Date(js_date, locale))));
  });
  $('#showDay').on('click', function() {
	setDateMessage(Date.HoursMinutes(js_date, locale));
  });
  $('#showYear').on('click', function() {
	setDateMessage(convertDate(new Date(Date(js_date, locale))));
  });
  $('#showWeek').on('click', function() {
	setDateMessage(Date.HoursMinutes(js_date, locale));
  });

  function convertDate(inputFormat) {
	function pad(s) { return (s < 10) ? '0' + s : s; }
	var d = new Date(inputFormat);
	return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear() - 2000].join('.');
  }

  function getDateMessage(){
	if(window.date_day == undefined){
	  window.date_day = Date.HoursMinutes(js_date, locale);
	}
	return window.date_day;
  }
  function setDateMessage(data){
	window.date_day = data;
  }

  %}
<div id="chatEvent-{%=o.event.id%}" class="event chatEvent clearfix">
	<span class="eventTime">{%=getDateMessage()%}</span>
{%
	if((typeof room != 'undefined')&&(room.ChatRoom.group_id != null)) {
		group = o.globalData.groups[room.ChatRoom.group_id];
		groupUrl = '<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', '~group_id'))?>';
		groupUrl = groupUrl.replace(/~group_id/, room.ChatRoom.group_id);
%}
	<img class="{%=group.Group.rating_class%} ava rounded" src="{%=group.GroupMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=group.Group.title%}"  onClick="window.location.href='{%=userUrl%}'">
{%
	} else {
		var user = o.globalData.users[o.event.initiator_id];
		var userUrl = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
		userUrl = userUrl.replace(/~user_id/, o.event.initiator_id);
		userId = user.User.id;
%}
	<img class="{%=user.User.rating_class%} ava rounded" src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=user.User.full_name%}"  onClick="window.location.href='{%=userUrl%}'" data-user-id="{%=userId%}" data-room-id={%=roomId%}>
{%
	}
%}
	<span id="eventMsg-{%=o.event.msg_id%}" class="chatText" onClick="window.location.href='{%=url%}'">{%#msg%}</span>
</div>
</script>

<script type="text/x-tmpl" id="chat-event-file">
{%
	var js_date = Date.fromSqlDate(o.event.created);
	var file = o.globalData.files[o.event.file_id];

	var user = o.globalData.users[o.event.initiator_id];
	var userId = user.User.id;
	var url = '<?=$this->Html->url(array('controller' => 'Chat', 'action' => 'room', '~room_id'))?>';
	url = url.replace(/~room_id/, o.event.room_id);
	var filetype = file.file;
	var fileId = file.id;
	var roomId = o.event.room_id;
	var shareUrl = '<?="https://".$_SERVER["HTTP_HOST"]?>' + file.url_preview;
	var ext = file.ext.replace(/\./, '').toLowerCase();
	file.url_preview = (iOS && (docFiles.indexOf(ext) > (-1))) ? '/File/download/'+file.id : file.url_preview;
%}
<div class="event chatEvent clearfix">
	<span class="eventTime">{%=Date.HoursMinutes(js_date, locale)%}</span>
{%
	if( (filetype == 'image') && (ext != 'tif') && (ext != 'tiff') ) {
%}
	<img class="ava blockLine" alt="{%=filetype%}" src="{%=file.url_img.replace(/noresize/, 'thumb100x100')%}" style="margin: 0 15px; cursor: pointer; float: right;" data-file-id="{%=fileId%}" data-room-id={%=roomId%} data-user-id="{%=userId%}">
{%
	} else {
%}
	<a href="{%=file.url_preview%}" target="_blank" class="filetype {%=ext%}" data-file-id="{%=fileId%}" data-room-id={%=roomId%} data-user-id="{%=userId%}"></a>
{%
	}
%}
	<div class="text">
		<a href="{%=file.url_preview%}" target="_blank" class="underline link-filetype {%=ext%}" data-file-id="{%=fileId%}" data-user-id="{%=userId%}">{%=file.orig_fname%}</a>
		<div class="description"><?=__('You received a file from ')?> <a href="{%=url%}">{%=user.User.full_name%}</a></div>
		<a href="javascript: void(0)" onclick="cloudSave('{%=file.id%}')" class="cloudSave"><span class="glyphicons cloud_plus"></span></a>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="user-search">
<div class="event clearfix taskMsg">
	<a href="/User/view/{%=o.user.User.id%}"><img src="{%=o.user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=o.user.User.full_name%}" class="{%=o.user.User.rating_class%} ava rounded" style="margin-left: 45px;"></a>
	<span class="articleText">
		<div>
			<a href="/User/view/{%=o.user.User.id%}" class="underline">{%=o.user.User.full_name%}</a>
		</div>
		<div class="description">{%=o.user.User.skills%}</div>
	</span>
</div>
</script>

<script type="text/x-tmpl" id="group-search">
<div class="event clearfix taskMsg">
	<a href="/Group/view/{%=o.group.Group.id%}"><img src="{%=o.group.GroupMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=o.group.Group.title%}" class="{%=o.group.Group.rating_class%} ava rounded" style="margin-right: 45px;"></a>
	<span class="articleText">
		<div>
			<a href="/Group/view/{%=o.group.Group.id%}" class="underline">{%=o.group.Group.title%}</a>
		</div>
		<div class="description">{%=o.group.Group.membersCount%} <?=__('member(s)')?></div>
	</span>
</div>
</script>

<script type="text/x-tmpl" id="article-search">
<div class="event clearfix taskMsg">
	<a href="/Article/view/{%=o.article.Article.id%}"><img src="{%=o.article.ArticleMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=o.article.Article.title%}" class="ava rounded" style="margin-right: 45px;"></a>
	<span class="articleText">
		<div>
			<a href="/Article/view/{%=o.article.Article.id%}" class="underline">{%=o.article.Article.title%}</a>
		</div>
	</span>
</div>
</script>

<script type="text/x-tmpl" id="file-search">

	{%
		var files = o.globalData.search_files;
		for(var i = 0; i < files.length; i++) {
			var media = files[i]['Media'];
			var ext = media['ext'].toLowerCase();
			ext = ext.replace('jpeg', 'jpg');
			ext = ext.substring(1);
			var fileClass = Cloud.hasType( ext ) ? 'filetype ' + ext : 'glyphicons file';
	%}
		<div class="event clearfix taskMsg">

				<a href="{%=media['url_preview']%}" target="_blank" class="item" data-type="file">
					<span class="{%=fileClass%}" style="padding: 0 5px;">
					</span>
					<div class="title">{%=media['orig_fname']%}</div>

				</a>
			</div>

			{%
		}
	%}

</script>

<script type="text/x-tmpl" id="article-event">
{%
	var js_date = Date.fromSqlDate(o.event.created);
	var user = o.globalData.users[o.event.owner_id];
	var userId = user.User.id;
	var group = o.globalData.groups[o.event.group_id];
	var article = o.globalData.articles[o.event.created];
	var url = '<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', '~id'))?>';
	url = url.replace(/~id/, o.event.id);
	var authorUrl = '';
	var crown_html = '';
	if(group != null) {
		authorUrl = '<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', '~id'))?>';
		authorUrl = authorUrl.replace(/~id/, o.event.group_id);
		if(group.Group.is_dream == 1) {
			var crown_src = '<?php echo $this->webroot . "img/group/crawn-s.png";?>';
			var crown_html = "<span style='padding: 0 3px;'><img src='" + crown_src + "' /></span>";
		}
	} else {
		authorUrl = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~id'))?>';
		authorUrl = authorUrl.replace(/~id/, o.event.owner_id);
	}
%}
<div class="event clearfix">
	<span class="eventTime">{%=Date.HoursMinutes(js_date, locale)%}</span>
	<a href="{%=authorUrl%}" class="dropme-files" data-user-id={%=userId%}>
{% if(group != null) { %}
				<img class="{%=group.Group.rating_class%} ava rounded" src="{%=group.GroupMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=group.Group.title%}" />
{% } else { %}
				<img class="{%=user.User.rating_class%} ava rounded" src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=user.User.full_name%}" />
{% } %}
	</a>
	<span class="articleText">
		<div><?=__('A new article was published')?>{%#crown_html%}</div>
	</span>
	<div class="clearfix"></div>
	<div class="additionalBlock clearfix">
		<img class="ava rounded" src="{%=article.ArticleMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=o.event.title%}" />
		<div class="articleText">
			<a href="{%=url%}" class="underline">{%=o.event.title%}</a>
		</div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="article-comment-event">
{%
	var js_date = Date.fromSqlDate(o.event.created);
	var user = o.globalData.users[o.event.user_id];
	var articleTitle = o.globalData.article_title;
	var userName = user.User.full_name;
	var url = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
	url = url.replace(/~user_id/, o.event.user_id);

	var msg = o.globalData.messages[o.event.msg_id].message;
	var msgId = o.event.id;

	var str = (o.event.type == 'answer') ? '<?=__('%s answered your comment to %s', '~userName', '~articleLink')?>' : '<?=__('%s commented your article %s', '~userName', '~articleLink')?>';
	if(o.event.user_id == '<?=$currUserID?>') {
		str = (o.event.type == 'answer') ? "<?=__('You\'ve answered on comment to %s', '~articleLink')?>" : "<?=__('You\'ve commented an article %s', '~articleLink')?>";
	}
	var userLink = tmpl('user-post', {ProjectEvent: o.event, userName: user.User.full_name});
	var articleLink = tmpl('article-post', {Article: o.event});
	var article = o.event.article_id;
	  var commentsUrl = '<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' => 'comments', '~task_id'))?>';
	  commentsUrl = commentsUrl.replace(/~task_id/, article);
%}
<div class="event clearfix taskMsg">
	<span class="eventTime">{%=Date.HoursMinutes(js_date, locale)%}</span>
	<a href="{%=url%}"><img src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=userName%}" class="{%=user.User.rating_class%} ava rounded"></a>
	<div class="articleText">
		<div>{%#str.replace(/~userName/, userLink).replace(/~articleLink/, articleLink)%}</div>
	</div>

	<div class="additionalBlock">
		<span class="articleText">
			<div class="description">{%=msg%}</div>
		</span>
	</div>
	<div id="articleComments-{%=o.event.article_id%}" class="commentsContainer">
		  <a class="commentLink" href="javascript:void(0);" data-comments-url="{%=commentsUrl%}" data-comments-id="{%=msgId%}"></a>
	  <div style="clear: both"></div>
	  </div>
</div>
</script>

<script type="text/x-tmpl" id="vacancy-response">
{%
	var js_date = o.event.approve == 0 ? o.event.created : o.event.modified;
	js_date = Date.fromSqlDate(js_date);

	var group = o.globalData.groups[o.event.group_id];
	var vacancyTitle = o.event.title;
	var groupTitle = group.Group.title;

	var vacancyUrl = '<?=$this->Html->url(array('controller' => 'Group', 'action' => 'vacancies', '~group_id'))?>';
	var groupUrl = '<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', '~group_id'))?>';

	vacancyUrl = vacancyUrl.replace(/~group_id/, group.Group.id);
	groupUrl = groupUrl.replace(/~group_id/, group.Group.id);

	var groupRef = tmpl('generic-url', {url: groupUrl, text: groupTitle});
	var vacancyRef = tmpl('generic-url', {url: vacancyUrl, text: vacancyTitle});
	var userRef = tmpl('generic-url', {url: '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view'))?>', text: '<?=__('profile is completed')?>'});

	var str = ''

	if (o.event.approve == '0') {
		str = '<?=__('You applied to %s on the job %s. Please make sure your %s', '~groupRef', '~vacancyRef', '~userRef')?>';
	} else if(o.event.approve == '1') {
		str = '<?=__('Your request to %s for job %s has been accepted', '~groupRef', '~vacancyRef', '~userRef')?>';
	} else if(o.event.approve == '-1') {
		str = '<?=__('Your request to %s for job %s has been rejected', '~groupRef', '~vacancyRef', '~userRef')?>';
	}
%}
<div class="event clearfix taskMsg">
	<span class="eventTime">{%=Date.HoursMinutes(js_date, locale)%}</span>
	<a href="{%=groupUrl%}"><img src="{%=group.GroupMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=groupTitle%}" class="{%=group.Group.rating_class%} ava rounded"></a>
	<span class="articleText">
		<div>{%#str.replace(/~groupRef/, groupRef).replace(/~vacancyRef/, vacancyRef).replace(/~userRef/, userRef)%}</div>
	</span>
</div>
</script>

<script type="text/x-tmpl" id="project-task-msg">
{%
	var js_date = Date.fromSqlDate(o.event.created);
	var user = o.globalData.users[o.event.user_id];
	var group = false;
	var task = o.globalData.tasks;
	task = task[o.event.task_id];
	var group = task['Task']['Group'];
	var crown_html = '';
	if(group.is_dream == 1) {
		var crown_src = '<?php echo $this->webroot . "img/group/crawn-s.png";?>';
		var crown_html = "<span><img src='" + crown_src + "' /></span>";
	}
	var userName = user.User.full_name;
	var userId = user.User.id;
	var url = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
	url = url.replace(/~user_id/, o.event.user_id);
	var task = o.globalData.tasks[o.event.task_id];
	var commentsUrl = '<?=$this->Html->url(array('controller' => 'ProjectAjax', 'action' => 'comments', '~task_id'))?>';
	commentsUrl = commentsUrl.replace(/~task_id/, task.Task.id);
	var msg = o.globalData.messages[o.event.msg_id].message;
	var str = '<?=__('%s commented task %s', '~userName', '~taskLink')?>';
	if( msg == '&nbsp;' ) str = '<?=__('%s attached file to task %s', '~userName', '~taskLink')?>';
	var taskLink = tmpl('task-post', {ProjectEvent: o.event, title: task.Task.title});
	var userLink = tmpl('user-post', {ProjectEvent: o.event, userName: user.User.full_name});
	var msg_id = o.event.msg_id;

	var hasFiles = false;

	for (var prop in o.globalData.files) {
		val = o.globalData.files[prop];
		if(  val.object_id == msg_id &&  val.object_type == "TaskComment" ) {
			hasFiles = true;
		}
	}

	var fileList = [];
	var val = '';
%}
<div class="event clearfix taskMsg">
	<span class="eventTime">{%=Date.HoursMinutes(js_date, locale)%}</span>
	<a href="{%=url%}" data-user-id="{%=userId%}" class="dropme-files"><img src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=userName%}" class="{%=user.User.rating_class%} ava rounded"></a>
	<span class="articleText">
		<div>{%#str.replace(/~userName/, userLink).replace(/~taskLink/, taskLink)%}{%#crown_html%}</div>
{%
	if( msg != '&nbsp;' ) {
%}
		<div class="description">{%=msg%}</div>
{%
	}
%}
	</span>

{%
	if(hasFiles) {
%}
		<div class="additionalBlock">
{%
		var val = '';
		// ---- не работает на iOS 8.1 - 8.1.3 (ipad) ----- // $.each(o.globalData.files, function(i, val) {
		for (var prop in o.globalData.files) {
			val = o.globalData.files[prop];
			if(  val.object_id == msg_id &&  val.object_type == "TaskComment" ) {
				var file = val;
				var filetype = val.file;
				var fileId = val.id;
				file.url_preview = (iOS && (docFiles.indexOf(file.ext.toLowerCase()) > (-1))) ? '/File/download/'+file.id : file.url_preview;
%}
			<div class="taskFile">
{%
			ext = file.ext.replace(/\./, '').toLowerCase();
			if( (filetype == 'image') && (ext != 'tif') && (ext != 'tiff') ) {
				var url = val.url_download;
				var size = val.orig_w + 'x' + val.orig_h;
%}
				<img src="{%=val.url_img.replace(/noresize/, 'thumb50x50')%}" alt="{%=filetype%}" class="ava link-filetype" style="cursor: pointer;"  data-url="{%=url%}"  data-size="{%=size%}" onclick="showTimeLinePopup($(this))" data-file-id="{%=fileId%}" data-user-id="{%=userId%}"/>
{%
			} else {
%}

				{%
					if (file.converted) {
				%}
                    <a href="javascript: void(0)" target="_blank" class="filetype {%=ext%}" style="cursor: pointer;" data-file-id="{%=fileId%}" data-user-id="{%=userId%}"></a>
				{% } %}

{%
			}
%}
                <div class="articleText">
                    {% if (file.media_type == 'video') { %}
                        <a href="javascript: void(0)" class="link-filetype video-pop-this {%=ext%}" data-file-id="{%=fileId%}" data-user-id="{%=userId%}" data-url-down="{%=file.url_download%}" data-converted="{%=file.converted%}">{%=file.orig_fname%}</a> <a href="javascript: void(0)" onclick="cloudSave('{%=file.id%}')" class="cloudSave"><span class="glyphicons cloud_plus"></span></a>
                    {% } else { %}
                        <a href="{%=file.url_preview%}" target="_blank" class="underline link-filetype {%=ext%}" data-file-id="{%=fileId%}" data-user-id="{%=userId%}">{%=file.orig_fname%}</a> <a href="javascript: void(0)" onclick="cloudSave('{%=file.id%}')" class="cloudSave"><span class="glyphicons cloud_plus"></span></a>
                    {% } %}
                </div>
            </div>

{%
		}
	};
%}
		</div>
{%
	}
%}
{%
	if(user.User.id != <?php echo $currUserID; ?>) {
%}
	<div class="clearfix"></div>
	<div id="taskCommentsMsg-{%=task.Task.id%}" class="commentsContainer">
		<a class="commentLink" href="javascript:void(0);" data-comments-url="{%=commentsUrl%}"></a>
	<div style="clear: both"></div>
	</div>
{%
	}
%}

</div>
</script>
<!-- task messaging -->



<script type="text/x-tmpl" id="task-post">
{%
	var url = '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'task', '~task_id', '#' => 'post~project_event_id'))?>';
	url = url.replace(/~project_event_id/, o.ProjectEvent.id).replace(/~task_id/, o.ProjectEvent.task_id);
%}
<a href="{%=url%}" class="underline taskExpand" data-task-id="{%=o.ProjectEvent.task_id%}">{%=o.title%}</a>
</script>

<script type="text/x-tmpl" id="article-post">
{%
	var url = '<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', '~article_id'))?>';
	url = url.replace(/~article_event_id/, o.Article.id).replace(/~article_id/, o.Article.article_id);
%}
<a href="{%=url%}" class="underline">{%=o.Article.article_title%}</a>
</script>

<script type="text/x-tmpl" id="user-post">
{%
	var url = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
	url = url.replace(/~user_id/, o.ProjectEvent.user_id);
%}
<a href="{%=url%}" class="underline">{%=o.userName%}</a>
</script>

<script type="text/x-tmpl" id="generic-url">
<a href="{%=o.url%}" class="underline">{%=o.text%}</a>
</script>

<script type="text/x-tmpl" id="project-task-file">
{%
	var js_date = Date.fromSqlDate(o.event.created);
	var file = o.globalData.files[o.event.file_id];
	file.url_preview = (iOS && (docFiles.indexOf(file.ext.toLowerCase()) > (-1))) ? '/File/download/'+file.id : file.url_preview;
	var user = o.globalData.users[o.event.user_id];
	var userName = user.User.full_name;
	var userId = user.User.id;
	var url = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
	url = url.replace(/~user_id/, o.event.user_id);
	var task = o.globalData.tasks[o.event.task_id];
	var str = '<?=__('%s commented task %s', '~userName', '~taskLink')?>';
	var taskLink = tmpl('task-post', {ProjectEvent: o.event, title: task.Task.title});
	var str = '<?=__('%s attached file to task %s', '~userName', '~taskLink')?>';
	var filetype = file.file;
	var fileId = file.id;
	var userLink = tmpl('user-post', {ProjectEvent: o.event, userName: user.User.full_name});
%}
<div class="event clearfix taskFile">
	<span class="eventTime">{%=Date.HoursMinutes(js_date, locale)%}</span>
{%
	ext = file.ext.replace(/\./, '').toLowerCase();
	if( (filetype == 'image') && (ext != 'tif') && (ext != 'tiff') ) {
%}
	<img class="ava blockLine link-filetype" alt="{%=filetype%}" src="{%=file.url_img.replace(/noresize/, 'thumb100x100')%}" style="margin: 0 15px; cursor: pointer; float: right;" data-file-id="{%=fileId%}" data-user-id="{%=userId%}">
{%
	} else {
%}
	<a href="{%=file.url_preview%}" target="_blank" class="filetype {%=ext%}" data-file-id="{%=fileId%}" data-user-id="{%=userId%}"></a>
{%
	}
%}
	<div class="text">
		<a href="{%=file.url_preview%}" target="_blank" class="underline link-filetype {%=ext%}" data-file-id="{%=fileId%}" data-user-id="{%=userId%}">{%=file.orig_fname%}</a>
		<div class="description">{%#str.replace(/~userName/, userLink).replace(/~taskLink/, taskLink).replace(/~fileLink/, '"' + file.orig_fname + '"')%}</div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="last_groups">
<div class="event clearfix">
	<?=__('These groups may be interesting for you')?>
{%
	for(var i = 0; i < o.globalData.last_groups.length; i++) {
		var group = o.globalData.last_groups[i];
		var url = '<?=$this->Html->url(array('controller' =>'Group', 'action' => 'view', '~group_id'))?>';
		var members = o.globalData.group_members[group.Group.id];
%}
	<div class="joinBlock clearfix">
		<img src="{%=group.GroupMedia.url_img.replace(/noresize/, 'thumb50x50')%}" alt="{%=group.Group.title%}" class="{%=group.Group.rating_class%} rounded">
		<div class="inner">
			<a href="{%=url.replace(/~group_id/g, group.Group.id)%}" class="title">{%=group.Group.title%}</a>
			<div class="count">{%=members.length%} <?=__('member(s)')?></div>
		</div>
		<div class="clearfix"></div>
		<div class="description">{%=group.Group.descr%}</div>
	</div>
{%
	}
%}
</div>
</script>

<!-- Last articles template -->
<script type="text/x-tmpl" id="last_articles">
<div class="event clearfix">
	<?=__('These articles may be interesting for you')?>
	<br />
	<br />
{%
	for(var i = 0; i < o.globalData.last_articles.length; i++) {
		var article = o.globalData.last_articles[i].Article;
		var url = '<?=$this->Html->url(array('controller' =>'Article', 'action' => 'view', '~article_id'))?>';

		var user = o.globalData.users[article.owner_id];
		var group = o.globalData.groups[article.group_id];
		var url = '<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', '~id'))?>';
		url = url.replace(/~id/, article.id);
		var commentsUrl = '<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' => 'timeline_comments', '~id'))?>';
		commentsUrl = commentsUrl.replace(/~id/, article.id);
%}
	<div class="event lastArticle clearfix">
{%
		if(group != null) {
			var groupUrl = '<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', '~id'))?>';
%}
		<a href="{%=groupUrl.replace(/~id/, group.Group.id)%}"><img class="{%=group.Group.rating_class%} ava rounded" src="{%=group.GroupMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=group.Group.title%}" /></a>
		<span class="articleText">
			<div><a href="{%=groupUrl.replace(/~id/, group.Group.id)%}" class="underline">{%=group.Group.title%}</a>  <?=__('published an article')?></div>
{%
		} else {
			var userUrl = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~id'))?>';
%}
		<a href="{%=userUrl.replace(/~id/, user.User.id)%}"><img class="{%=user.User.rating_class%} ava rounded" src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=user.User.full_name%}" /></a>
		<span class="articleText">
			<div><a href="{%=userUrl.replace(/~id/, user.User.id)%}" class="underline">{%=user.User.full_name%}</a>  <?=__('published an article')?></div>
{%
		}
%}
			<a href="{%=url%}" class="description">{%=article.title%}</a>
		</span>
		<div class="clearfix"></div>
		<div id="articleComments-{%=article.id%}" class="commentsContainer">
			<a class="commentLink" href="javascript:void(0);" data-comments-url="{%=commentsUrl%}"></a>
	  <div style="clear: both"></div>
		</div>
	</div>
{%
	}
%}
</div>
</script>

<!-- Most popular articles -->
<script type="text/x-tmpl" id="popular_articles">
<div class="event clearfix popular-articles">
    <div class="text-center text-uppercase">
		<p class="info-dream"><?=__('Read interesting articles')?></p>
	</div>
{%

    for(var i = 0; i < o.globalData.popular_articles.length; i++) {
        var article = o.globalData.popular_articles[i].Article;
        var created = new Date(article.created);
        var day = created.getDate();
        var month = created.getMonth();
        var mL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var mS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
		//TODO: restore counts for popular articles
		var count = 0;
        //var count = o.globalData.popular_articles[i].Count.cnt;
        if (o.globalData.popular_articles[i].Media != undefined) {
            var articleImg = o.globalData.popular_articles[i].Media.url_img;
        }
        else {
            var articleImg = '';
        }

        var url = '<?=$this->Html->url(array('controller' =>'Article', 'action' => 'view', '~article_id'))?>';
        url = url.replace(/~article_id/, article.id);
        var user = o.globalData.users[article.owner_id];
%}
    <div class="event popularArticle clearfix">
{%
            var userUrl = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~id'))?>';
%}

        <div class="popular-articleText container-fluid">
            <div class="row">
                <div class="col-xs-4">
                    <div class="article-img">
                        <img src="{%=articleImg.replace(/noresize/, 'thumb100x100')%}">
						<a href="{%=userUrl.replace(/~id/, user.User.id)%}"><img class="{%=user.User.rating_class%} ava rounded" src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=user.User.full_name%}" /></a>
                    </div>
                </div>
				<div class="col-xs-8 article-text-info">
					<div class="article-digits">
						<span class="glyphicons calendar create-date col-xs-5">{%=day%} {%=mS[month]%}</span>
						<!--span class="glyphicons eye_open count col-xs-5">{%=count%}</span-->
					</div>
					<div class="article-info">
						<a href="{%=userUrl.replace(/~id/, user.User.id)%}" class="underline">{%=user.User.full_name%}</a> <?=__('published an article')?>
					</div>
					<div class="description">
						<a href="{%=url%}">{%=article.title%}</a>
					</div>
				</div>
            </div>
        </div>
        <div class="clearfix"></div>
	</div>
{%
    }
%}

</div>
</script>

<script type="text/x-tmpl" id="joined-group">
{%
	//
	//Group is created when user registered on site
	//
	var group = o.globalData.groups[o.event.group_id];
	var url = '<?=$this->Html->url(array('controller' =>'Group', 'action' => 'view', '~group_id'))?>';
	var userUrl = '<?=$this->Html->url(array('controller' =>'User', 'action' => 'view', '~user_id'))?>';
	var members = o.globalData.group_members[o.event.group_id];
%}
<div class="event clearfix group-block">
    <div class="joinBlock clearfix">
        <div class="info clearfix">
            <img src="{%=group.GroupMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=group.Group.title%}" class="{%=group.Group.rating_class%}  rounded">
            <div class="inner">
                <div class="count">{%=members.length%} <?=__('member(s)')?></div>
                <a href="{%=url.replace(/~group_id/g, group.Group.id)%}" class="title" data-group-id="{%=o.event.group_id%}">{%=group.Group.title%}</a>
                <div class="group-text"><?=__('You became an Administrator of your personal community. Assemble a team, create a project, set a task, achieve your dreams')?></div>
            </div>

        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="create-new-group container-fluid">
	<div class="row text-center">
		<div class="col-xs-10"><p class="create-new-group-text"><?=__('Or create more community')?></p></div>
		<div class="col-xs-2"><a href="/Group/edit"><span class="create-new-group-plus glyphicons plus"></span></a></div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="interesting-project">
<div class="event clearfix interesting-project">

    <div class="event interestingProject-block clearfix">
        <div class="interestingProject-text container-fluid">
            <div class="row">
                <div class="col-xs-4">
                    <div class="interestingProject-img">
                        <img src="/img/proj_photo_3.jpg">
						<a href="javascript: void(0)"><img class="thumb avatar ava rounded" src="/img/mini_logo.png" alt="" /></a>
                    </div>
                </div>
				<div class="col-xs-8 interestingProject-text-info">
					<div class="interestingProject-info">
						В сообществе <a href="javascript: void(0)">Young dev team</a> <br>была создана задача
					</div>
					<div class="interestingProject-description">
						<a href="https://konstruktor.com/User/task/1329" target="_blank">"Верстальщик в команду"</a>
					</div>
				</div>
            </div>
            <div class="interestingProject-digits">
				<span class="glyphicons money-card col-xs-4">$700</span>
				<span class="glyphicons calendar col-xs-4">29 Июня</span>
				<span class="glyphicons duration col-xs-4">14 дней</span>
			</div>
        </div>
        <div class="clearfix"></div>
	</div>
    <div class="event interestingProject-block clearfix">
        <div class="interestingProject-text container-fluid">
            <div class="row">
                <div class="col-xs-4">
                    <div class="interestingProject-img">
                        <img src="/img/proj_photo_2.jpg">
						<a href="#"><img class="thumb avatar ava rounded" src="/img/mini_logo.png" alt="" /></a>
                    </div>
                </div>
				<div class="col-xs-8 interestingProject-text-info">
					<div class="interestingProject-info">
						В сообществе <a href="javascript: void(0)">Outsource Development</a> <br>была создана задача
					</div>
					<div class="interestingProject-description">
						<a href="https://konstruktor.com/User/task/1330" target="_blank">"Разработчик в команду (JavaScript)"</a>
					</div>
				</div>
            </div>
            <div class="interestingProject-digits">
				<span class="glyphicons money-card col-xs-4">$700</span>
				<span class="glyphicons calendar col-xs-4">29 Июня</span>
				<span class="glyphicons duration col-xs-4">14 дней</span>
			</div>
        </div>
        <div class="clearfix"></div>
	</div>
    <div class="event interestingProject-block clearfix">
        <div class="interestingProject-text container-fluid">
            <div class="row">
                <div class="col-xs-4">
                    <div class="interestingProject-img">
                        <img src="/img/proj_photo_1.jpg">
						<a href="#"><img class="thumb avatar ava rounded" src="/img/mini_logo.png" alt="" /></a>
                    </div>
                </div>
				<div class="col-xs-8 interestingProject-text-info">
					<div class="interestingProject-info">
						В сообществе <a href="javascript: void(0)">Pers and Co</a> <br>была создана задача
					</div>
					<div class="interestingProject-description">
						<a href="https://konstruktor.com/User/task/1328" target="_blank">"Необходимо QA в проект"</a>
					</div>
				</div>
            </div>
            <div class="interestingProject-digits">
				<span class="glyphicons money-card col-xs-4">$700</span>
				<span class="glyphicons calendar col-xs-4">29 Июня</span>
				<span class="glyphicons duration col-xs-4">14 дней</span>
			</div>
        </div>
        <div class="clearfix"></div>
	</div>
</div>
</script>



<script type="text/x-tmpl" id="cloud-share">
{%
if(o.share.Cloud['user_id'] != undefined) {
	var user = o.globalData.users[o.share.Cloud.user_id];
	var share = o.share.Cloud;
	var media = o.share.Media;

	var url = '<?=$this->Html->url(array('controller' =>'User', 'action' => 'view', '~user_id'))?>';
	url = url.replace(/~group_id/, user.User.id);
	var string = '<?=__('New shared file')?>';
	var accsess = ['','<?= __('Access by link for') ?>','<?= __('Individual access for') ?>','<?= __('Edit access for') ?>'];
%}
<div class="event clearfix" id="cloud-share-{%=o.share.Share.id %}">
	{%#string%}
	<div class="joinBlock clearfix">
		<div class="info clearfix">
			<img src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=user.User.full_name%}" class="{%=user.User.rating_class%} rounded">
			<div class="inner">
				<a href="{%=url.replace(/~user_id/g, user.User.id)%}" class="title">{%=user.User.full_name%}</a>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="description">{%=accsess[o.share.Share.share_type]%} <a href="{%=media.url_download%}" class="title"> {%=share.name%}</a></div>
	</div>
</div>
{% } %}
</script>
<script type="text/x-tmpl" id="invite-group">
{%
	var group = o.globalData.groups[o.GroupMember.group_id];
	var url = '<?=$this->Html->url(array('controller' =>'Group', 'action' => 'view', '~group_id'))?>';
	url = url.replace(/~group_id/, group.Group.id);
	var groupRef = tmpl('generic-url', {url: url, text: group.Group.title});
	var string = '<?=__('You have been invited to group %s as "%s"', '~groupUrl', '~role')?>';
	string = string.replace(/~groupUrl/, groupRef).replace(/~role/, o.GroupMember.role);
	var members = o.globalData.group_members[o.GroupMember.group_id];
%}
<div class="event clearfix" id="invite-request-{%=o.GroupMember.id%}">
	{%#string%}
	<div class="joinBlock clearfix">
		<div class="info clearfix">
			<img src="{%=group.GroupMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=group.Group.title%}" class="{%=group.Group.rating_class%}  rounded">
			<div class="inner">
				<a href="{%=url.replace(/~group_id/g, group.Group.id)%}" class="title">{%=group.Group.title%}</a>
				<div class="count">{%=members.length%} <?=__('member(s)')?></div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="description">{%=group.Group.descr%}</div>
		<div class="buttons pull-left">
			<div class="btn btn-primary" onclick="Timeline.acceptInvite('{%=group.Group.id%}', '{%=o.GroupMember.id%}');"><?=__('Accept')?></div>
			<div class="btn btn-default" onclick="Timeline.declineInvite('{%=group.Group.id%}', '{%=o.GroupMember.id%}');"><?=__('Decline')?></div>
		</div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="join-group-request">
{%

	var user = o.globalData.users[o.GroupMember.user_id];
	var userUrl = '<?=$this->Html->url(array('controller' =>'User', 'action' => 'view', '~user_id'))?>';

	if(user && user.User){
	userUrl = userUrl.replace(/~user_id/, user.User.id);
	var userRef = tmpl('generic-url', {url: userUrl, text: user.User.full_name});

	var group = o.globalData.groups[o.GroupMember.group_id];
	var groupUrl = '<?=$this->Html->url(array('controller' =>'Group', 'action' => 'view', '~group_id'))?>';
	groupUrl = groupUrl.replace(/~group_id/, group.Group.id);
	var groupRef = tmpl('generic-url', {url: groupUrl, text: group.Group.title});

	var string = '<?=__('User %s wants to join your group %s', '~userUrl', '~groupUrl')?>';
	string = string.replace(/~userUrl/, userRef).replace(/~groupUrl/, groupRef);
		if(group.Group.is_dream == 1) {
		var crown_src = '<?php echo $this->webroot . "img/group/crawn-s.png";?>';
		var crown_html = "<span><img width='19' src='" + crown_src + "' /></span>";
		string += crown_html
	}

%}
<div class="event clearfix" id="join-request-{%=o.GroupMember.id%}">
	{%#string%}
	<div class="joinBlock clearfix">
		<div class="info clearfix">
			<img src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=user.User.full_name%}" class="{%=user.User.rating_class%}  rounded">
			<div class="inner">
				<a href="{%=userUrl%}" class="title">{%=user.User.full_name%}</a>
				<div class="count">{%=user.User.skills%}</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="description"></div>
		<div class="buttons pull-right">
			<div class="btn btn-primary" onclick="Timeline.initJoin($(this))"><?=__('Accept')?></div>
			<div class="btn btn-default" onclick="Timeline.declineJoin('{%=user.User.id%}', '{%=group.Group.id%}', '{%=o.GroupMember.id%}');"><?=__('Decline')?></div>
		</div>
		<div class="accept-buttons hidden" style="width: 100%;">
			<div class="buttons pull-right">
				<div class="btn btn-primary" onclick="Timeline.acceptJoin('{%=user.User.id%}', '{%=group.Group.id%}', '{%=o.GroupMember.id%}');"><?=__('Accept')?></div>
				<div class="btn btn-default" onclick="Timeline.declineJoin('{%=user.User.id%}', '{%=group.Group.id%}', '{%=o.GroupMember.id%}');"><?=__('Decline')?></div>
			</div>
			<input placeholder="<?=__('Role')?>..." style="width: 100%">
		</div>
	</div>
</div>
{%
	}
%}
</script>

<script type="text/x-tmpl" id="joined-project">
{%
	var project = o.globalData.projects[o.event.project_id];
	var url = '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'view', '~project_id'))?>';
	var members = o.globalData.project_members[o.event.project_id];
%}
<div class="event clearfix project-block">
	<?=__('You joined this project')?>
	<div class="joinBlock clearfix">
		<a href="{%=url.replace(/~project_id/g, project.Project.id)%}" class="title" data-project-id="{%=o.event.project_id%}">{%=project.Project.title%}</a>
		<div class="count">{%=members.length%} <?=__('member(s)')?></div>
		<div class="clearfix"></div>
		<div class="description">{%=project.Project.descr%}</div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="konstructor-creation">
{%
	var js_date = Date.fromSqlDate(o.event.created);
%}
<div class="col-md-12 col-sm-12 col-xs-12 day-data t-a-center">
	<div class="day-calendar konstructor">
		<div class="date">{%=js_date.getDate()%} {%=aMonths[js_date.getMonth()]%} {%=js_date.getFullYear()%}</div>
		<div class="weekday">{%=aDays[js_date.getDay()]%}</div>
		<div class="start-project">
			<img src="/img/user-profile/t_logo2.png" alt="This site was created during long sleepless nights..." />
			<?=__('Updated site began to work')?>
		</div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="self-registered">
{%
	var js_date = Date.fromSqlDate(o.event.created);
%}
<div class="event clearfix">
	<div class="event-text">
		<div class="h2-title">{%=Date.fullDate(js_date)%} {%=Date.HoursMinutes(js_date, locale)%}</div>
		<p>{%=o.event.msg%}</p>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="last-registered">
<div class="event clearfix">
<?=__('These users may be interesting for you')?>
	<div style="margin-top: 5px;">
{%
	for(var i = 0; i < o.globalData.last_users.length; i++) {
		var user = o.globalData.users[o.globalData.last_users[i].User.id];
		var url = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
%}
		<a href="{%=url.replace(/~user_id/g, user.User.id)%}">
			<img class="{%=user.User.rating_class%} ava rounded" alt="{%=user.User.full_name%}" src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" style="margin: 0; cursor: pointer;">
		</a>&nbsp;
{%
	}
%}
	</div>
</div>
</script>

<script type="text/x-tmpl" id="timeline-bottom">
	<div class="periodItem">
		<div class="calendar">
			<div class="date">5 <?=__('March')?></div>
			<i class="fa fa-circle divider"></i>
			<div class="weekday"><?=__('Thursday')?></div>
		</div>
		<div class="defaultEvent right">
			<div class="value">{%=o.globalData.counters.articles%}</div>
			<div class="description"><?=__('articles written.')?></div>
		</div>
	</div>
	<div class="periodItem">
		<div class="calendar">
			<div class="date">20 <?=__('January')?></div>
			<i class="fa fa-circle divider"></i>
			<div class="weekday"><?=__('Tuesday')?></div>
		</div>
		<div class="defaultEvent left">
			<div class="value">{%=o.globalData.counters.messages%}</div>

			<div class="description"><?=__('messages written.')?></div>
		</div>
	</div>
	<div class="periodItem">
		<div class="calendar">
			<div class="date">16 <?=__('Dec')?></div>
			<i class="fa fa-circle divider"></i>
			<div class="weekday"><?=__('Tuesday')?></div>
		</div>
		<div class="defaultEvent right">
			<div class="value">{%=o.globalData.counters.projects%}</div>

			<div class="description"><?=__('projects created based on groups.')?></div>
		</div>
	</div>
	<div class="periodItem">
		<div class="calendar">
			<div class="date">25 <?=__('November')?></div>
			<i class="fa fa-circle divider"></i>
			<div class="weekday"><?=__('Tuesday')?></div>
		</div>
		<div class="defaultEvent left">
			<div class="value">{%=o.globalData.counters.groups%}</div>

			<div class="description"><?=__('groups created.')?></div>
		</div>
	</div>
	<div class="periodItem">
		<div class="calendar">
			<div class="date">12 <?=__('November')?></div>
			<i class="fa fa-circle divider"></i>
			<div class="weekday"><?=__('Wednesday')?></div>
		</div>
		<div class="defaultEvent right">
			<div class="siteIsUpdated">
				<img src="/img/timeline_logo.png" alt="" />
				<?=__('Updated site')?>
			</div>
		</div>
	</div>
</script>

<script type="text/x-tmpl" id="konstruktor-start">
	<div class="periodItem">
		<div class="calendar">
			<div class="date">12 <?=__('November')?></div>
			<i class="fa fa-circle divider"></i>
			<div class="weekday"><?=__('Wednesday')?></div>
		</div>
		<div class="defaultEvent right">
			<div class="siteIsUpdated">
				<img src="/img/timeline_logo.png" alt="" />
				<?=__('Updated site')?>
			</div>
		</div>
	</div>
</script>

<script type="text/x-tmpl" id="event-selector">
{%
	var side = o.side;
	var sql_date = o.sql_date;
	var hours = o.hours;
	var action = o.action;
%}
	<div class="event-select collapsed">
		<input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open"/>
		<label class="menu-open-button">
			<span class="hamburger hamburger-1"></span>
			<span class="hamburger hamburger-2"></span>
			<span class="hamburger hamburger-3"></span>
		</label>
		<div class="menu-item"><a href="#" class="menu-inner-item sprite-icon meet" data-type="meet" data-side={%=side%} data-sqldate={%=sql_date%} data-hours={%=hours%} data-action={%=action%}></a></div>
		<div class="menu-item"><a href="#" class="menu-inner-item sprite-icon mail" data-type="mail" data-side={%=side%} data-sqldate={%=sql_date%} data-hours={%=hours%} data-action={%=action%}></a></div>
		<div class="menu-item"><a href="#" class="menu-inner-item sprite-icon task" data-type="task" data-side={%=side%} data-sqldate={%=sql_date%} data-hours={%=hours%} data-action={%=action%}></a></div>
		<div class="menu-item"><a href="#" class="menu-inner-item sprite-icon call" data-type="call" data-side={%=side%} data-sqldate={%=sql_date%} data-hours={%=hours%} data-action={%=action%}></i></a></div>
		<div class="menu-item"><a href="#" class="menu-inner-item sprite-icon entartain" data-type="entertain" data-side={%=side%} data-sqldate={%=sql_date%} data-hours={%=hours%} data-action={%=action%}></a></div>
		<div class="menu-item"><a href="/Cloud/documentEdit" class="menu-inner-item sprite-icon document" data-type="document"></a></div>
		<div class="menu-item"><a href="#" class="menu-inner-item sprite-icon sport" data-type="sport" data-side={%=side%} data-sqldate={%=sql_date%} data-hours={%=hours%} data-action={%=action%}></a></div>
		<!--div class="menu-item"><a href="#" class="menu-inner-item sprite-icon" data-type="purchase" data-side={%=side%} data-sqldate={%=sql_date%} data-hours={%=hours%} data-action={%=action%}></a></div-->
		<div class="menu-item"><a href="#" class="menu-inner-item sprite-icon pay" data-type="pay" data-side={%=side%} data-sqldate={%=sql_date%} data-hours={%=hours%} data-action={%=action%}></a></div>
	</div>
	<div class="clearfix"></div>
</script>







<!-- GROUP EXPAND -->

<script type="text/x-tmpl" id="group-state-day">
{%
	$.each(o.data.Render_list, function(key, data) {
		var renderDate = Date.fromSqlDate(key);
		var dayoff= renderDate.getDay() > 5 ? ' dayOff' : '';
		var html = '';
%}
		<div id="row-day_{%=key%}" class="periodItem">
			<div id="day{%=key%}" class="calendar{%=dayoff%}" data-type="day" data-date="{%=key%}">
				<div class="date">{%=renderDate.getDate()%}  {%=aMonths[renderDate.getMonth()]%}</div>
				<i class="fa fa-circle divider"></i>
				<div class="weekday">{%=aDays[renderDate.getDay()]%}</div>
			</div>
			<div id="timeline_2015-10-14_2300" class="eventListBlock clearfix">

				<div class="leftSide">
{%
		$.each(data, function(num, eventData) {
			switch(eventData.type) {
				case 'member':
					html = tmpl('groupView-join', {event: eventData, data: o.data.Members[eventData.id]});
					%} {%#html%} {%
					break;
				case 'project':
					html = tmpl('groupView-project', {event: eventData, data: o.data.Projects[eventData.id]});
					%} {%#html%} {%
					break;
				default:
				  break;
			}
		});
%}
				</div>
				<div class="rightSide">
{%
		$.each(data, function(num, eventData) {
			switch(eventData.type) {
				case 'group':
					%} group {%
					break;
				case 'article':
					html = tmpl('groupView-article', {event: eventData, data: o.data.Articles[eventData.id]});
					%} {%#html%} {%
					break;
				default:
				  break;
			}
		});
%}
				</div>

				<div class="clearfix"></div>
			</div>
		</div>
{%
	});
%}
</script>

<script type="text/x-tmpl" id="groupView-join">
{%
	var userUrl = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
	userUrl = userUrl.replace(/~user_id/, o.data.user_id);
	var userRef = tmpl('generic-url', {url: userUrl, text: o.data.full_name});
	var userLink = '<?=__('%s joined group as %s', '~userlink', '<strong>~role</strong>')?>';
	userLink = userLink.replace(/~userlink/, userRef).replace(/~role/, o.data.role);
%}
<div class="event clearfix">
	{%#userLink%}
	<div class="joinBlock clearfix">
		<div class="info clearfix">
			<img src="{%=o.data.img_url.replace(/noresize/, 'thumb100x100')%}" alt="{%=o.data.full_name%}" class="thumb rounded">
			<div class="inner">
				<a href="{%=userUrl%}" class="title">{%=o.data.full_name%}</a>
				<div class="count">{%=o.data.skills%}</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="description"></div>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="groupView-article">
{%
	var articleUrl = '<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', '~article_id'))?>';
	articleUrl = articleUrl.replace(/~article_id/, o.data.id);
	var articleRef = tmpl('generic-url', {url: articleUrl, text: o.data.title});
%}
<div class="event clearfix">
	<a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view'))?>/161">
		<img class="ava rounded" src="{%=o.data.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=o.data.title%}" />
	</a>
	<span class="articleText">
		<div><?=__('A new article was published')?></div>
		<div class="description">{%#articleRef%}</div>
	</span>
	<div class="clearfix"></div>
</div>
</script>

<script type="text/x-tmpl" id="groupView-project">
{%
	var projectUrl = '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'view', '~article_id'))?>';
	projectUrl = projectUrl.replace(/~article_id/, o.data.id);
	var projectRef = tmpl('generic-url', {url: projectUrl, text: o.data.title});

	var state = '';
	if(o.data.closed == true) {
		state = '<?=__('closed')?>';
	} else {
		state = (o.data.created == o.data.last_update) ? '<?=__('created')?>' : '<?=__('updated')?>';
	}
%}
<div class="event clearfix groupView-project">
	<?=__('Project was ')?><strong>{%=state%}</strong>
	<div class="joinBlock clearfix">
		<a href="{%=projectUrl%}" class="title" data-project-id="{%=o.data.id%}">{%=o.data.title%}</a>
		<div class="description">{%=o.data.descr%}</div>
	</div>
</div>
</script>

<!-- PROJECT EXPAND -->

<script type="text/x-tmpl" id="project-state-day">
{%
	$.each(o.data.Render_list, function(key, data) {
		var renderDate = Date.fromSqlDate(key);
		var dayoff= renderDate.getDay() > 5 ? ' dayOff' : '';
		var html = '';
%}
		<div id="row-day_{%=key%}" class="periodItem">
			<div id="day{%=key%}" class="calendar{%=dayoff%}" data-type="day" data-date="{%=key%}">
				<div class="date">{%=renderDate.getDate()%}  {%=aMonths[renderDate.getMonth()]%}</div>
				<i class="fa fa-circle divider"></i>
				<div class="weekday">{%=aDays[renderDate.getDay()]%}</div>
			</div>
			<div class="eventListBlock clearfix">

				<div class="leftSide"></div>
				<div class="rightSide">
{%
		$.each(data, function(num, eventData) {
			switch(eventData.type) {
				case 'task':
					html = tmpl('projectView-task', {event: eventData, data: o.data['task-list'][eventData.id]});
					%} {%#html%} {%
					break;
				default:
					break;
			}
		});
%}
				</div>

				<div class="clearfix"></div>
			</div>
		</div>
{%
	});
%}
</script>

<script type="text/x-tmpl" id="projectView-task">
{%
	var projectUrl = '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'task', '~task_id'))?>';
	projectUrl = projectUrl.replace(/~task_id/, o.data.task_id);
	var projectRef = tmpl('generic-url', {url: projectUrl, text: o.data.title});

	state = '';
	switch(o.data.event_type) {
		case '3':
			state = "<?=__('Task was created')?>"
			break;
		case '6':
			state = "<?=__('Task was closed')?>"
			break;
		case '7':
			state = "<?=__('Task was updated')?>"
			break;
		default:
			break;
	}
	state = state.replace(/updated|created|closed/gi, function(str) {
		return "<b>"+str+"</b>";
	});
%}
<div class="event clearfix projectView-task">
	{%=state%}
	<div class="joinBlock clearfix">
		<a href="{%=projectUrl%}" class="title taskLink" data-task-id="{%=o.data.task_id%}">{%=o.data.title%}</a>
	</div>
</div>
</script>

<!-- PROJECT EXPAND -->

<script type="text/x-tmpl" id="task-state-day">
{%
	$.each(o.data.Render_list, function(key, data) {
		var renderDate = Date.fromSqlDate(key);
		var dayoff= renderDate.getDay() > 5 ? ' dayOff' : '';
		var html = '';
%}
		<div id="row-day_{%=key%}" class="periodItem">
			<div id="day{%=key%}" class="calendar{%=dayoff%}" data-type="day" data-date="{%=key%}">
				<div class="date">{%=renderDate.getDate()%}  {%=aMonths[renderDate.getMonth()]%}</div>
				<i class="fa fa-circle divider"></i>
				<div class="weekday">{%=aDays[renderDate.getDay()]%}</div>
			</div>
			<div class="eventListBlock clearfix">

				<div class="leftSide">
{%
				$.each(data, function(num, eventData) {
					if(!eventData.own) {
						switch(eventData.type) {
							case 'comment':
								var taskId = eventData.id;
								var userId = o.data['events'][taskId]['user_id'];
								html = tmpl('taskView-file-comment', {event: eventData, data: o.data['events'][taskId], user: o.data['users'][userId], task: o.data.task});
								%} {%#html%} {%
								break;
							case 'file':
								var taskId = eventData.id;
								var userId = o.data['events'][taskId]['user_id'];
								html = tmpl('taskView-file-comment', {event: eventData, data: o.data['events'][taskId], user: o.data['users'][userId], task: o.data.task});
								%} {%#html%} {%
								break;
							case 'file_comment':
								var taskId = eventData.id;
								var userId = o.data['events'][taskId]['user_id'];
								html = tmpl('taskView-file-comment', {event: eventData, data: o.data['events'][taskId], user: o.data['users'][userId], task: o.data.task});
								%} {%#html%} {%
								break;
							case 'open':
								%} <strong><?=__('Task was created')?></strong><br> {%
								break;
							case 'close':
								%} <strong><?=__('Task was closed')?></strong><br> {%
								break;
							default:
								break;
						}
					}
				});
%}
				</div>
				<div class="rightSide">
{%
		$.each(data, function(num, eventData) {
			if(eventData.own) {
				switch(eventData.type) {
					case 'comment':
						var taskId = eventData.id;
						var userId = o.data['events'][taskId]['user_id'];
						html = tmpl('taskView-file-comment', {event: eventData, data: o.data['events'][taskId], user: o.data['users'][userId], task: o.data.task});
						%} {%#html%} {%
						break;
					case 'file':
						var taskId = eventData.id;
						var userId = o.data['events'][taskId]['user_id'];
						html = tmpl('taskView-file-comment', {event: eventData, data: o.data['events'][taskId], user: o.data['users'][userId], task: o.data.task});
						%} {%#html%} {%
						break;
					case 'file_comment':
						var taskId = eventData.id;
						var userId = o.data['events'][taskId]['user_id'];
						html = tmpl('taskView-file-comment', {event: eventData, data: o.data['events'][taskId], user: o.data['users'][userId], task: o.data.task});
						%} {%#html%} {%
						break;
					case 'open':
						%} <strong><?=__('Task was created')?></strong><br> {%
						break;
					case 'close':
						%} <strong><?=__('Task was closed')?></strong><br> {%
						break;
					default:
						break;
				}
			}
		});
%}
				</div>

				<div class="clearfix"></div>
			</div>
		</div>
		<br>
		<br>
		<br>
{%
	});
%}
</script>

<script type="text/x-tmpl" id="taskView-file-comment">
{%
	var user = o.user;
	var userName = user.User.full_name;
	var userId = user.User.id;
	var url = '<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', '~user_id'))?>';
	url = url.replace(/~user_id/, o.event.user_id);

	var task = o.task;
	var commentsUrl = '<?=$this->Html->url(array('controller' => 'ProjectAjax', 'action' => 'comments', '~task_id'))?>';
	commentsUrl = commentsUrl.replace(/~task_id/, task.Task.id);
	var msg = '';
	if(o.data.msg_id != null){
		msg = o.data.message.message;
	}
	var str = '<?=__('%s commented task %s', '~userName', '~taskLink')?>';
	if( msg == '&nbsp;' ) str = '<?=__('%s attached file to task %s', '~userName', '')?>';
	var taskLink = tmpl('task-post', {ProjectEvent: o.event, title: task.Task.title});
	var userLink = tmpl('user-post', {ProjectEvent: o.event, userName: user.User.full_name});
	var msg_id = o.event.msg_id;

	var hasFiles = false;

	if(  o.data.media != undefined ) {
		hasFiles = true;
	}

	var val = '';
%}
<div class="event clearfix taskMsg">
	<a href="{%=url%}" data-user-id="{%=userId%}" class="dropme-files"><img src="{%=user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="{%=userName%}" class="{%=user.User.rating_class%} ava rounded"></a>
	<span class="articleText">
		<div>{%#str.replace(/~userName/, userLink).replace(/~taskLink/, taskLink)%}</div>
{%
	if( msg != '&nbsp;' ) {
%}
		<div class="description">{%=msg%}</div>
{%
	}
%}
	</span>
{%
	if(hasFiles) {
%}
		<div class="additionalBlock">
{%
		var val = '';
		// ---- не работает на iOS 8.1 - 8.1.3 (ipad) ----- // $.each(o.globalData.files, function(i, val) {
		for (var val in o.data.media) {
			var file = o.data.media[val];
			var filetype = file.file;
			var fileId = file.id;
			file.url_preview = (iOS && (docFiles.indexOf(file.ext.toLowerCase()) > (-1))) ? '/File/download/'+file.id : file.url_preview;
%}
			<div class="taskFile">
{%
			ext = file.ext.replace(/\./, '').toLowerCase();
			if( (filetype == 'image') && (ext != 'tif') && (ext != 'tiff') ) {
%}
				<img src="{%=file.url_img.replace(/noresize/, 'thumb50x50')%}" alt="{%=filetype%}" class="ava link-filetype" style="cursor: pointer;" data-file-id="{%=fileId%}" data-user-id="{%=userId%}">
{%
			} else {
%}
				<a href="javascript: void(0)" target="_blank" class="filetype {%=ext%}" style="cursor: pointer;" data-file-id="{%=fileId%}" data-user-id="{%=userId%}"></a>
{%
			}
%}
				<div class="articleText">
					<a href="{%=file.url_preview%}" target="_blank" class="underline link-filetype {%=ext%}" data-file-id="{%=fileId%}" data-user-id="{%=userId%}">{%=file.orig_fname%}</a> <a href="javascript: void(0)" onclick="cloudSave('{%=file.id%}')" class="cloudSave"><span class="glyphicons cloud_plus"></span></a>
				</div>
			</div>
{%
		};
%}
		</div>
{%
		}
%}
{%
	if(user.User.id != <?php echo $currUserID; ?>) {
%}
	<div class="clearfix"></div>
	<div id="taskCommentsMsg-{%=task.Task.id%}" class="commentsContainer">
		<a class="commentLink" href="javascript:void(0);" data-comments-url="{%=commentsUrl%}"></a>
	<div style="clear: both"></div>
	</div>
{%
	}
%}

</div>
</script>
