<div class="modal fade" id="ww"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<?=$this->Form->create('Operation', array('url' => array('controller' => 'Project', 'action' => 'taskAccountManage'), 'class' => 'clearfix'	))?>
					<div class="pull-left" style="width: 150px;">
						<?=$this->Form->hidden('task_id', array('value' => Hash::get($task, 'Task.id')))?>
						<div class="form-group">
							<label><?=__('Revenue')?></label>
							<?=$this->Form->input('income', array('label' => false, 'div' => false, 'placeholder' => '0.00', 'class' => 'form-control income', 'type' => 'number', 'step' => '0.01'))?>
						</div>
						<div class="form-group">
							<label><?=__('Taxes')?></label>
							<?=$this->Form->input('tax', array('label' => false, 'div' => false, 'placeholder' => '0.00', 'class' => 'form-control tax', 'type' => 'number', 'step' => '0.01'))?>
						</div>
						<div class="form-group">
							<label><?=__('Net Income')?></label>
							<input type="text" placeholder="" class="form-control total" value="" readonly="true">
						</div>
						<button type="submit" class="btn btn-primary"><?=__('Add')?></button>
					</div>
					<div class="pull-right" style="width: 150px;">
						<div class="form-group">
							<label><?=__('Expences')?></label>
							<?=$this->Form->input('expense', array('label' => false, 'div' => false, 'placeholder' => '0.00', 'class' => 'form-control expense', 'type' => 'number', 'step' => '0.01'))?>
						</div>
						<div class="form-group">
							<label><?=__('Margin')?></label>
							<?=$this->Form->input('percent', array('label' => false, 'div' => false, 'placeholder' => '0.00', 'class' => 'form-control percent', 'type' => 'number', 'step' => '0.01'))?>
						</div>
					</div>
				<?=$this->Form->end()?>
			</div>
		</div>
	</div>
</div>

<div class="modal calculatorModal fade" id="calculator"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<div class="calculator">
					<div class="value">999999</div>
					<button class="btn btn-default reset">C</button>
					<button class="btn btn-default">±</button>
					<button class="btn btn-default">÷</button>
					<button class="btn btn-default">×</button>
					<button class="btn btn-default">7</button>
					<button class="btn btn-default">8</button>
					<button class="btn btn-default">9</button>
					<button class="btn btn-default">-</button>
					<button class="btn btn-default">4</button>
					<button class="btn btn-default">5</button>
					<button class="btn btn-default">6</button>
					<button class="btn btn-default">+</button>
					<div class="colomn">
						<button class="btn btn-default">1</button>
						<button class="btn btn-default">2</button>
						<button class="btn btn-default">3</button>
						<button class="btn btn-default">0</button>
						<button class="btn btn-default">,</button>
						<button class="btn btn-default">%</button>
					</div>
					<button class="btn btn-primary equally">=</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!--div class="modal fade eventTypeModal" id="qq"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<?=$this->Form->create('UserEvent', array('url' => array('controller' => 'Project', 'action' => 'addUserEvent')))?>
					<div class="form-group">
						<label><?=__('Event title')?></label>
						<?=$this->Form->input('UserEvent.title', array('label' => false, 'div' => false, 'value' => Hash::get($task, 'Task.title'), 'class' => 'form-control', 'readonly' => true))?>
					</div>
					<?=$this->Form->hidden('UserEvent.task_id', array('value' => Hash::get($task, 'Task.id')))?>
					<?=$this->Form->hidden('UserEvent.recipient_id')?>
					<div class="form-group noBorder">
						<span class="text"><?=__('Event type')?></span>
						<select class="event" name="data[UserEvent][type]">
							<option value="meet"><?=__('Meeting')?></option>
							<option value="mail"><?=__('Send email')?></option>
							<option value="call"><?=__('Call')?></option>
						</select>
						<div class="nextLine">
							<select class="period" name="data[UserEvent][duration]">
								<option value="period"><?=__('Period')?></option>
								<option value="day" selected="selected"><?=__('Day')?></option>
							</select>
							<div class="dateTime date" id="eventDay">
								<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
								<input type="text" name="data[UserEvent][eventTime]" class="form-control" placeholder="" readonly="readonly">
							</div>
							<span id="daySelect" style="display: none">
								<span class="tire">—</span>
								<div id="dueDate" class="dateTime date">
									<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
									<input type="text" name="data[UserEvent][periodEnd]" class="form-control" placeholder="" readonly="readonly">
								</div>
							</span>
							<span id="timeSelect">
								<div class="dateTime onlyTime date" id="timeStart" style="margin-left: 14px">
									<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
									<input type="text" name="data[UserEvent][timeBegin]" class="form-control" placeholder="" readonly="readonly">
								</div>
								<span class="tire">—</span>
								<div class="dateTime onlyTime date" id="timeEnd">
									<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
									<input type="text" name="data[UserEvent][timeEnd]" class="form-control" placeholder="" readonly="readonly">
								</div>
							</span>

						</div>
					</div>

					<div class="form-group">
						<label><?=__('User')?></label>
						<input type="text" id="userSearch" class="form-control">
					</div>

					<div class="form-group noBorder">
						<label><?=__('Members')?></label>
						<img src="/img/ajax_loader.gif" alt="" class="preloader">
						<div class="groupAccess clearfix" style="height: 160px; overflow: hidden;">
						</div>
					</div>

					<div class="form-group">
						<label><?=__('Event description')?></label>
						<?=$this->Form->input('UserEvent.descr', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => __('Description').'...', 'class' => 'form-control'))?>
					</div>
					<button type="submit" class="btn btn-primary" style="margin-right: 10px;"><?=__('Add')?></button>
				<?=$this->Form->end()?>
			</div>
		</div>
	</div>
