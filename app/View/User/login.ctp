<?
	$this->Html->script('login.js?v='.Configure::read('version'), array('inline' => false));
	$this->Html->script('vendor/jquery.nicescroll.min', array('inline' => false));

	if(isset($_GET['internal_traffic'])){
		setcookie ("internal_traffic", "1", strtotime( '+365 days' ), "/");
		$_COOKIE['internal_traffic'] = 1;
	}
?>

<?=$this->element('User/home')?>
