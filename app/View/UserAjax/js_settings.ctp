var profileURL = {
	panel: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'panel'))?>',
	timelineEvents: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'timelineEvents'))?>.json',
	updateEvent: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'updateEvent'))?>.json',
	deleteEvent: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'deleteEvent'))?>.json',

	acceptEvent: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'acceptEvent'))?>.json',
	declineEvent: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'declineEvent'))?>.json',
	changeEventCategory: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'changeEventCategory'))?>.json',

	skillList: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'skillList'))?>.json',
	eventOperation: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'getFinanceOperation'))?>.json',
	saveToCloud: '<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'saveToCloud'))?>.json',

	fileSendHere: '<?=$this->Html->url(array('controller' => 'ChatAjax', 'action' => 'sendTimelineFiles'))?>.json'
}
//TODO: Maybe not needed anymore notifications for profile incompleteness
//var userLocale = {
//	notifyProfile: '<?=__('Enter your information in the User settings')?>'
//}
//var notifyProfile = <?php //echo ($notifyProfile) ? 'true' : 'false'?>;
