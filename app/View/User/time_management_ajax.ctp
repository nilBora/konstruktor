<?
	$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
	$lang = Configure::read('Config.language') == 'rus' ? 'ru' : 'en';

	$this->Html->script(array(
		'vendor/bootstrap-datetimepicker.min',
		'vendor/bootstrap-datetimepicker.ru.js',
		'https://www.google.com/jsapi'
	), array('inline' => false));


	$strings = array(
		'personal' => __('Personal events'),	
		'call' => __('Calls'),	
		'conference' => __('Conferences'),	
		'meet' => __('Meetings'),	
		'holiday' => __('Holidays'),	
		'sport' => __('Sport events'),	
		'task' => __('Tasks'),	
		'mail' => __('Emails'),	
		'purchase' => __('Purchase'),	
		'entertain' => __('Entertainment'),	
		'pay' => __('Payment'),	
		'none' => __('Other events'),	
	);
?>

	<div class="row crmGraphics">
		<div class="col-sm-6">
			<div class="title" style="padding-top: 0;"><?=__('Events')?></div>
			<div id="typesChart" style="height: 350px; margin-top: 34px"></div>
		</div>
		<div class="col-sm-6">

			<div class="title" style="padding-top: 0;"><?=__('Compare')?></div>
			<div id="sellsChart" style="height: 350px; margin-top: 34px"></div>
		</div>
	</div>
	<br /><br />
<? /* ------------------------------------------ ОБЩАЯ СВОДКА -------------------------------------------- */ ?>
	<div class="row crmStatistic">
		<!--div class="col-sm-3 headTitle"><?=__('Event title')?></div-->
		<!--div class="col-sm-3 headTask"><?=__('Category')?></div-->
		<!--div class="col-sm-4 headEvent"><?=__('Event')?></div-->
		<!--div class="col-sm-2 headTime"><?=__('Time')?></div-->
		<!--div class="clearfix hidden-xs"></div-->
