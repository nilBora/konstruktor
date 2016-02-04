<div class="title"><?= __('Balance') ?></div>
<div class="btn-group" id="finance-chart-balance-period">
	<button type="button" class="btn btn-default active" data-value="week"><?= __('Week')?></button>
	<button type="button" class="btn btn-default" data-value="month"><?= __('Month')?></button>
	<button type="button" class="btn btn-default" data-value="quarter"><?= __('Quarter')?></button>
	<button type="button" class="btn btn-default" data-value="year"><?= __('Year')?></button>
</div>
<div class="rightButtons">
	<select class="currency" id="finance-chart-balance-currency">
		<? foreach ($this->Money->symbols() as $code => $symbol) { ?>
			<option value="<?= $code ?>"><?= $symbol?></option>
		<? } ?>
	</select>
	<a href="javascript:void(0)" class="btn btn-default smallBtn finance-chart-balance-type" data-value="column"><span class="glyphicons charts"></span></a>
	<a href="javascript:void(0)" class="btn btn-default smallBtn finance-chart-balance-type active" data-value="area"><span class="glyphicons stats"></span></a>
</div>
<div id="finance-operation-chart" style="height: 350px; margin-top: 34px"></div>

<script type="application/javascript">
var renderSVG = function ($element) {
	$element.find('svg').find('g:first')
		.append('<rect x="'+
		$element.find('svg').find('g:first').find('rect:first').attr('x')
		+'" y="0" width="1" height="'+
		(
		parseInt($element.find('svg').find('g:first').find('rect:first').attr('y'),10)
		+
		parseInt($element.find('svg').find('g:first').find('rect:first').attr('height'),10)
		) +
		'" stroke="none" stroke-width="0" fill="#000000"/>');
	$element.html($element.html());
};
var chartBalanceRender = function() {
	var chartType = $('.finance-chart-balance-type.active').data('value');
	var params = {
		project_id: <?= $id?>,
		currency: $('#finance-chart-balance-currency').val(),
		period: $('#finance-chart-balance-period button.active').data('value')
	};

	$.post(financeURL.operationChartData, params, function (response) {
		if (response == undefined || response.data.length <= 1) {
			$('#finance-operation-chart').html('<?= __('Balance is empty') ?>');
			return;
		}
		var rdata = response.data;
		//var data = google.visualization.arrayToDataTable(rdata);
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Created');
		data.addColumn('number', 'Balance');
		data.addColumn({type:'string',role:'tooltip'});
		for (var i = 1; i < rdata.length; i++) {
			var tooltip = '<?= __('Created')?>: ' + rdata[i][0] + "\n <?= __('Balance')?>: ";
			var value = rdata[i][1];
			var symbol = $("#finance-chart-balance-currency option:selected" ).text();
			if (parseFloat(rdata[i][1]) < 0) {
				tooltip += '-';
				value *= -1;
			}
			tooltip += ' ' + symbol + ' ' + value;
			data.addRow([rdata[i][0], rdata[i][1], tooltip]);
		}
		var options = {
			title: '',
			hAxis: {
				title: '',
				titleTextStyle: {
					color: '#333'
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
			colors:['#22b3b0'],
			series: {
				0: {areaOpacity: 0.1},
				1: {areaOpacity: 0.8}
			},
			lineWidth: 0,
			legend: {position: 'none'},
			chartArea: {width: '85%', height: '85%'}
		};
		if (chartType == 'column') {
			options.colors = ['#EAF8F8'];
			var chart = new google.visualization.ColumnChart(document.getElementById('finance-operation-chart'));
		} else if (chartType == 'area') {
			var chart = new google.visualization.AreaChart(document.getElementById('finance-operation-chart'));
		}
		chart.draw(data, options);
		renderSVG($('#finance-operation-chart'));
	});
};

// Events for chart re-rendering
$('#finance-chart-balance-currency').on('change', function () {
	chartBalanceRender();
});
$('#finance-chart-balance-period button').on('click', function () {
	$('#finance-chart-balance-period button').removeClass('active');
	$(this).addClass('active');
	chartBalanceRender();
});
$('.finance-chart-balance-type').on('click', function () {
	$('.finance-chart-balance-type').removeClass('active');
	$(this).addClass('active');
	chartBalanceRender();
});
$(window).resize(function () {
	chartBalanceRender();
});
</script>