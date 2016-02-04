<? if (!empty($categories)) { ?>
	<div class="title"><?= __('Income')?></div>
<?
	$income1 = 0;
	$income2 = 0;
	foreach ($categories as $item) {
		if ($item['category']['type'] == 0) {
			if (!$item[0]['sum_amount_1'] && !$item[0]['sum_amount_2']) {
				continue;
			}
?>
			<div class="item">
				<span class="name"><?= $item['category']['name'] ?></span>
				<span class="value1"><?= $item[0]['sum_amount_1'] ?></span>
				<span class="value2"><?= $item[0]['sum_amount_2'] ?></span>
			</div>
			<?
			$income1 += $item[0]['sum_amount_1'];
			$income2 += $item[0]['sum_amount_2'];
		}
	}
?>
	<div class="item total">
		<span class="name"></span>
		<span class="value1"><?= $this->Money->symbolFor($currency) ?> <?= $income1 ?></span>
		<span class="value2"><?= $this->Money->symbolFor($currency) ?> <?= $income2 ?></span>
	</div>

	<div class="title"><?= __('Expense')?></div>
<?
	$expense1 = 0;
	$expense2 = 0;
	foreach ($categories as $item) {
		if (!$item[0]['sum_amount_1'] && !$item[0]['sum_amount_2']) {
			continue;
		}
		if ($item['category']['type'] == 1) {
			$item[0]['sum_amount_1'] *= -1;
			$item[0]['sum_amount_2'] *= -1;
?>
			<div class="item">
				<span class="name"><?= $item['category']['name'] ?></span>
				<span class="value1"><?= $item[0]['sum_amount_1'] ?></span>
				<span class="value2"><?= $item[0]['sum_amount_2'] ?></span>
			</div>
<?
			$expense1 += $item[0]['sum_amount_1'];
			$expense2 += $item[0]['sum_amount_2'];
		}
	}
?>
	<div class="item total">
		<span class="name"></span>
		<span class="value1"><?= $this->Money->symbolFor($currency) ?> <?= $expense1 ?></span>
		<span class="value2"><?= $this->Money->symbolFor($currency) ?> <?= $expense2 ?></span>
	</div>
	<div class="item residue">
		<span class="name"><?= __('Residue') ?></span>
		<? $negativeClass = (($income1 - $expense1) < 0) ? 'negative' : '' ?>
		<span class="value1 <?= $negativeClass ?>"><?= $this->Money->symbolFor($currency) ?> <?= ($income1 - $expense1) ?></span>
		<? $negativeClass = (($income2 - $expense2) < 0) ? 'negative' : '' ?>
		<span class="value2 <?= $negativeClass ?>"><?= $this->Money->symbolFor($currency) ?> <?= ($income2 - $expense2) ?></span>
	</div>
<? } else { ?>
	<?= __('Result is empty') ?>
<? } ?>