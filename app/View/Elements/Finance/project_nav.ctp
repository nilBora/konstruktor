<?
	$isProjectActive = $this->request->controller == 'FinanceProject';
	$isOperationActive = $this->request->controller == 'FinanceOperation';
	$isReportActive = $this->request->controller == 'FinanceReport';
	$isGoalActive = $this->request->controller == 'FinanceGoal';
	$isBudgetActive = $this->request->controller == 'FinanceBudget';
?>

<div class="navigation">
	<? if ($isOwner || $isFullAccess || !empty($currUserShare['accounts'])) { ?>
		<? if ($isProjectActive) { ?>
			<span class="btn btn-default active"><?= __('Accounts') ?></span>
		<? } else { ?>
			<a class="underlink" href="<?= $this->Html->url(array('controller' => 'FinanceProject', 'action' => 'index', $id)) ?>"><?= __('Accounts') ?></a>
		<? } ?>
	<? } ?>

	<? if ($isOwner || $isFullAccess || (!empty($currUserShare['operations']) && !empty($currUserShare['accounts']))) { ?>
		<? if ($isOperationActive) { ?>
			<span class="btn btn-default active"><?= __('Operations') ?></span>
		<? } else { ?>
			<a class="underlink" href="<?= $this->Html->url(array('controller' => 'FinanceOperation', 'action' => 'index', $id)) ?>"><?= __('Operations') ?></a>
		<? } ?>
	<? } ?>

	<? if ($isOwner || $isFullAccess || !empty($currUserShare['accounts'])) { ?>
		<? if ($isReportActive) { ?>
			<span class="btn btn-default active"><?= __('Reports') ?></span>
		<? } else { ?>
			<a class="underlink" href="<?= $this->Html->url(array('controller' => 'FinanceReport', 'action' => 'index', $id)) ?>"><?= __('Reports') ?></a>
		<? } ?>
	<? } ?>

	<? if ($isOwner || $isFullAccess || !empty($currUserShare['goals'])) { ?>
		<? if ($isGoalActive) { ?>
			<span class="btn btn-default active"><?= __('Goals') ?></span>
		<? } else { ?>
			<a class="underlink" href="<?= $this->Html->url(array('controller' => 'FinanceGoal', 'action' => 'index', $id)) ?>"><?= __('Goals') ?></a>
		<? } ?>
	<? } ?>

	<? if ($isOwner || $isFullAccess || !empty($currUserShare['budgets'])) { ?>
		<? if ($isBudgetActive) { ?>
			<span class="btn btn-default active"><?= __('Budgets') ?></span>
		<? } else { ?>
			<a class="underlink" href="<?= $this->Html->url(array('controller' => 'FinanceBudget', 'action' => 'index', $id)) ?>"><?= __('Budgets') ?></a>
		<? } ?>
	<? } ?>
</div>