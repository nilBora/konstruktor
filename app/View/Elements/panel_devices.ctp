<div class="dropdown-panel-scroll">
	<?=$this->element('device_list')?>
</div>
<div class="b-order-bottom-device clearfix">
    <a href="<?=$this->Html->url(array('controller' => 'Device', 'action' => 'orders'), true)?>" class="underlink my-orders" style="text-decoration: none"><?=__('My orders')?></a>
    <a href="javascript:void(0)" class="btn disabled order-device"><?=__('Create order')?></a>
</div>