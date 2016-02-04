<h1 class="page-header"><?php echo __('Dashboard') ?></h1>
<div class="row">
	<div class="col-md-3">

		<table class="table table-striped">
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
	</div>
	<div class="col-md-3">
		<table class="table table-striped">
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
	</div>
	<div class="col-md-3">
		<?php
			echo $this->Form->create('BoostCake', array(
				'inputDefaults' => array(
					'div' => 'form-group',
					'wrapInput' => false,
					'class' => 'form-control'
				),
				'class' => 'well'
			));
		?>
		<fieldset>
			<legend>Legend</legend>
			<?php echo $this->Form->input('text', array(
				'label' => 'Label name',
				'placeholder' => 'Type something…',
				'after' => '<span class="help-block">Example block-level help text here.</span>'
			)); ?>
			<?php echo $this->Form->input('checkbox', array(
				'label' => 'Check me out',
				'class' => false
			)); ?>
			<?php echo $this->Form->submit('Submit', array(
				'div' => 'form-group',
				'class' => 'btn btn-default'
			)); ?>
		</fieldset>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