</div-->

<div class="modal fade eventTypeModal" id="userEventModal">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<?=$this->Form->create('UserEvent', array('url' => array('controller' => 'Project', 'action' => 'addUserEvent')))?>
					<div class="form-group">
						<?=$this->Form->input('UserEvent.title', array('label' => false, 'div' => false, 'value' => Hash::get($task, 'Task.title'), 'class' => 'form-control'))?>
					</div>
					<?=$this->Form->hidden('UserEvent.id')?>
					<?=$this->Form->hidden('UserEvent.recipient_id')?>

					<?=$this->Form->hidden('UserEvent.is_delayed')?>
					<?=$this->Form->hidden('UserEvent.object_type', array('value' => 'task'))?>
					<?=$this->Form->hidden('UserEvent.object_id', array('value' => Hash::get($task, 'Task.id')))?>
					<!--?=$this->Form->hidden('UserEvent.task_id')?-->

					<div class="form-group">
						<?=$this->Form->input('UserEvent.bind', array('label' => false, 'div' => false, 'class' => 'form-control disabled', 'placeholder' => __('Bind to').'...', 'readonly' => true, 'value' => Hash::get($task, 'Task.title')))?>
					</div>

					<div class="form-group noBorder">
						<div class="nextLine">
							<?=$this->Form->hidden('UserEvent.yearStart')?>
							<div style="width: 151px; display: inline-block; margin: 0; padding: 0;">
								<select name="data[UserEvent][monthStart]" id="monthStart" value="<?=date('m')?>">
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
							<div style="width: 61px; display: inline-block; margin: 0; padding: 0;">
								<select name="data[UserEvent][dayStart]" id="dayStart" value="<?=date('m')?>">
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
							<div style="width: 107px; display: inline-block; margin: 0; padding: 0;">
								<select name="data[UserEvent][timeStart]" id="timeStart">
