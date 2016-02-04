<?
$isKonstructorActive = ($this->request->controller == 'Group' && Hash::get($this->request->pass, '0') == Configure::read('Konstructor.groupID')) ? ' active' : '';

$isChatActive = ($this->request->controller == 'Chat') ? ' class="active"' : '';
$isGroupActive = ($this->request->controller == 'Group' && !$isKonstructorActive) ? ' class="active"' : '';
$isDeviceActive = ($this->request->controller == 'Device') ? ' class="active"' : '';
$isUserActive = ($this->request->controller == 'User' && $this->request->action == 'edit') ? ' class="active"' : '';
$isNotesActive = ($this->request->controller == 'Note') ? ' class="active"' : '';
$isArticleActive = ($this->request->controller == 'Article') ? ' class="active"' : '';
$isCloudActive = ($this->request->controller == 'Cloud') ? ' class="active"' : '';
$isFinanceActive = (substr($this->request->controller, 0, 7) == 'Finance') ? ' class="active"' : '';
$isInvestActive = (substr($this->request->controller, 0, 6) == 'Invest') ? ' class="active"' : '';
?>
<div class="main-panel-list">
    <ul>
        <!--li><a href="javascript:void(0)"><span class="glyphicons search searchPanel"></span><span class="text"><?=__('Search')?></span></a></li-->
        <!--li><a href="/Mytime"><span class="glyphicons clock"></span><span class="text"><?=__('Timeline')?></span></a></li-->
        <div class="mainButtons">
            <li<?=$isChatActive?> data-panel="chatPanel">
                <a href="javascript:void(0)">
                    <span class="glyphicons chat chatPanel"></span>
                    <div id="chatTotalUnread" class="count"></div>
                    <span class="text"><?=__('Chat')?></span>
                </a>
           </li>

            <li<?=$isGroupActive?> data-panel="groupPanel">
                <a href="javascript:void(0)">
                    <span class="glyphicons group groupPanel"></span>
                    <div id="groupInviteCount" class="count"></div>
                    <span class="text"><?=__('Groups')?></span>
                </a>
            </li>

            <li<?=$isArticleActive?> data-panel="notesPanel">
                <a href="javascript:void(0)">
                    <span class="glyphicons notes notesPanel"></span>
                    <div id="newsCount" class="count"></div>
                    <span class="text"><?=__('Articles')?></span>
                </a>
            </li>

            <li<?=$isInvestActive?> data-panel="investPanel" style="display: none;">
                <a href="javascript:void(0)">
                    <span class="glyphicons briefcase investPanel"></span>
                    <span class="text"><?=__('Investments')?></span>
                </a>
            </li>

            <li<?=$isFinanceActive?> data-panel="financePanel" style="display: none;">
                <a href="javascript:void(0)">
                    <span class="glyphicons credit_card financePanel"></span>
                    <span class="text"><?=__('Finances')?></span>
                </a>
            </li>

            <li<?=$isCloudActive?> data-panel="cloudPanel">
                <a href="javascript:void(0)">
                    <span class="glyphicons cloud cloudPanel"></span>
                    <span class="text"><?=__('Cloud')?></span>
                </a>
            </li>

            <li<?=$isDeviceActive?> data-panel="ipadPanel" style="display: none;">
                <a href="javascript:void(0)">
                    <span class="glyphicons ipad ipadPanel"></span>
                    <span class="text"><?=__('Devices')?></span>
                </a>
            </li>

            <li data-panel="calendarPanel">
                <a href="/Timeline/planet">
                    <span class="glyphicons globe"></span>
                    <span class="text"><?=__('Planet')?></span>
                </a>
            </li>

            <!--li<?=$isUserActive?>>
                <a id="settingsNotification" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'edit'))?>">
                    <span class="glyphicons settings"></span>
                    <span class="text"><?=__('Settings')?></span>
                </a>
            </li-->
        </div>

        <div class="serviceButtons ">
            <li>
                <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'logout'))?>" onclick="return confirm('<?=__("Are you sure ?")?>')">
                    <span class="glyphicons exit"></span>
                    <span class="text"><?=__('Exit')?></span>
                </a>
            </li>

            <li class="logo-li<?=$isKonstructorActive?>">
                <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', Configure::read('Konstructor.groupID')))?>">
                    <span class="logo-icon"></span>
                    <span class="text">Konstruktor</span>
                </a>
            </li>
        </div>

    </ul>
</div>
