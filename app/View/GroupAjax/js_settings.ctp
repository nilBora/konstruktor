var groupURL = {
    panel: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'panel'))?>',
    getGallery: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'getGallery'))?>.json',
    addGalleryVideo: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'addGalleryVideo'))?>.json',
    delGalleryVideo: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'delGalleryVideo'))?>.json',
    join: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'join'))?>.json',
    invite: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'invite'))?>.json',

    vacancyResponse: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'vacancyResponse'))?>.json',
    vacancyApprove: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'vacancyApprove'))?>.json',
    vacancyDecline: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'vacancyDecline'))?>.json',
    vacancyResponses: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'vacancyResponses'))?>.json',

    inviteAccept: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'acceptInvite'))?>.json',
    inviteDecline: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'declineInvite'))?>.json',
    memberApprove: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'memberApprove'))?>.json',
    memberRemove: '<?=$this->Html->url(array('controller' => 'GroupAjax', 'action' => 'memberRemove'))?>.json'
}
var groupDef = {
    maxImages: <?=Configure::read('groupMaxImages')?>
}
