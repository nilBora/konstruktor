<?
	$this->Html->script(array(
		'https://www.google.com/jsapi'
	), array('inline' => false));
?>

<div class="row" style="margin-top:22px">
	<div class="col-sm-6"></div>
	<div class="col-sm-6">
		<!--select class="">
			<option>Все компании</option>
			<option>Компания 1</option>
		</select-->
		<?=$this->Form->input('group_id', array('options' => $aGroupOptions, 'label' => false, 'div' => false, 'id' => 'groupList', 'value' => isset($this->request->named['group']) ? $this->request->named['group'] : 0 ))?>
		<?=$this->Form->input('event_id', array('options' => $aTypeOptions, 'label' => false, 'div' => false, 'id' => 'eventType', 'value' => isset($this->request->named['type']) ? $this->request->named['type'] : 0 ))?></div>	
</div>

<div class="row crmGraphics">
	<div class="col-sm-6">
		<div class="btn-group">
			<!--button type="button" class="btn btn-default active">Месяц</button>
			<button type="button" class="btn btn-default">Квартал</button>
			<button type="button" class="btn btn-default">Год</button-->
		</div>
		<div class="title"><?=__('Events')?></div>
		<div id="typesChart" style="height: 350px; margin-top: 34px"></div>
	</div>
	<div class="col-sm-6">
		
		<div class="title"><?=__('Compare')?></div>
		<div id="sellsChart" style="height: 350px; margin-top: 34px"></div>
	</div>
</div>
<br /><br />
<div class="row crmStatistic">
	<div class="col-sm-3 headTitle"><?=__('Events statistics')?></div>
	<div class="col-sm-3 headTask"><?=__('Task')?></div>
	<div class="col-sm-4 headEvent"><?=__('Event')?></div>
	<div class="col-sm-2 headTime"><?=__('Time')?></div>
	<div class="clearfix hidden-xs"></div>