<?
	$totalTime = 0;
	
	$total['count']['call'] = 0;
	$total['count']['mail'] = 0;
	$total['count']['meet'] = 0;
	$total['count']['personal'] = 0;
	$total['count']['conference'] = 0;
	$total['count']['holiday'] = 0;
	$total['count']['sport'] = 0;
	$total['count']['task'] = 0;
	$total['count']['purchase'] = 0;
	$total['count']['entertain'] = 0;
	$total['count']['pay'] = 0;
	$total['count']['none'] = 0;
	
	$total['time']['call'] = 0;
	$total['time']['mail'] = 0;
	$total['time']['meet'] = 0;
	$total['time']['personal'] = 0;
	$total['time']['conference'] = 0;
	$total['time']['holiday'] = 0;
	$total['time']['sport'] = 0;
	$total['time']['task'] = 0;
	$total['time']['purchase'] = 0;
	$total['time']['entertain'] = 0;
	$total['time']['pay'] = 0;
	$total['time']['none'] = 0;

	$total['type']['work'] = 0;
	$total['type']['personal'] = 0;

	$total['type-time']['work'] = 0;
	$total['type-time']['personal'] = 0;

	$groupNum = 0;

	foreach( $data as $owner => $grpoupData ) {
?>	
	<!--h2><?= $owner == 'owner' ? __('My events') : __('Other events') ?></h2-->
<?	
		foreach( $grpoupData as $groupTitle => $ownerData ) {
?>
		<div class="groupPackHead" data-num="<?=$groupNum?>"><span class="glyphicons chevron-right arrow"></span>   <?=$groupTitle?></div>
	<div id="groupPack-<?=$groupNum?>" class="groupPack">
<?		
			$groupNum++;
			foreach($ownerData as $eventsTitle => $eventList) {
				$eventTime = 0;
				$catCount = 0;
?>
	<div class="eventEntry">
		<div class="col-sm-3 title"><?=$eventsTitle?></div>
<?
				foreach($eventList as $categoryTitle => $eventCategory) {
					$total['type'][$categoryTitle] ++;
					$catCount ++;
?>	
	<!-- EVENT CATEGORY -->
<?
			if( $catCount==1 ) {
?>
			<div class="col-sm-3 task <?= $owner == 'owner' ? 'select' : ''?>">
<?
					if( $owner == 'owner' ) {
?>
			<?=$this->Form->input('type', array('options' => $aCategoryChangeOptions, 'label' => false, 'div' => false, 'value' => $categoryTitle, 'data-title' => $eventsTitle ))?>
<?
					} else {
?>
			<?=$aCategoryChangeOptions[$categoryTitle]?>
<?
					}
?>
			</div>
<?
					} else {
?>
			<div class="col-sm-3 col-sm-offset-3 task"></div>
<?
					}
?>	
		<div class="col-sm-6 event">
<?
					foreach($eventCategory as $type => $typeData) {	
						$total['count'][$type] += $typeData['count'];
						$total['type'][$categoryTitle] += $typeData['count'];
						$eventTime += $typeData['time'];

						$total['type-time'][$categoryTitle] += $typeData['time'];

						$days = floor( $typeData['time'] / 86400);
						$hours = floor(( $typeData['time'] - $days * 86400) / 3600);
						$minutes = floor(( $typeData['time'] - $days * 86400 - $hours * 3600) / 60);
						$typeTime = $days ? $days.' '.__('d').' ' : '';
						$typeTime = $hours ? $typeTime.$hours.' '.__('h').' ' : '';
						$typeTime = $minutes ? $typeTime.$minutes.' '.__('min') : $typeTime.'0 '.__('min');
?>
			<div class="item row">
				<div class="col-sm-8">
					<span class="value"><?=$strings[$type]?></span>
					<span class="count">×<?=$typeData['count']?></span>
				</div>
				<div class="col-sm-4 time"><?=$typeTime?></div>
			</div>
<?
					}
?>		
		</div>
		<div class="clearfix hidden-xs"></div>
<?
					$multi = count($eventCategory) > 1;
				}
				if($multi) {
					$days = floor($eventTime / 86400);
					$hours = floor(($eventTime - $days * 86400) / 3600);
					$minutes = floor(($eventTime - $days * 86400 - $hours * 3600) / 60);

					$eventTimeString = $days ? $days.' '.__('d').' ' : '';
					$eventTimeString = $hours ? $eventTimeString.$hours.' '.__('h').' ' : '';
					$eventTimeString = $minutes ? $eventTimeString.$minutes.' '.__('min') : $eventTimeString.'0 '.__('min');
		
?>		
	<!-- EVENT TOTAL -->	
		<div class="col-sm-3 col-sm-offset-3"></div>
		<div class="col-sm-6">
			<div class="item row">
				<div class="col-sm-8 event" style="text-align: right;">
						<span class="generally"><?=__('Total time spent')?></span>
				</div>	
				<div class="col-sm-4 event">
						<span class="count"><?=$eventTimeString?></span>
				</div>
			</div>
		</div>
		<div class="clearfix hidden-xs"></div>
		<br>
<?
				}
?>
	</div>
<?
			}
?>
	</div>
<?
		}
	}
?>
	<br>
<?
	$days = floor($total['type-time']['work'] / 86400);
	$hours = floor(($total['type-time']['work'] - $days * 86400) / 3600);
	$minutes = floor(($total['type-time']['work'] - $days * 86400 - $hours * 3600) / 60);

	$workTime = $days ? $days.' '.__('d').' ' : '';
	$workTime = $hours ? $workTime.$hours.' '.__('h').' ' : '';
	$workTime = $minutes ? $workTime.$minutes.' '.__('min') : $workTime.'0 '.__('min');

	$days = floor($total['type-time']['personal'] / 86400);
	$hours = floor(($total['type-time']['personal'] - $days * 86400) / 3600);
	$minutes = floor(($total['type-time']['personal'] - $days * 86400 - $hours * 3600) / 60);

	$personalTime = $days ? $days.' '.__('d').' ' : '';
	$personalTime = $hours ? $personalTime.$hours.' '.__('h').' ' : '';
	$personalTime = $minutes ? $personalTime.$minutes.' '.__('min') : $personalTime.'0 '.__('min');	

	$totalTime = $total['type-time']['work'] + $total['type-time']['personal'];

	$days = floor($totalTime / 86400);
	$hours = floor(($totalTime - $days * 86400) / 3600);
	$minutes = floor(($totalTime - $days * 86400 - $hours * 3600) / 60);

	$totalTime = $days ? $days.' '.__('d').' ' : '';
	$totalTime = $hours ? $totalTime.$hours.' '.__('h').' ' : '';
	$totalTime = $minutes ? $totalTime.$minutes.' '.__('min') : $totalTime.'0 '.__('min');		
