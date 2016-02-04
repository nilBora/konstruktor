<div class="span8 offset2">
<?
	$title = __('Order payments');
	echo $this->element('admin_title', compact('title'));
	echo $this->element('admin_content');
?>
	<table class="grid table-bordered shadow">
	<thead>
		<tr class="first table-gradient">
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('Order')?></a></th>
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('User ID')?></a></th>
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('Contractor')?></a></th>
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('Contact Person')?></a></th>
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('Total')?></a></th>
		</tr>
	</thead>
	<tbody>
<?
	$total = 0;
	foreach($aData as $orderID => $data) {
		$user_id = $data['Order']['contractor_id'];
		$contractor = $aContractorOptions[$user_id];
		$total+= $data['Total'];
?>
	<tr class="grid-row">
		<td><?=$this->element('order_num', $data['Order'])?></td>
		<td><?=$user_id?></td>
		<td><?=$contractor['title']?></td>
		<td><a href="mailto:<?=$contractor['email']?>"><?=$contractor['contact_person']?></a></td>
		<td class="text-right"><?=$data['Total']?></td>
	</tr>
<?
	}
	if (!$aData) {
?>
	<tr>
		<td colspan="5" style="padding: 10px 5px"><?=__('No orders to process')?></td>
	</tr>
<?
	}
?>
	<tr class="first table-gradient">
		<td colspan="4" class="text-right" style="padding: 3px 5px"><a class="grid-unsortable" href="javascript:void(0)"><b><?=__('Total')?></b></a></td>
		<td class="text-right" style="padding: 3px 5px"><a class="grid-unsortable" href="javascript:void(0)"><b><?=$total?></b></a></td>
	</tr>
	</tbody>
	</table>
<?
	echo $this->element('admin_content_end');
?>
</div>
