<?=$this->element('admin_title', array('title' => __('Dashboard')))?>
<div class="span8 offset2">
	<?=$this->element('admin_content')?>
	
	<table class="grid table-bordered shadow pull-left">
	<thead>
		<tr class="first table-gradient">
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('Registered today')?></a></th>
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('Qty')?></a></th>
		</tr>
	</thead>
	<tbody>
<?
	$total = 0;
	foreach($aStatsToday as $stat) {
		$country = (isset($aCountryOptions[$stat['User']['live_country']])) ? $aCountryOptions[$stat['User']['live_country']] : '';
		$total+= $stat[0]['count'];
?>
	<tr class="grid-row">
		<td><?=$country?></td>
		<td class="text-right"><?=$stat[0]['count']?></td>
	</tr>
<?
	}
	if (!$aStatsToday) {
?>
	<tr>
		<td colspan="2" style="padding: 10px 5px"><?=__('No registered users yet')?></td>
	</tr>
<?
	}
?>
	<tr class="first table-gradient">
		<td class="text-right" style="padding: 3px 5px"><a class="grid-unsortable" href="javascript:void(0)"><b><?=__('Total')?></b></a></td>
		<td class="text-right" style="padding: 3px 5px"><a class="grid-unsortable" href="javascript:void(0)"><b><?=$total?></b></a></td>
	</tr>
	</tbody>
	</table>
	
	
	<table class="grid table-bordered shadow floatL">
	<thead>
		<tr class="first table-gradient">
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('Registered total')?></a></th>
			<th><a class="grid-unsortable" href="javascript:void(0)"><?=__('Qty')?></a></th>
		</tr>
	</thead>
	<tbody>
<?
	$total = 0;
	foreach($aStats as $stat) {
		$country = (isset($aCountryOptions[$stat['User']['live_country']])) ? $aCountryOptions[$stat['User']['live_country']] : '';
		$total+= $stat[0]['count'];
?>
	<tr class="grid-row">
		<td><?=$country?></td>
		<td class="text-right"><?=$stat[0]['count']?></td>
	</tr>
<?
	}
?>
	<tr class="first table-gradient">
		<td class="text-right" style="padding: 3px 5px"><a class="grid-unsortable" href="javascript:void(0)"><b><?=__('Total')?></b></a></td>
		<td class="text-right" style="padding: 3px 5px"><a class="grid-unsortable" href="javascript:void(0)"><b><?=$total?></b></a></td>
	</tr>
	</tbody>
	</table>
	<?=$this->element('admin_content_end')?>
</div>

