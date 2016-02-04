<?
	$aIcons = array(
		1 => 'icon glyphicons ipad',
		4 => 'icon glyphicons imac',
		5 => 'icon glyphicons print'
	);
	if (isset($product_type_id)) {
		$id = $product_type_id;
	}
?>
<span class="<?=$aIcons[$id]?>"></span>