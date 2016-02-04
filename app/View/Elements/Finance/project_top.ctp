<?
$isSettingsActive = $this->request->controller == 'FinanceSettings';
$isShareActive = $this->request->controller == 'FinanceShare';
?>
<div class="row financeTop">
	<div class="col-sm-4 pull-right controlButtons">
		<a class="btn btn-default smallBtn" href="javascript:void(0)" data-toggle="modal" data-target="#calculator"><span class="glyphicons calculator"></span></a>
		<? if ($isOwner || $currUserShare['full_access']) { ?>
		<a class="btn btn-default smallBtn <? if ($isShareActive) { ?> active <? } ?>" href="<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'index', $id)) ?>"><span class="glyphicons parents"></span></a>
		<? } ?>
		<? if ($isOwner) { ?>
		<a class="btn btn-default smallBtn <? if ($isSettingsActive) { ?> active <? } ?>" href="<?= $this->Html->url(array('controller' => 'FinanceSettings', 'action' => 'index', $id)) ?>"><span class="glyphicons wrench"></span></a>
		<a class="btn btn-default smallBtn" href="javascript:void(0)" id="finance-del-project"><span class="glyphicons bin"></span></a>
		<? } ?>
	</div>
</div>
<?=$this->element('Finance/calc')?>