<?php
if(Configure::read('Config.language') == 'rus') {
?>
									<option value="0">00:00</option>
									<option value="1">01:00</option>
									<option value="2">02:00</option>
									<option value="3">03:00</option>
									<option value="4">04:00</option>
									<option value="5">05:00</option>
									<option value="6">06:00</option>
									<option value="7">07:00</option>
									<option value="8">08:00</option>
									<option value="9">09:00</option>
									<option value="10">10:00</option>
									<option value="11">11:00</option>
									<option value="12">12:00</option>
									<option value="13">13:00</option>
									<option value="14">14:00</option>
									<option value="15">15:00</option>
									<option value="16">16:00</option>
									<option value="17">17:00</option>
									<option value="18">18:00</option>
									<option value="19">19:00</option>
									<option value="20">20:00</option>
									<option value="21">21:00</option>
									<option value="22">22:00</option>
									<option value="23">23:00</option>
<?
} else {
?>
									<option value="0">12:00 am</option>
									<option value="1">01:00 am</option>
									<option value="2">02:00 am</option>
									<option value="3">03:00 am</option>
									<option value="4">04:00 am</option>
									<option value="5">05:00 am</option>
									<option value="6">06:00 am</option>
									<option value="7">07:00 am</option>
									<option value="8">08:00 am</option>
									<option value="9">09:00 am</option>
									<option value="10">10:00 am</option>
									<option value="11">11:00 am</option>
									<option value="12">12:00 pm</option>
									<option value="13">01:00 pm</option>
									<option value="14">02:00 pm</option>
									<option value="15">03:00 pm</option>
									<option value="16">04:00 pm</option>
									<option value="17">05:00 pm</option>
									<option value="18">06:00 pm</option>
									<option value="19">07:00 pm</option>
									<option value="20">08:00 pm</option>
									<option value="21">09:00 pm</option>
									<option value="22">10:00 pm</option>
									<option value="23">11:00 pm</option>
<?
}
?>
								</select>
							</div>
						</div>
						<div class="nextLine">
							<?=$this->Form->hidden('UserEvent.yearStart', array('value' => date('o')))?>
							<div id="eType" style="width: 215px; display: inline-block; margin: 0; padding: 0;">
								<select id="UserEventType" name="data[UserEvent][type]">
									<option value="blank"><?=__('Event type')?></option>
									<option value="meet"><?=__('Meeting')?></option>
									<option value="mail"><?=__('Send email')?></option>
									<option value="call"><?=__('Call')?></option>
									<option value="conderence"><?=__('Conference')?></option>
									<option value="sport"><?=__('Sport')?></option>
									<option value="task"><?=__('Task')?></option>
									<option value="none"><?=__('Other')?></option>
								</select>
							</div>
							<div id="eDuration" style="width: 107px; display: inline-block; margin: 0; padding: 0;">
								<select name="data[UserEvent][duration]" id="timeDuration" value="<?=date('m')?>">
									<option value="0"><?=__('Period')?></option>
									<option value="15">15 <?=__('min')?></option>
									<option value="30">30 <?=__('min')?></option>
									<option value="60">1 <?=__('h')?></option>
									<option value="120">2 <?=__('h')?></option>
									<option value="180">3 <?=__('h')?></option>
									<option value="240">4 <?=__('h')?></option>
									<option value="300">5 <?=__('h')?></option>
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

					<div class="form-group noBorder">
						<div class="bigCheckBox" style="display: inline;">
							<input name="data[UserEvent][shared]" id="UserEventShared" type="checkbox" class="checkboxStyle glyphicons ok_2" />
							<span class="checkboxText"><?=__('share')?></span>
						</div>
					</div>
					<div id="saveEventButton" class="btn btn-primary loadBtn"><span><?=__('Save')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
					<div id="delayEventButton" class="btn btn-default loadBtn pull-right"><span><?=__('Set aside')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
					<div class="clearfix"></div>

					<!--div class="form-group noBorder" id="uList" style="display: none; margin: 0;">
						<div id="eventUserList" class="tokenfield"></div>
						<?=$this->Form->hidden('list')?>
					</div>

					<div class="form-group">
						<input type="text" id="userSearch" class="form-control" placeholder="<?=__('Select user')?>">
					</div>

					<div class="form-group noBorder">
						<img src="/img/ajax_loader.gif" alt="" class="preloader" style="position: absolute; right: 10px;">
						<div class="groupAccess clearfix" style="height: 0px; overflow: hidden;"></div>
					</div>

					<div class="form-group">
						<?=$this->Form->input('UserEvent.descr', array('type' => 'textarea', 'label' => false, 'div' => false, 'placeholder' => __('Event description'), 'class' => 'form-control', 'onFocus' => "this.style.webkitTransform = 'translate3d(0px,-10000px,0)'; webkitRequestAnimationFrame(function() { this.style.webkitTransform = ''; }.bind(this))"))?>
					</div>
					<div id="saveEventButton" class="btn btn-primary loadBtn"><span><?=__('Save')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div-->
				<?=$this->Form->end()?>
			</div>
		</div>
	</div>
