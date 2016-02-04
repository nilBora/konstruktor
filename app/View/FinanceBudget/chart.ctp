<?
$this->Html->script(array(
	'vendor/jquery/jquery-1.10.2.min',
	'vendor/bootstrap.min',
	'https://www.google.com/jsapi',
), array('inline' => false));
echo $this->fetch('script');
echo $this->Html->meta('icon');

$vendorCss = array(
	'fonts',
	'bootstrap.min'
);
$css = array(
	'main-panel-new',
	'style'
);
foreach($css as &$_css) {
	$_css.= '.css?v='.Configure::read('version');
}
echo $this->Html->css(array_merge($vendorCss, $css));

$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
$budgets = $aFinanceBudget;
$monthNames = array(
	1 => __(date('F', strtotime($month1))),
	2 => __(date('F', strtotime($month2))),
	3 => __(date('F', strtotime($month3))),
	4 => __(date('F', strtotime($month4))),
);

$chartPlanExpense = $chartPlanIncome = $chartExpense = $chartIncome = array(
	$monthNames[1] => 0,
	$monthNames[2] => 0,
	$monthNames[3] => 0,
	$monthNames[4] => 0,
);

if (!empty($categories)) {
	$totalExpense = 0;
	$expense1 = 0;
	$expense2 = 0;
	$expense3 = 0;
	$expense4 = 0;
	$expensePlan = 0;
	foreach ($categories as $i => $item) {
		if ($item['category']['type'] != 1 || !isset($budgets[$item['category']['id']])) {
			continue;
		}
		$budget = $budgets[$item['category']['id']]['FinanceBudget'];
		$sum_amount_1 = $item[0]['sum_amount_1'];
		$sum_amount_2 = $item[0]['sum_amount_2'];
		$sum_amount_3 = $item[0]['sum_amount_3'];
		$sum_amount_4 = $item[0]['sum_amount_4'];
		$sum_amount_1 *= -1;
		$sum_amount_2 *= -1;
		$sum_amount_3 *= -1;
		$sum_amount_4 *= -1;
		$plan = $budget['plan'];
		$categoryName = $item['category']['name'];
		// chart
		$chartPlanExpense[$monthNames[1]] += $plan;
		$chartPlanExpense[$monthNames[2]] += $plan;
		$chartPlanExpense[$monthNames[3]] += $plan;
		$chartPlanExpense[$monthNames[4]] += $plan;

		$expense1 += $sum_amount_1;
		$expense2 += $sum_amount_2;
		$expense3 += $sum_amount_3;
		$expense4 += $sum_amount_4;
		$expensePlan += $plan;
	}
	$totalExpense = $expense1 + $expense2 + $expense3 + $expense4;

	$totalIncome = 0;
	$income1 = 0;
	$income2 = 0;
	$income3 = 0;
	$income4 = 0;
	$incomePlan = 0;
	foreach ($categories as $item) {
		if ($item['category']['type'] != 0 || !isset($budgets[$item['category']['id']])) {
			continue;
		}
		$budget = $budgets[$item['category']['id']]['FinanceBudget'];
		$sum_amount_1 = $item[0]['sum_amount_1'];
		$sum_amount_2 = $item[0]['sum_amount_2'];
		$sum_amount_3 = $item[0]['sum_amount_3'];
		$sum_amount_4 = $item[0]['sum_amount_4'];
		$plan = $budget['plan'];
		// chart
		$chartPlanIncome[$monthNames[1]] += $plan;
		$chartPlanIncome[$monthNames[2]] += $plan;
		$chartPlanIncome[$monthNames[3]] += $plan;
		$chartPlanIncome[$monthNames[4]] += $plan;

		$income1 += $sum_amount_1;
		$income2 += $sum_amount_2;
		$income3 += $sum_amount_3;
		$income4 += $sum_amount_4;
		$incomePlan += $plan;
	}
	$balance1 = $income1 - $expense1;
	$balance2 = $income2 - $expense2;
	$balance3 = $income3 - $expense3;
	$balance4 = $income4 - $expense4;
	$planBalance = $incomePlan - $expensePlan;
	// chart
	$chartIncome[$monthNames[1]] = $income1;
	$chartIncome[$monthNames[2]] = $income2;
	$chartIncome[$monthNames[3]] = $income3;
	$chartIncome[$monthNames[4]] = $income4;

	$chartExpense[$monthNames[1]] = $expense1;
	$chartExpense[$monthNames[2]] = $expense2;
	$chartExpense[$monthNames[3]] = $expense3;
	$chartExpense[$monthNames[4]] = $expense4;
}
?>

<div class="budgetGraphic">
	<div class="btn-group" id="finance-chart-budget-type">
		<button type="button" class="btn btn-default active" data-value="expense"><?= __('Expense') ?></button>
		<button type="button" class="btn btn-default" data-value="income"><?= __('Income') ?></button>
	</div>
	<div id="finance-budget-chart" style="height: 350px; margin-top: 34px"></div>
</div>

<script type="application/javascript">
	$(document).ready(function () {
// Events
		$('#finance-report-filter-fromMonth-mirror').on('change', function () {
			$('#finance-budget-filter').find('[name="fromMonth"]').val($(this).val());
			$('#finance-budget-filter').submit();
		});
	});
	// Charts
	var $chartPlanExpense = <?= json_encode($chartPlanExpense)?>;
	var $chartPlanIncome = <?= json_encode($chartPlanIncome)?>;
	var $chartExpense = <?= json_encode($chartExpense)?>;
	var $chartIncome = <?= json_encode($chartIncome)?>;

	var chartBudgetRender = function() {
		var budgetType = $('#finance-chart-budget-type button.active').data('value');
		var data = new google.visualization.DataTable();

		data.addColumn('string', 'Month'); // X-axis
		data.addColumn('number', '<?=__('fact')?>'); // Y-axis
		//data.addColumn('number', '<?=__('plan')?>'); // Y-axis
		//data.addColumn('number', '<?=__('diff')?>'); // Y-axis

		if (budgetType == 'expense') {
			var fact = $chartExpense;
			var plan = $chartPlanExpense;
		} else {
			var fact = $chartIncome;
			var plan = $chartPlanIncome;
		}

		var months = Object.keys(fact);
		for (var i = 0; i < months.length; i++) {
			var month = months[i];
			//data.addRow([month.substr(0, 3), fact[month], plan[month], fact[month] - plan[month]]);
			data.addRow([month.substr(0, 3), fact[month]]);
		}

		var options = {
			title: '',
			vAxis: {
				minValue: 0,
				gridlines: {color: 'transparent'}
			},
			pointSize: 11,
			colors:['#FF9396', '#22b3b0', '#9E6E6E'],
			series: {
				0: {areaOpacity: 1},
				1: {areaOpacity: 0.3},
				2: {areaOpacity: 1}
			},
			lineWidth: 0,
			'legend': {'position': 'top'},
			chartArea: {width: '92%', height: '85%'}
		};

		var chart = new google.visualization.AreaChart(document.getElementById('finance-budget-chart'));
		chart.draw(data, options);

		$(window).resize(function () {
			chart.draw(data, options);
		});
	};

	// Filter
	$('#finance-budget-filter [name="accountId"]').on('change', function () {
		$('#finance-budget-filter').submit();
	});

	// Load google charts
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(function () {
		chartBudgetRender();
	});

	// Events for chart re-rendering
	$('#finance-chart-budget-type button').on('click', function () {
		$('#finance-chart-budget-type button').removeClass('active');
		$(this).addClass('active');
		chartBudgetRender();
	});
</script>