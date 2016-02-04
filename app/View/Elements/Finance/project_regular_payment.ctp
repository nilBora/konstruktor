<div class="regularPayment">
	<div class="inner">
		<div class="head"><span class="glyphicons alarm"></span><span><?= __('Regular payments') ?></span></div>
		<?
		foreach ($aRegularPayments as $item) {
			$operation = $item['FinanceOperation'];
			if (isset($item['FinanceGoal'], $item['FinanceAccount'])) {
				$goal = $item['FinanceGoal'];
				$account = $item['FinanceAccount'];
			}
		?>
			<? if (isset($goal['id'])) { ?>
				<? $remain = $goal['final_sum'] - $operation['accumulate'] ?>
				<? $remain = ($remain < 0 ? 0 : $remain) ?>
				<div class="subText1"><?= $goal['name'] ?></div>
				<div class="sum"><?= $this->Money->symbolFor($operation['currency']) ?><?= $this->Money->format($operation['accumulate']) ?></div>
				<div class="text1"><?= $account['name'] ?></div>
				<div class="text2"><?= __('Remain')?></div>
				<div class="text2"><?= $this->Money->symbolFor($operation['currency']) ?><?= $this->Money->format($remain) ?></div>
				<div class="subText2"><?= __('Last day') ?> —
					<?= date('j', strtotime($goal['finish'])) ?> <?= strtolower(__(date('M', strtotime($goal['finish'])))) ?> <?= date('Y', strtotime($goal['finish'])) ?>
				</div>
			<? } else { ?>
				<div class="sum"><?= $this->Money->symbolFor($operation['currency']) ?><?= $this->Money->format($operation['amount']) ?></div>
				<div class="text">
					<?= date('j', strtotime($operation['created'])) ?> <?= strtolower(__(date('M', strtotime($operation['created'])))) ?> <?= date('Y', strtotime($operation['created'])) ?>
					<? if ($operation['comment']) { ?>,<? } ?>
					<?= $operation['comment'] ?>
				</div>
			<? } ?>
		<? } ?>


		<!--
		<div class="sum">$2 000</div>
		<div class="text">11 июня, кредит, шуба</div>
		<hr />
		<div class="sum">$1 100</div>
		<div class="subText">Накоплено $1 800</div>
		<div class="text">Отложить 1 января 2015, iPhone 7+</div>
		<hr />
		<div class="subText1">Донор почки</div>
		<div class="sum">$311,1</div>
		<div class="text1">Master Card</div>
		<div class="text2">Осталось собрать</div>
		<div class="text2">$24 891</div>
		<div class="subText2">Последний день —  31 июля 2014</div>
		-->
	</div>
</div>