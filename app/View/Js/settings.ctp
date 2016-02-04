//ArticleAjax
var articleURL = {
    panel: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ArticleAjax', 'action' => 'panel'), true)?>',
    loadMore: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ArticleAjax', 'action' => 'loadMore'), true)?>',
    saveArticle: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ArticleAjax', 'action' => 'saveArticle'), true).'.json'?>',
}

//ChatAjax
<?php App::uses('ChatEvent', 'Model'); ?>
var chatURL = {
	// panel: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'panel'), true)?>.json',
	contactList: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'contactList'), true)?>.json',
	sendMsg: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'sendMsg'), true)?>.json',
	sendFiles: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'sendFiles'), true)?>.json',
	updateState: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'updateState'), true)?>.json',
	openRoom: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'openRoom'), true)?>.json',
	markRead: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'markRead'), true)?>.json',
	delContact: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'delContact'), true)?>.json',
	addMember: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'addMember'), true)?>.json',
	removeMember: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'removeMember'), true)?>.json',
	loadMore: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'loadMore'), true)?>.json',

	editMessage: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'editMessage'), true)?>.json',
	removeMessage: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'ChatAjax', 'action' => 'removeMessage'), true)?>.json'
}
chatUpdateTime = <?=Configure::read('chat.updateTime')?>;
chatDef = {
	outcomingMsg: <?=ChatEvent::OUTCOMING_MSG?>,
	incomingMsg: <?=ChatEvent::INCOMING_MSG?>,
	roomOpened: <?=ChatEvent::ROOM_OPENED?>,
	fileUploaded: <?=ChatEvent::FILE_UPLOADED?>,
	fileDownloadAvail: <?=ChatEvent::FILE_DOWNLOAD_AVAIL?>,
	invitedUser: <?=ChatEvent::INVITED_USER?>,
	wasInvited: <?=ChatEvent::WAS_INVITED?>,
	joinedRoom: <?=ChatEvent::JOINED_ROOM?>,
	excludedUser: <?=ChatEvent::EXCLUDED_USER?>,
	wasExcluded: <?=ChatEvent::WAS_EXCLUDED?>,
	leftRoom: <?=ChatEvent::LEFT_ROOM?>
}
chatLocale = {
	Loading: '<?=__('Loading...')?>'
}
var mediaURL = {
	upload: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'upload'), true)?>',
	move: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'move'), true)?>.json',
	remove: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'delete'), true)?>.json',
	editorUpload: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'editorUpload'), true)?>.json'
};

//CloudAjax
var cloudURL = {
	panel: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'CloudAjax', 'action' => 'panel'), true) ?>',
	panelMove: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'CloudAjax', 'action' => 'panelMove'), true) ?>',
	addFolder: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'CloudAjax', 'action' => 'addFolder'), true) ?>.json',
	deActive: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'CloudAjax', 'action' => 'deActive'), true) ?>.json',
  delFolder: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'CloudAjax', 'action' => 'delFolder'), true) ?>.json',
	move: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'CloudAjax', 'action' => 'move'), true) ?>.json'
}

//DeviceAjax
var structURL = {
    deviceList: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'DeviceAjax', 'action' => 'deviceList'), true)?>',
    panel: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'DeviceAjax', 'action' => 'panel'), true)?>',
    distrib: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'DeviceAjax', 'action' => 'distrib'), true)?>.json',
    block: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'DeviceAjax', 'action' => 'block'), true)?>.json'
}