?>		
		<!-- FINAL -->
		<div class="col-sm-3 title"><?=__('Total')?></div>
		<div class="col-sm-3 event"></div>
		<div class="col-sm-6">
			<div class="item row">
				<div class="col-sm-8 event" style="text-align: right;">
					<span class="generally"><?=__('Time spent for work')?>:</span>
				</div>	
				<div class="col-sm-4 event">
					<span class="count"><?=$workTime?></span>
				</div>
			</div>
		</div>

		<div class="col-sm-3 col-sm-offset-3"></div>
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-8 event" style="text-align: right;">
					<span class="generally"><?=__('Personal time spent')?>:</span>
				</div>	
				<div class="col-sm-4 event">
					<span class="count"><?=$personalTime?></span>
				</div>
			</div>
		</div>

		<div class="col-sm-3 col-sm-offset-3"></div>
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-8 event" style="text-align: right;">
					<span class="generally"><?=__('Total time spent')?>:</span>
				</div>	
				<div class="col-sm-4 event">
					<span class="count"><?=$totalTime?></span>
				</div>
			</div>
		</div>

	</div>
	
	<? /* ------------------------------------------ ДЕТАЛЬНАЯ СВОДКА -------------------------------------------- */ ?>
	<div class="row crmStatistic">
<?
	/*
	$totalTime = 0;
	foreach( $detailsData as $groupTitle => $ownerData ) {
?>
		<div class="groupPackHead" data-num="<?=$groupNum?>"><span class="glyphicons chevron-right arrow"></span>   <?=$groupTitle?></div>
		<div id="groupPack-<?=$groupNum?>" class="groupPack">
<?		
			$groupNum++;
			foreach($ownerData as $eventsTitle => $eventList) {
				$eventTime = 0;
				$catCount = 0;
				foreach( $eventList as $event ) {
?>

		
		
			<div class="eventEntry">
				<div id="event-<?=$event['id']?>" class="editableEvent" onclick="editEventPopup('<?=$event['event_time']?>', '<?=date('H:00', strtotime($event['event_time']))?>', '<?=$event['event_end_time']?>', '<?=date('H:00', strtotime($event['event_end_time']))?>', '<?=$event['type']?>', '<?=$event['recipient_id']?>', '<?=$event['object_type']?>', '<?=$event['object_id']?>', '<?=$event['id']?>', '<?=$event['shared']?>', '<?=str_replace('"', '\"', $event['title'])?>', '<?=$event['descr']?>')">
					<div class="col-sm-1 task"><? if($event['user_id'] == $currUserID) { ?> <span class="glyphicons user"> <? } ?></div>
					<div class="col-sm-3 title"><?=$event['title']?></div>
					<div class="col-sm-3 task"><?= $aCategoryOptions[ $event['category'] ] ?></div>	
					<div class="col-sm-2 task"><?= isset($aTypeOptions[$event['type']]) ? $aTypeOptions[ $event['type'] ] : __('Other events') ?></div>
					<div class="col-sm-3 task">
<?php 
		if(Configure::read('Config.language') == 'rus'){
?>
						<?=date('H:i - d.m.Y', strtotime($event['event_time']) )?>
<?
		} else {
?>
						<?=date('h:i a - m/d/Y', strtotime($event['event_time']) )?>
<? 
		} 
?>	
					</div>
				<div class="clearfix hidden-xs"></div>
				</div>
			</div>
			
			
			

<?
				}
			}
?>
	</div>
<?
	}
	*/
?>
	</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

<script type="text/javascript">	
	google.load("visualization", "1", {packages:["corechart"]});

