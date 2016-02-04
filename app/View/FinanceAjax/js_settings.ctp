var financeURL = {
	panel: '<?= $this->Html->url(array('controller' => 'FinanceAjax', 'action' => 'panel')) ?>',
	addProject: '<?= $this->Html->url(array('controller' => 'FinanceAjax', 'action' => 'addProject')) ?>',
	delProject: '<?= $this->Html->url(array('controller' => 'FinanceAjax', 'action' => 'delProject')) ?>',
	successDelProject: '<?= $this->Html->url(array('controller' => 'FinanceProject', 'action' => 'successDeleted')) ?>',

	listAccount: '<?= $this->Html->url(array('controller' => 'FinanceAccount', 'action' => 'getList')) ?>',
	addAccount: '<?= $this->Html->url(array('controller' => 'FinanceAccount', 'action' => 'addAccount')) ?>',
	editAccount: '<?= $this->Html->url(array('controller' => 'FinanceAccount', 'action' => 'editAccount')) ?>',
	delAccount: '<?= $this->Html->url(array('controller' => 'FinanceAccount', 'action' => 'delAccount')) ?>',

	listCategory: '<?= $this->Html->url(array('controller' => 'FinanceCategory', 'action' => 'getList')) ?>',
	addCategory: '<?= $this->Html->url(array('controller' => 'FinanceCategory', 'action' => 'addCategory')) ?>',
	editCategory: '<?= $this->Html->url(array('controller' => 'FinanceCategory', 'action' => 'editCategory')) ?>',
	delCategory: '<?= $this->Html->url(array('controller' => 'FinanceCategory', 'action' => 'delCategory')) ?>',
	expensesStatistic: '<?= $this->Html->url(array('controller' => 'FinanceCategory', 'action' => 'getStatistic')) ?>.json',

	addOperation: '<?= $this->Html->url(array('controller' => 'FinanceOperation', 'action' => 'addOperation')) ?>',
	editOperation: '<?= $this->Html->url(array('controller' => 'FinanceOperation', 'action' => 'editOperation')) ?>',
	delOperation: '<?= $this->Html->url(array('controller' => 'FinanceOperation', 'action' => 'delOperation')) ?>',
	operationChartData: '<?= $this->Html->url(array('controller' => 'FinanceOperation', 'action' => 'chartData')) ?>.json',
	operationShowMore: '<?= $this->Html->url(array('controller' => 'FinanceOperation', 'action' => 'showMore')) ?>',
	compareAccounts: '<?= $this->Html->url(array('controller' => 'FinanceOperation', 'action' => 'compareAccounts')) ?>',

	addGoal: '<?= $this->Html->url(array('controller' => 'FinanceGoal', 'action' => 'addGoal')) ?>',
	editGoal: '<?= $this->Html->url(array('controller' => 'FinanceGoal', 'action' => 'editGoal')) ?>',
	delGoal: '<?= $this->Html->url(array('controller' => 'FinanceGoal', 'action' => 'delGoal')) ?>',

	addBudget: '<?= $this->Html->url(array('controller' => 'FinanceBudget', 'action' => 'addBudget')) ?>',

	searchUser: '<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'searchUser')) ?>.json',
	sendInvite: '<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'sendInvite')) ?>',
	setFullAccess: '<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'setFullAccess')) ?>',
	unsetFullAccess: '<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'unsetFullAccess')) ?>',
	deleteUser: '<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'deleteUser')) ?>'
}