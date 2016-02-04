<?
	if (isset($product_type_id)) {
		$id = $product_type_id;
	}
	if ($id == Configure::read('device.typePrinter')) {
		echo $this->element('sum', array('sum' => $arenda_price)).'/'.__('page');
	} else {
		echo $this->element('sum', array('sum' => $arenda_price)).'/'.__('month');
	}
