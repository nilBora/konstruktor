var structURL = {
    deviceList: '<?=$this->Html->url(array('controller' => 'DeviceAjax', 'action' => 'deviceList'))?>',
    panel: '<?=$this->Html->url(array('controller' => 'DeviceAjax', 'action' => 'panel'))?>',
    distrib: '<?=$this->Html->url(array('controller' => 'DeviceAjax', 'action' => 'distrib'))?>.json',
    block: '<?=$this->Html->url(array('controller' => 'DeviceAjax', 'action' => 'block'))?>.json'
}