<?
	$totalTime = 0;
	$totalExpense = 0;
	$totalIncome = 0;
	//$totalBalance = 0;

	$totalCalls = 0;
	$totalMails = 0;
	$totalMeets = 0;

	foreach($data as $subproject) {
		$subprojTime = 0;
		$subprojBalance = 0;
		$taskCount = 0;
?>
	<!-- SUBPROJECT -->
	<div class="col-sm-3 title"><?=$subproject['title']?></div>


<?
		foreach($subproject['tasks'] as $task) {
			$subprojTime += $task['calls']['time'] + $task['meets']['time'] + $task['mails']['time'];
			
			$subprojBalance += (float)$task['account_state']['income'] - (float)$task['account_state']['expense'];
			//$totalBalance += (float)$task['account_state']['balance'];
			$totalIncome += (float)$task['account_state']['income'];
			$totalExpense += (float)$task['account_state']['expense'];

			$totalCalls += $task['calls']['count'];
			$totalMails += $task['mails']['count'];
			$totalMeets += $task['meets']['count'];
			
			$days = floor( $task['calls']['time'] / 86400);
			$hours = floor(( $task['calls']['time'] - $days * 86400) / 3600);
			$minutes = floor(( $task['calls']['time'] - $days * 86400 - $hours * 3600) / 60);
			$callTime = $days ? $days.' '.__('d').' ' : '';
			$callTime = $hours ? $callTime.$hours.' '.__('h').' ' : '';
			$callTime = $minutes ? $callTime.$minutes.' '.__('min') : $callTime.'0 '.__('min');
			
			$days = floor( $task['meets']['time'] / 86400);
			$hours = floor(( $task['meets']['time'] - $days * 86400) / 3600);
			$minutes = floor(( $task['meets']['time'] - $days * 86400 - $hours * 3600) / 60);
			$meetTime = $days ? $days.' '.__('d').' ' : '';
			$meetTime = $hours ? $meetTime.$hours.' '.__('h').' ' : '';
			$meetTime = $minutes ? $meetTime.$minutes.' '.__('min') : $meetTime.'0 '.__('min');
			
			$days = floor( $task['mails']['time'] / 86400);
			$hours = floor(( $task['mails']['time'] - $days * 86400) / 3600);
			$minutes = floor(( $task['mails']['time'] - $days * 86400 - $hours * 3600) / 60);
			$mailTime = $days ? $days.' '.__('d').' ' : '';
			$mailTime = $hours ? $mailTime.$hours.' '.__('h').' ' : '';
			$mailTime = $minutes ? $mailTime.$minutes.' '.__('min') : $mailTime.'0 '.__('min');
			
			$taskCount++;
?>	
	<!-- TASK -->
<?
	if( $taskCount==1 ) {
?>
		<div class="col-sm-3 task"><?=$task['title']?></div>
<?
	} else {
?>
		<div class="col-sm-3 col-sm-offset-3 task"></div>
<?
	}
?>
	<div class="col-sm-6 event">
		<div class="item row">
			<div class="col-sm-8">
				<span class="value"><?=__('Telephone calls')?></span>
				<span class="count">×<?=$task['calls']['count']?></span>
			</div>
			<div class="col-sm-4 time"><?=$callTime?></div>
		</div>
		<div class="item row">
			<div class="col-sm-8">
				<span class="value"><?=__('Emails')?></span>
				<span class="count">×<?=$task['mails']['count']?></span>
			</div>
			<div class="col-sm-2 time"><?=$mailTime?></div>
		</div>
		<div class="item row">
			<div class="col-sm-8">
				<span class="value"><?=__('Meetings')?></span>
				<span class="count">×<?=$task['meets']['count']?></span>
			</div>
			<div class="col-sm-2 time"><?=$meetTime	?></div>
		</div>
	</div>
	<div class="clearfix hidden-xs"></div>
<?
		}
		
		$totalTime += $subprojTime;
		
		$days = floor($subprojTime / 86400);
		$hours = floor(($subprojTime - $days * 86400) / 3600);
		$minutes = floor(($subprojTime - $days * 86400 - $hours * 3600) / 60);
		
		if($subprojBalance == 0 || $subprojTime == 0) {
			$balancePerHour = 0;
		} else {
			if(($subprojTime / 3600) < 1) {
				$balancePerHour = $subprojBalance;
			} else {
				$balancePerHour = $subprojBalance / ($subprojTime / 3600);
			}
		}
		
		$subprojTime = $days ? $days.' '.__('d').' ' : '';
		$subprojTime = $hours ? $subprojTime.$hours.' '.__('h').' ' : '';
		$subprojTime = $minutes ? $subprojTime.$minutes.' '.__('min') : $subprojTime.'0 '.__('min');
		
?>		
	<!-- TOTAL -->
	<div class="col-sm-3 col-sm-offset-3 task"></div>
	<div class="col-sm-6 event">
		<div class="item">
			<span class="generally"><?=__('Total time spent')?></span>
			<span class="count"><?=$subprojTime?></span>
		</div>
		<div class="item">
			<span class="generally"><?=__('Earnings per hour')?></span>
			<span class="count"><?=number_format($balancePerHour, 2).' '.$this->Money->symbols()['USD']?></span>
		</div>
		<div class="item">
			<span class="generally"><?=__('Total earnings')?></span>
			<span class="count"><?=$subprojBalance.' '.$this->Money->symbols()['USD']?></span>
		</div>
	</div>
	<div class="clearfix hidden-xs"></div>
<?
	}
		
	$days = floor($totalTime / 86400);
	$hours = floor(($totalTime - $days * 86400) / 3600);
	$minutes = floor(($totalTime - $days * 86400 - $hours * 3600) / 60);

	$totalBalance = $totalIncome - $totalExpense;

	if($totalBalance == 0 || $totalTime == 0) {
		$balancePerHour = 0;
	} else {
		if(($totalTime / 3600) < 1) {
			$balancePerHour = $totalBalance;
		} else {
			$balancePerHour = $totalBalance / ($totalTime / 3600);
		}
		$balancePerHour = $totalBalance / ($totalTime / 3600);
	}

	//время для графика в минутах
	$chartTime = $totalTime  / 60; 

	$totalTime = $days ? $days.' '.__('d').' ' : '';
	$totalTime = $hours ? $totalTime.$hours.' '.__('h').' ' : '';
	$totalTime = $minutes ? $totalTime.$minutes.' '.__('min') : $totalTime.'0 '.__('min');
