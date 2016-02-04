<?php
$isSponsorsActive = ($this->request->controller == 'InvestProject' && $this->request->action == 'listSponsors');
$isEditActive = ($this->request->controller == 'InvestProject' && $this->request->action == 'editProject');
$isViewActive = ($this->request->controller == 'InvestProject' && $this->request->action == 'view');
?>
<?php if ($showNav) { ?>
<!--div class="controlButtons">
	<a class="btn btn-default smallBtn <? if ($isSponsorsActive) { ?>active<? } ?>"
	   href="<?= $this->Html->url(array('controller' => 'InvestProject', 'action' => 'listSponsors', $id)) ?>"
	>
		<span class="glyphicons coins"></span>
	</a>
	<a class="btn btn-default smallBtn <? if ($isEditActive) { ?>active<? } ?>"
	   href="<?= $this->Html->url(array('controller' => 'InvestProject', 'action' => 'editProject', $id)) ?>"
		>
		<span class="glyphicons wrench"></span>
	</a>
	<a class="btn btn-default smallBtn <? if ($isViewActive) { ?>active<? } ?>"
	   href="<?= $this->Html->url(array('controller' => 'InvestProject', 'action' => 'view', $id)) ?>"
		>
		<span class="glyphicons eye_open"></span>
	</a>
</div-->
<style>
.controlButtons a{
	color: #999;
	text-decoration: none;
	font-size: 19px;
	padding: 5px;

}
.controlButtons .glyphicons:before{
	position: relative;
}
.controlButtons .glyphicons.eye_open:before{
	top: -1px;
}
.controlButtons .glyphicons.edit:before{
	top: -3px;
}
</style>
<div class="controlButtons">
	<?php if($currUserID == $investProject['InvestProject']['user_id']):?>
	<a class="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'listSponsors', $id ))?>"  href="#">
		<div style="color: #FFF; background-color: #12d09b;font-size: 13px; border-radius: 15px;width: 23px;padding: 2px 8px;display: inline-block;">$</div>
	</a>
	<?php endif;?>
	<a class="" href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'view', $id ))?>">
		<span class="glyphicons eye_open"></span>
	</a>
	<?php if($currUserID == $investProject['InvestProject']['user_id']):?>
	<a class="" href="javascript:edit()">
		<span class="glyphicons edit"></span>
	</a>
	<?php endif;?>
</div>
<?php } ?>