//DeviceAjax
var financeURL = {
	panel: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceAjax', 'action' => 'panel'), true) ?>',
	addProject: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceAjax', 'action' => 'addProject'), true) ?>',
	delProject: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceAjax', 'action' => 'delProject'), true) ?>',
	successDelProject: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceProject', 'action' => 'successDeleted'), true) ?>',

	listAccount: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceAccount', 'action' => 'getList'), true) ?>',
	addAccount: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceAccount', 'action' => 'addAccount'), true) ?>',
	editAccount: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceAccount', 'action' => 'editAccount'), true) ?>',
	delAccount: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceAccount', 'action' => 'delAccount'), true) ?>',

	listCategory: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceCategory', 'action' => 'getList'), true) ?>',
	addCategory: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceCategory', 'action' => 'addCategory'), true) ?>',
	editCategory: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceCategory', 'action' => 'editCategory'), true) ?>',
	delCategory: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceCategory', 'action' => 'delCategory'), true) ?>',
	expensesStatistic: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceCategory', 'action' => 'getStatistic'), true) ?>.json',

	addOperation: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceOperation', 'action' => 'addOperation'), true) ?>',
	editOperation: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceOperation', 'action' => 'editOperation'), true) ?>',
	delOperation: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceOperation', 'action' => 'delOperation'), true) ?>',
	operationChartData: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceOperation', 'action' => 'chartData'), true) ?>.json',
	operationShowMore: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceOperation', 'action' => 'showMore'), true) ?>',
	compareAccounts: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceOperation', 'action' => 'compareAccounts'), true) ?>',

	addGoal: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceGoal', 'action' => 'addGoal'), true) ?>',
	editGoal: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceGoal', 'action' => 'editGoal'), true) ?>',
	delGoal: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceGoal', 'action' => 'delGoal'), true) ?>',

	addBudget: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceBudget', 'action' => 'addBudget'), true) ?>',

	searchUser: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceShare', 'action' => 'searchUser'), true) ?>.json',
	sendInvite: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceShare', 'action' => 'sendInvite'), true) ?>',
	setFullAccess: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceShare', 'action' => 'setFullAccess'), true) ?>',
	unsetFullAccess: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceShare', 'action' => 'unsetFullAccess'), true) ?>',
	deleteUser: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'FinanceShare', 'action' => 'deleteUser'), true) ?>'
}

//GroupAjax
var groupURL = {
    panel: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'panel'), true)?>',
    getGallery: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'getGallery'), true)?>.json',
    addGalleryVideo: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'addGalleryVideo'), true)?>.json',
    delGalleryVideo: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'delGalleryVideo'), true)?>.json',
    join: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'join'), true)?>.json',
    invite: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'invite'), true)?>.json',

    vacancyResponse: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'vacancyResponse'), true)?>.json',
    vacancyApprove: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'vacancyApprove'), true)?>.json',
    vacancyDecline: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'vacancyDecline'), true)?>.json',
    vacancyResponses: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'vacancyResponses'), true)?>.json',

    inviteAccept: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'acceptInvite'), true)?>.json',
    inviteDecline: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'declineInvite'), true)?>.json',
    memberApprove: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'memberApprove'), true)?>.json',
    memberRemove: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'memberRemove'), true)?>.json',
    setDream: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'GroupAjax', 'action' => 'setDream'), true)?>.json'
}
var groupDef = {
    maxImages: <?=Configure::read('groupMaxImages')?>
}

//InvestAjax
var investURL = {
	panel: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'InvestAjax', 'action' => 'panel'), true) ?>',
	removeMedia: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'InvestProject', 'action' => 'removeMedia'), true) ?>',
	removeReward: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'InvestReward', 'action' => 'delete'), true) ?>'
}

//NoteAjax
var noteURL = {
	panel: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'NoteAjax', 'action' => 'panel'), true)?>',
	panelMove: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'NoteAjax', 'action' => 'panelMove'), true) ?>',
	editPanel: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'NoteAjax', 'action' => 'editPanel'), true) ?>',
	remove: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'NoteAjax', 'action' => 'delete'), true) ?>',
	addFolder: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'NoteAjax', 'action' => 'addFolder'), true)?>.json',
	delFolder: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'NoteAjax', 'action' => 'delFolder'), true)?>.json',
	move: '<?= $this->Html->url(array('plugin'=> false, 'controller' => 'NoteAjax', 'action' => 'move'), true) ?>.json'
}

//UserAjax
var profileURL = {
	panel: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'panel'), true)?>',
	timelineEvents: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'timelineEvents'), true)?>.json',
	updateEvent: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'updateEvent'), true)?>.json',
	deleteEvent: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'deleteEvent'), true)?>.json',

	acceptEvent: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'acceptEvent'), true)?>.json',
	declineEvent: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'declineEvent'), true)?>.json',
	changeEventCategory: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'changeEventCategory'), true)?>.json',

	skillList: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'skillList'), true)?>.json',
	eventOperation: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'getFinanceOperation'), true)?>.json',
	saveToCloud: '<?=$this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'saveToCloud'), true)?>.json'
}
//TODO: Maybe not needed anymore notifications for profile incompleteness
//var userLocale = {
//	notifyProfile: '<?=__('Enter your information in the User settings')?>'
//}
//var notifyProfile = <?php //echo ($notifyProfile) ? 'true' : 'false'?>;