?>	
	<!-- FINAL -->
	<div class="col-sm-3 title"></div>
	<div class="col-sm-3 event">
		<div class="item">
			<span class="generally"><?=__('Expense')?></span>
			<span class="count"><?=$totalExpense.' '.$this->Money->symbols()['USD']?></span>
		</div>
		<!--div class="item">
			<span class="generally">Налоги</span>
			<span class="count">$4 270 548,8</span>
		</div-->
		<div class="item">
			<span class="generally"><?=__('Income')?></span>
			<span class="count"><?=$totalIncome.' '.$this->Money->symbols()['USD']?></span>
		</div>
	</div>
	<div class="col-sm-6 event">
		<div class="item">
			<span class="generally"><?=__('Total time spent')?></span>
			<span class="count"><?=$totalTime?></span>
		</div>
		<div class="item">
			<span class="generally"><?=__('Earnings per hour')?></span>
			<span class="count"><?=number_format($balancePerHour, 2).' '.$this->Money->symbols()['USD']?></span>
		</div>
		<div class="item">
			<span class="generally"><?=__('Total earnings')?></span>
			<span class="count"><?=($totalIncome - $totalExpense).' '.$this->Money->symbols()['USD']?></span>
		</div>
	</div>
</div>
<br />
<br />


<script type="text/javascript">
	
	google.load("visualization", "1", {packages:["corechart"]});
	
	var chartEventsRender = function() {
		
		if( <?=$totalCalls?> == 0 && <?=$totalMails?> == 0 && <?=$totalMeets?> == 0 ) {
			$('#typesChart').css('height', 'auto');
			$('#typesChart').text('<?=__('Statistic is empty')?>');
			return false;
		}
		
		var params = {
			
		};		
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Type'); // X-axis
  		data.addColumn('number', '<?=__('Quantity')?>'); // Y-axis
		
		data.addRow( [ '<?=__('Telephone calls')?>' , <?=$totalCalls?>] );
		data.addRow( [ '<?=__('Emails')?>' , <?=$totalMails?>] );
		data.addRow( [ '<?=__('Meetings')?>' , <?=$totalMeets?>] );
		
		var options = {
			title: '',
			pieHole: 0.4,
			chartArea: {width: '100%', height: '90%'},
        	slices: {
       			0: { color: '#FFCD6A' },
            	1: { color: '#9A0199' },
            	2: { color: '#02C090' }
          	}
		};
		var chart = new google.visualization.PieChart(document.getElementById('typesChart'));
		chart.draw(data, options);
		$(window).resize(function () {
			chart.draw(data, options);
		});
	}
	
	var chartSellsRender = function() {
		
		if( <?= $totalIncome ?> == 0 && <?= $totalBalance ?> == 0 && <?= $totalExpense ?> == 0 && <?= $chartTime ?> == 0) {
			$('#sellsChart').css('height', 'auto');
			$('#sellsChart').text('<?=__('Statistic is empty')?>');
			return false;
		}	
		
		var params = {
			
		};		
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Type'); // X-axis
  		data.addColumn('number', '<?=__('Quantity')?>'); // Y-axis
		
		data.addRow( [ '<?=__('Net Income')?>' , 	<?= $totalIncome ?>] );
		data.addRow( [ '<?=__('Income')?>' , 	<?= $totalBalance ?>] );
		data.addRow( [ '<?=__('Expense')?>' , <?= $totalExpense ?>] );
		data.addRow( [ '<?=__('Time')?> (<?=__('minutes')?>)' , 	<?= $chartTime ?>] );
		
		var options = {
			title: '',
			hAxis: {
				title: '',
				titleTextStyle: { color: '#ffffff' }
		 	},
			vAxis: {
				minValue: 0,
				gridlines: { color: 'transparent' },
				textPosition: 'none'
			},
			pointSize: 10,
			colors:['#EDEBFF'],
			series: {
				0: { areaOpacity: 0.1 },
				1: { areaOpacity: 0.8 }
			},
			lineWidth: 0,
			legend: {'position': 'none'},
			chartArea: {width: '100%', height: '85%'}
		};
		var chart = new google.visualization.ColumnChart(document.getElementById('sellsChart'));
		chart.draw(data, options);
		$(window).resize(function () {
			chart.draw(data, options);
		});
	}
	
	google.setOnLoadCallback(function () {
		chartEventsRender();
		chartSellsRender();
	});
	
	$(document).ready(function () {
		$('input.attachFile, select').styler({fileBrowse: '<span class="glyphicons paperclip"></span>'});
		
		$('#groupList, #eventType').on('change', function() {
			var url = '/User/mySells/group:' + $('#groupList').val() + '/type:' + $('#eventType').val();
			window.location.href = url;
		});
	});
</script>
