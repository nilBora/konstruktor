<?
	$aStatus = array(
		0 => __('Processing order'), 
		1 => __('Requires a payment'),
		10 => __('Shipping'),
		11 => __('Ready')
	);
	$status = $order['Order']['paid']*10 + $order['Order']['shipped'];
	echo $aStatus[$status];
	