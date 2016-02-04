<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
function onLoad() {
	document.payForm.submit();
}
</script>
</head>
<body onload="onLoad()">
<form action="<?=Configure::read('paypal.URL')?>" method="post">

<input type="hidden" name="cmd" value="_xclick"> 
<input type="hidden" name="business" value="<?=Configure::read('paypal.merchant')?>"> 
<input type="hidden" name="item_name" value="<?=__('Recharge balance of user account on %s', DOMAIN_TITLE)?>"> 
<input type="hidden" name="item_number" value="<?=$item_number?>">
<input type="hidden" name="amount" value="<?=$total?>">
<input type="hidden" name="no_shipping" value="1"> 
<input type="hidden" name="rm" value="2"> 

<input type="hidden" name="return" value="<?=$this->Html->url(array('controller' => 'Payment', 'action' => 'paid'), true)?>"> 
<input type="hidden" name="notify_url" value="<?=$this->Html->url(array('controller' => 'Payment', 'action' => 'ipnPaypal'), true)?>"> 
<input type="hidden" name="cancel_return" value="<?=$this->Html->url(array('controller' => 'Payment', 'action' => 'cancel'), true)?>">
<input type="hidden" name="currency_code" value="USD">

<input type="submit" value="Go">

</form>
</body>
</html>
