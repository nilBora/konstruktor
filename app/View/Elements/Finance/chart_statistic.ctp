<?
	$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
	if(Configure::read('Config.language') == 'rus'){
		$lang = 'ru';
	}else{
		$lang = 'en';
	}
?>

<div class="title"><?=__('Expences')?></div>
<div class="btn-group" id="finance-chart-statistic-period">
	<button type="button" class="btn btn-default active" data-value="week"><?= __('Week')?></button>
	<button type="button" class="btn btn-default" data-value="quarter"><?= __('Quarter')?></button>
	<button type="button" class="btn btn-default" data-value="year"><?= __('Year')?></button>
</div>
<div class="calandarPeriod">
	<div class="dateTime date" id="finance-chart-statistic-from">
		<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
		<input type="text" class="form-control" placeholder="<?= __('From') ?>" readonly="readonly">
		<input type="hidden" value="" id="finance-chart-statistic-from-input">
	</div>
	<div class="dateTime date" id="finance-chart-statistic-to">
		<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
		<input type="text" class="form-control" placeholder="<?= __('To') ?>" readonly="readonly">
		<input type="hidden" value="" id="finance-chart-statistic-to-input">
	</div>
</div>
<select class="currency" id="finance-chart-statistic-currency">
	<? foreach ($this->Money->symbols() as $code => $symbol) { ?>
		<option value="<?= $code ?>"><?= $symbol?></option>
	<? } ?>
</select>
<div id="finance-expensesStatistic-chart" style="height: 240px; margin-top: 30px;"></div>

<script type="application/javascript">
var chartStatisticRender = function() {
	var params = {
		project_id: <?= $id?>,
		currency: $('#finance-chart-statistic-currency').val(),
		period: $('#finance-chart-statistic-period button.active').data('value'),
		from: $('#finance-chart-statistic-from-input').val(),
		to: $('#finance-chart-statistic-to-input').val()
	};
	$.post(financeURL.expensesStatistic, params, function (response) {
		if (response == undefined || response.data.length <= 1) {
			$('#finance-expensesStatistic-chart').html('<?= __('Statistic is empty') ?>');
			return;
		}
		var data = google.visualization.arrayToDataTable(response.data);
		var options = {
			title: '',
			pieHole: 0.4,
			chartArea: {width: '100%', height: '90%'}
		};
		var chart = new google.visualization.PieChart(document.getElementById('finance-expensesStatistic-chart'));
		chart.draw(data, options);
		$(window).resize(function () {
			chart.draw(data, options);
		});
	});
}

// Events for chart re-rendering
$('#finance-chart-statistic-currency').on('change', function () {
	chartStatisticRender();
});
var financeChartStattisticNoRender = false;
$('#finance-chart-statistic-period button').on('click', function () {
	financeChartStattisticNoRender = true;
	$('#finance-chart-statistic-period button').removeClass('active');
	$('#finance-chart-statistic-from').datetimepicker("reset");
	$('#finance-chart-statistic-to').datetimepicker("reset");
	$(this).addClass('active');
	chartStatisticRender();
	financeChartStattisticNoRender = false;
});
$('#finance-chart-statistic-from-input, #finance-chart-statistic-to-input').on('change', function () {
	if (financeChartStattisticNoRender){
		return;
	}
	$('#finance-chart-statistic-period button').removeClass('active');
	chartStatisticRender();
});

// Calendars
$('#finance-chart-statistic-from').datetimepicker({
	format: '<?= $dateFormat?>',
	weekStart: 1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	language:"<?=$lang?>",
	linkField: 'finance-chart-statistic-from-input',
	linkFormat: 'yyyy-mm-dd hh:ii:ss'
});
$('#finance-chart-statistic-to').datetimepicker({
	format: '<?= $dateFormat?>',
	weekStart: 1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	language:"<?=$lang?>",
	linkField: 'finance-chart-statistic-to-input',
	linkFormat: 'yyyy-mm-dd hh:ii:ss'
});
</script>