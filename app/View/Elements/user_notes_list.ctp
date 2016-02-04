<ul class="group-list" <? if($id) { ?>style="padding-top: 190px"<? } ?>>
<? 	
	foreach($aNotes as $note) {
		if($note['Note']['is_folder']) { 
?>
            <!-- Folder -->
            <li class="simple-list-item" data-type="folder" data-id="<?=$note['Note']['id']?>">
                <a href="javascript:void(0)">
                    <div class="user-list-item clearfix">
                        <span class="glyphicons folder_closed"></span>
                        <div class="articlesInfo">
                            <div class="title"><?=$note['Note']['title']?></div>
                            <div class="size"><?=$note['Note']['fileCount']?> <?=__('file(s)')?></div>
                        </div>
                    </div>
                </a>
            </li>
<? 
		} else { 
			if($note['Note']['type'] == 'text') {
?>
            <!-- Note -->
            <li class="simple-list-item" data-id="<?=$note['Note']['id'] ?>" data-type="text" data-url="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'view', $note['Note']['id']))?>">
				<a href="javascript:void(0)">
                    <div class="user-list-item clearfix">
						<span class="filetype doc"></span>
                        <div class="articlesInfo">
							<div class="title"><?=$note['Note']['title']?></div>
							<!--div class="size">2,1 МБ</div-->
                        </div>
                    </div>
                </a>
            </li>
<? 
			} else if($note['Note']['type'] == 'table') {
?>			
            <!-- Table -->
            <li class="simple-list-item" data-id="<?=$note['Note']['id'] ?>" data-type="table" data-url="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'spreadsheet', $note['Note']['id']))?>">
				<a href="javascript:void(0)">
                    <div class="user-list-item clearfix">
						<span class="filetype xls"></span>
                        <div class="articlesInfo">
							<div class="title"><?=$note['Note']['title']?></div>
							<!--div class="size">2,1 МБ</div-->
                        </div>
                    </div>
                </a>
            </li>
<?				
			}
		} 
	} 
?>
</ul>