</div>

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

	$('#userSearch').keypress(function() {
		$('#userEventModal .preloader').show();
	});

	$('#userSearch').donetyping(function() {
		var postData = { q: $('#userSearch').val() };
		$.post( "<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'userList'))?>", postData, ( function (data) {
			$(".eventTypeModal .groupAccess").html(data);

			var itemCount = $('.item', '.eventTypeModal' ).length;

			if( itemCount > 2 ) {
				$('.eventTypeModal form .groupAccess').animate({ height: 160 }, 500);
			} else {
				$('.eventTypeModal form .groupAccess').animate({ height: 80 * itemCount }, 500);
			}
		}));
		$('.eventTypeModal .preloader').hide();
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

	$(document).ready(function () {
		$('#userEventModal .preloader').hide();

		$(document).on('click', '.item.user', function(e) {
			$('.item.user.active').removeClass('active');
			$(this).addClass('active');
			if( $(".token[data-user-id='"+$(this).data('user_id')+"']").length == 0 ) {
				$('#UserEventRecipientId').val( $(this).data('user_id') );

				var html = tmpl('user-select', {id: $(this).data('user_id'), name: $(".name", this).text(), url: $('img', this).attr('src')});

				$('#eventUserList').append(html);
				if( $('#uList .token').length == 1 ) {
					$('#uList').animate({ height: 'show' }, 200, 'linear');
				}
				$(this).animate({ height: 'hide', margin: 'hide' }, 400, 'easeOutQuart');
			}
		});

		$('.tokenfield').on('click', '.token a.close', function(e) {
			$(this).parents('.token').animate({ width: 'hide', opacity: 'hide' }, 200, 'linear', function(){
				$(this).remove();
			});

			if( $('#uList .token').length == 0 ) {
				$('#uList').animate({ height: 'hide' }, 200, 'linear');
			}
			$(".item[data-user_id='"+$(this).parents('.token').data('user-id')+"']").animate({ height: 'show', margin: 'show' }, 400, 'easeOutQuart');
		});

		$('.eventTypeModal #saveEventButton.loadBtn').click(function(){
			$('#UserEventIsDelayed').val('0');
			$(this).removeClass('loadBtn');
			$(this).addClass('disabled');
			//Timeline.updateEvent();
			if (eventIsValid()) {
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
						$('#uList .token').remove();
						$('#uList').hide();
						$('.eventTypeModal #saveEventButton').addClass('loadBtn');
						$('.eventTypeModal #saveEventButton').removeClass('disabled');
						$('.eventTypeModal form .groupAccess').css('height', 0);
						$('#userSearch').val('');
					}
					$('#UserEventTitle').popover('destroy');
					$('#userEventModal').modal('hide');
				});
			}
		});

		$('.eventTypeModal #delayEventButton.loadBtn').click(function(){
			$('#UserEventIsDelayed').val('1');
			$(this).removeClass('loadBtn');
			$(this).addClass('disabled');
			//Timeline.updateEvent();
			if (eventIsValid()) {
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
						$('#uList .token').remove();
						$('#uList').hide();
						$('.eventTypeModal #saveEventButton').addClass('loadBtn');
						$('.eventTypeModal #saveEventButton').removeClass('disabled');
						$('.eventTypeModal form .groupAccess').css('height', 0);
						$('#userSearch').val('');
					}
					$('#UserEventTitle').popover('destroy');
					$('#userEventModal').modal('hide');
				});
			}
		});

		$('select, .bigCheckBox input').styler({fileBrowse: '<span class="glyphicons paperclip"></span>'});
<?php

if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>

		$('#dueDate').datetimepicker({
			language:"<?=$lang?>",
			format:"dd.mm.yyyy",
			weekStart: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2
		});

		$('#eventDay').datetimepicker({
			language:"<?=$lang?>",
			format:"dd.mm.yyyy",
			weekStart: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2
		});

		$('#timeStart, #timeEnd').datetimepicker({
			language:"<?=$lang?>",
			format:"hh:ii",
			weekStart: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 1
		});

        $("#eventDay").on("change.dp",function (e) {
			var time = $(e.target).val();

            $('#dueDate').datetimepicker('setStartDate', time)
            $('#dueDate').datetimepicker('update', time);

        });

        $("#timeStart").on("change.dp",function (e) {
			var time = $(e.target).val();

            $('#timeEnd').datetimepicker('setStartDate', time)
            $('#timeEnd').datetimepicker('update', time);
        });

		$('#OperationIncome, #OperationExpense, #OperationTax, #OperationPercent').on('change, keydown, keyup', function() {
			var income = $('#OperationIncome').val();
			var expense = $('#OperationExpense').val();
			var tax = $('#OperationTax').val();
			var percent = ($('#OperationPercent').val() / 100) * $('#OperationIncome').val();
			var total = income - expense - tax - percent;

			$('#ww .total').val(total);
	    });

		$('#userEventModal, #ww').on('show.bs.modal', function (e) {
			$('#UserEventYearStart').val('<?=date('o')?>').change();
			$('#monthStart').val('<?=date('n')?>').change();
			$('#dayStart').val('<?=date('j')?>').change();
			$('#timeStart').val('<?=date('G')?>').change();
		})

		$('#userEventModal, #ww').on('shown.bs.modal', function (e) {
			$('body').css("position","fixed");
		})

		$('#userEventModal, #ww').on('hide.bs.modal', function (e) {
			$('body').css("position","static");
		});

	});
</script>

<style type="text/css">
	.groupAccess .item { background: #ffffff; }
	.groupAccess .item.active { background: #f5f6f8; }

	.jq-selectbox__dropdown ul { max-height: 372px!important; }

	.token { overflow: hidden; position: relative; line-height: 100%; width: 159px; }
	.token img { position: absolute; top: 0; left: 0; height: 100%; padding-right: 5px; }
	.token .token-label { padding-left: 35px!important; max-width: 131px; }
	.token a.close { position: absolute!important; top: 0!important; right: 0!important; }
	.tokenfield .token:nth-child(2n) { margin-right: 0!important; }
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
