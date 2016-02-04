<div class="group-achieve-info">
	<input type="hidden" name="data[GroupAchievement][<?=$i?>][id]" value="<?=Hash::get($groupAchievement, 'id')?>">
	<input type="hidden" name="data[GroupAchievement][<?=$i?>][group_id]" value="<?=$group_id?>">
    <fieldset>
        <label for="group-create-9"><?=__('Achievement')?></label>
        <div class="input-boxing clearfix">
            <textarea class="textarea-auto animated icon-left-width" id="group-create-9" name="data[GroupAchievement][<?=$i?>][title]" placeholder="<?=__('Achievement')?>..."><?=Hash::get($groupAchievement, 'title')?></textarea>
        </div>
    </fieldset>
    <fieldset>
        <label for="group-create-10"><?=__('Link to verified accomplishments')?></label>
        <div class="input-boxing clearfix">
            <input id="group-create-8" type="text" name="data[GroupAchievement][<?=$i?>][url]" value="<?=Hash::get($groupAchievement, 'url')?>" placeholder="http://yoursite.com..."/>
        </div>
    </fieldset>
    <!--div class="group-block-remove">
        <span class="glyphicons circle_remove"> <i><?=__('Remove achievement')?></i></span>
    </div-->
</div>