<?
	$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
	if(Configure::read('Config.language') == 'rus'){
		$lang = 'ru';
	}else{
		$lang = 'en';
	}
	$month1 = date('Y-m', strtotime('-1 month'));
	$month2 = date('Y-m');
?>
<div class="title"><?= __('Compare')?></div>
<div class="calandarPeriod">
	<div class="dateTime date" id="finance-chart-compare-from">
		<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
		<input type="text" class="form-control" placeholder="<?= __('From') ?>" readonly="readonly">
		<input type="hidden" value="" id="finance-chart-compare-from-input">
	</div>
	<div class="dateTime date" id="finance-chart-compare-to">
		<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
		<input type="text" class="form-control" placeholder="<?= __('To') ?>" readonly="readonly">
		<input type="hidden" value="" id="finance-chart-compare-to-input">
	</div>
</div>
<!--
<div class="dropdown" id="finance-chart-compare-accounts">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?= __('Accounts') ?> <span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<? foreach ($aFinanceAccount as $account) { ?>
			<li data-id="<?= $account['FinanceAccount']['id'] ?>"><?= $account['FinanceAccount']['name'] ?></li>
		<? } ?>
	</ul>
</div>
<select class="currency" id="finance-chart-compare-currency">
	<? foreach ($this->Money->symbols() as $code => $symbol) { ?>
		<option value="<?= $code ?>"><?= $symbol?></option>
	<? } ?>
</select>
-->
<div id="finance-compare-chart" style="height: 300px; margin-top: 4px"></div>

<script type="application/javascript">
var chartCompareRender = function() {
	var params = {
		from: $('#finance-chart-compare-from-input').val(),
		to: $('#finance-chart-compare-to-input').val()
	};
	$.post(financeURL.compareAccounts + '/' + <?= $id ?> + '.json', params, function (response) {
		if (response == undefined || response.data.length <= 1) {
			$('#finance-compare-chart').html('<?= __('Compare is empty') ?>');
			return;
		}
		var data = google.visualization.arrayToDataTable(response.data);
		var options = {
			title: '',
			hAxis: {
				title: '',
				titleTextStyle: {
					color: '#ffffff'
				}
			},
			vAxis: {
				minValue: 0,
				gridlines: {
					color: '#eeefff'
				},
				format: '#'
			},
			pointSize: 10,
			colors:['#EDEBFF'],
			series: {
				0: {areaOpacity: 0.1},
				1: {areaOpacity: 0.8}
			},
			lineWidth: 0,
			legend: {'position': 'none'},
			chartArea: {width: '80%', height: '80%'}
		};
		var chart = new google.visualization.ColumnChart(document.getElementById('finance-compare-chart'));
		chart.draw(data, options);
		renderSVG($('#finance-compare-chart'));
	});
};
// Resize
$(window).resize(function () {
	chartCompareRender();
});
// Calendars
$('#finance-chart-compare-from-input, #finance-chart-compare-to-input').on('change', function () {
	chartCompareRender();
});
$('#finance-chart-compare-from').datetimepicker({
	format: 'MM yyyy',
	weekStart: 1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 3,
	minView: 3,
	language:"<?=$lang?>",
	linkField: 'finance-chart-compare-from-input',
	linkFormat: 'yyyy-mm'
});
$('#finance-chart-compare-to').datetimepicker({
	format: 'MM yyyy',
	weekStart: 1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 3,
	minView: 3,
	language:"<?=$lang?>",
	linkField: 'finance-chart-compare-to-input',
	linkFormat: 'yyyy-mm'
});
$('#finance-chart-compare-from').datetimepicker('setDate', new Date("<?= $month1 ?>"));
$('#finance-chart-compare-to').datetimepicker('setDate', new Date("<?= $month2 ?>"));
</script>