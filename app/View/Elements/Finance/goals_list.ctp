<?
/**
 * @property MoneyHelper Money
 */
foreach ($aFinanceGoal as $item) {
	$goal = $item['FinanceGoal'];
	$account = $item['Account'];
	$balances = $item['Balance'];
	$currencySymbol = $this->Money->symbolFor($account['currency']);
?>
<div class="goalItem">
	<div class="name">
		<div class="title"><?= $goal['name'] ?></div>
		<div class="account"><?= $account['name'] ?></div>
	</div>
	<a href="<?= $this->Html->url(array('controller' => 'FinanceGoal', 'action' => 'editGoal', $goal['id'])) ?>" data-toggle="modal" data-target="#finance-modal-save-goal" class="btn btn-default smallBtn"><span class="glyphicons pencil"></span></a>
	<? if ($isOwner) {?>
	<a href="javascript:void(0)" data-id="<?= $goal['id'] ?>" class="btn btn-default smallBtn finance-del-goal"><span class="glyphicons bin"></span></a>
	<? } ?>
	<div class="description">
		<?
		$start = new DateTime($goal['created']);
		$finish = new DateTime($goal['finish']);
		$delta = $finish->diff($start);
		$numberMonths = 12*$delta->y + $delta->m + 1;
		$regularPayment = $currencySymbol . $this->Money->format($goal['final_sum'] / $numberMonths);
		$finalSum = $currencySymbol . $this->Money->format($goal['final_sum']);
		?>

		<?= sprintf(__('The total amount is %s for %s mo'), $finalSum, $numberMonths) ?>.
		<?= sprintf(__('You have to deposit %s monthly to related account'), $regularPayment) ?>. <br>
		<?= __('Start') ?> — <?= date('j', strtotime($goal['created'])) ?> <?= strtolower(__(date('M', strtotime($goal['created'])))) ?> <?= date('Y', strtotime($goal['created'])) ?>,
		<?= __('finish') ?> — <?= date('j', strtotime($goal['finish'])) ?> <?= strtolower(__(date('M', strtotime($goal['finish'])))) ?> <?= date('Y', strtotime($goal['finish'])) ?>.
	</div>
	<!-- Goal time line -->
	<div class="goalGraphic clearfix">
		<div class="head">
			<div class="accumulation"><?= __('Accumulated') ?></div>
			<div class="putAside"><?= __('Put aside')?></div>
			<div class="line"></div>
		</div>
<?
	if (empty($balances)) {
		for($k = 0; $k < $numberMonths; $k++) {
?>
			<div class="item circle_round disabled">
				<div class="accumulation"><?= $currencySymbol ?> <?= 0 ?></div>
				<div class="putAside"><?= $regularPayment ?></div>
				<div class="line"></div>
				<span class="glyphicons circle_round"></span>
				<div class="month"><?= strtolower(__(date('M', strtotime("+$k month", strtotime($goal['created']))))) ?></div>
			</div>
<?
		}
	} else {
		$start = $goal['startBalance'];
		$monthlyAccumulate = 0;
		$accumulate = 0;
		$regularPayment = $goal['final_sum'] / $numberMonths;
		$finalSum = $goal['final_sum'];
		$reserve = 0;
		$i = 0;
		foreach ($balances as $balance) {
			$i++;
			$monthlyAccumulate = $balance['FinanceOperation']['balance_after'] - $start;
			$accumulate += $monthlyAccumulate;
			$class = 'circle_ok';
			if ((($monthlyAccumulate + $reserve) < $regularPayment) && $accumulate < $finalSum) {
				$class = 'circle_remove';
			}
		?>
			<div class="item <? if ($class == 'circle_round') {?>disabled<? } ?>">
				<div class="accumulation"><?= $currencySymbol?> <?= $accumulate ?></div>
				<div class="putAside"><?= $currencySymbol?> <?= $monthlyAccumulate ?></div>
				<div class="line"></div>
				<span class="glyphicons <?= $class ?>"></span>
				<div class="month"><?= strtolower(__(date('M', strtotime($balance['FinanceOperation']['date'])))) ?></div>
			</div>
		<?
			$start = $balance['FinanceOperation']['balance_after'];
			$reserve = $monthlyAccumulate - $regularPayment;
		}
		$monthForFinish = $numberMonths - $i;
		$regularPayment = $goal['final_sum'] / $monthForFinish;
		if ($monthForFinish > 0) {
			for($j = 1; $j <= $monthForFinish; $j++) {
				$class = 'circle_ok';
				if (($reserve < $regularPayment) && $accumulate < $finalSum) {
					$class = 'circle_round';
				}
		?>
				<div class="item <? if ($class == 'circle_round') {?>disabled<? } ?>">
					<div class="accumulation"><?= $currencySymbol?> <?= $accumulate ?></div>
					<div class="putAside"><?= $currencySymbol?> <?= $this->Money->format($regularPayment) ?></div>
					<div class="line"></div>
					<span class="glyphicons <?= $class ?>"></span>
					<div class="month"><?= strtolower(__(date('M', strtotime("+$j month", strtotime($balance['FinanceOperation']['date']))))) ?></div>
				</div>
		<?
			}
		}
	}
?>
	</div>
</div>
<? } ?>

<script>
$(document).ready(function () {
// Delete goal
	$('.finance-del-goal').on('click', function () {
		if (!confirm("<?= __('Are you sure ?') ?>")) {
			return;
		}
		$.post(financeURL.delGoal, {id: $(this).data('id')}, function (response) {
			if (response){
				alert(response);
				return;
			}
			location.reload(false);
		});
	});
});
</script>