<?
		if( $this->request->data('type') != 'all' ) {
?>
	var chartEventsRenderAjax = function() {
		
		var params = {
			
		};		
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Type'); // X-axis
  		data.addColumn('number', '<?=__('Quantity')?>'); // Y-axis
		
		data.addRow( [ '<?=__('Work')?>' , <?=$total['type']['work']?>] );
		data.addRow( [ '<?=__('Personal')?>' , <?=$total['type']['personal']?>] );
		
		var options = {
			title: '',
			pieHole: 0.4,
			chartArea: {width: '95%', height: '90%'},
        	slices: {
       			0: { color: '#F44336' },
            	1: { color: '#02C090' }
          	}
		};
		var chart = new google.visualization.PieChart(document.getElementById('typesChart'));
		chart.draw(data, options);
		$(window).resize(function () {
			chart.draw(data, options);
		});
	}
<?
		} else {
?>
	var chartEventsRenderAjax = function() {
		
		var params = {
			
		};		
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Type'); // X-axis
  		data.addColumn('number', '<?=__('Quantity')?>'); // Y-axis
		
		data.addRow( [ '<?=__('Telephone calls')?>' , <?=$total['count']['call']?>] );
		data.addRow( [ '<?=__('Emails')?>' , <?=$total['count']['mail']?>] );
		data.addRow( [ '<?=__('Meetings')?>' , <?=$total['count']['meet']?>] );
		data.addRow( [ '<?=__('Conferences')?>' , <?=$total['count']['conference']?>] );
		data.addRow( [ '<?=__('Sports')?>' , <?=$total['count']['sport']?>] );
		data.addRow( [ '<?=__('Tasks')?>' , <?=$total['count']['task']?>] );
		data.addRow( [ '<?=__('Purchase')?>' , <?=$total['count']['purchase']?>] );
		data.addRow( [ '<?=__('Entertainment')?>' , <?=$total['count']['entertain']?>] );
		data.addRow( [ '<?=__('Payment')?>' , <?=$total['count']['pay']?>] );
		data.addRow( [ '<?=__('Other')?>' , <?=$total['count']['none']?>] );
		
		var options = {
			title: '',
			pieHole: 0.4,
			chartArea: {width: '95%', height: '90%'},
        	slices: {
       			0: { color: '#F44336' },
            	1: { color: '#02C090' },
            	2: { color: '#FFA726' },
            	3: { color: '#2196F3' },
            	4: { color: '#689F38' },
            	5: { color: '#795548' },
            	6: { color: '#78909C' },
            	7: { color: '#827717' },
            	8: { color: '#9A0199' },
            	9: { color: '#57F9FA' }
          	}
		};
		var chart = new google.visualization.PieChart(document.getElementById('typesChart'));
		chart.draw(data, options);
		$(window).resize(function () {
			chart.draw(data, options);
		});
	}
<?
		}
		$timeWork = $total['type-time']['work'] / 3600;
		$timePersonal = $total['type-time']['personal'] / 3600;
		$max = $timeWork > $timePersonal ? $timeWork : $timePersonal;
		do {
			$max = floor(($max+1)/2);
		} while ($max > 10);
?>
	var chartSellsRenderAjax = function() {
		
		var params = {
			
		};		
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Type'); // X-axis
  		data.addColumn('number', '<?=__('Quantity')?>'); // Y-axis
		
		data.addRow( [ '<?=__('Work')?>' , 	<?=$timeWork?>] );
		data.addRow( [ '<?=__('Personal')?>' , 	<?=$timePersonal?>] );
		
		var options = {
			title: '',
			hAxis: {
				title: '',
				titleTextStyle: { color: '#ffffff' }
		 	},
			vAxis: {
				title: '<?=__('Time spent, h.')?>',
				minValue: 0,
				titleTextStyle: {
					color: '#333'
				},
				gridlines: { 
					color: '#eeefff',
					count: <?=$max?>,
				}
			},
			pointSize: 10,
			colors:['#EDEBFF'],
			series: {
				0: { areaOpacity: 0.1 },
				1: { areaOpacity: 0.8 }
			},
			legend: {'position': 'none'},
			chartArea: {width: '80%', height: '85%'}
		};
		var chart = new google.visualization.ColumnChart(document.getElementById('sellsChart'));
		chart.draw(data, options);
		$(window).resize(function () {
			chart.draw(data, options);
		});
	}
	
	$(document).ready(function () {
		$('input.attachFile, select').styler({fileBrowse: '<span class="glyphicons paperclip"></span>'});
	});
</script>
