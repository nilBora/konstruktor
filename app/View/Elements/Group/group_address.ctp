<div class="group-address-info">
	<input type="hidden" name="data[GroupAddress][<?=$i?>][id]" value="<?=Hash::get($groupAddress, 'id')?>">
	<input type="hidden" name="data[GroupAddress][<?=$i?>][group_id]" value="<?=$group_id?>">
    <fieldset>
	    <label for="group-create-4"><?=__('Headquaters')?></label>
	    <div class="input-boxing clearfix">
	        <span class="icon-input">
	            <span class="glyphicons direction"></span>
	        </span>
	        <textarea class="textarea-auto animated icon-left-width" id="group-create-4" name="data[GroupAddress][<?=$i?>][address]" placeholder="<?=__('Headquaters')?>..."><?=Hash::get($groupAddress, 'address')?></textarea>
	    </div>
	</fieldset>
	<fieldset>
	    <label for="group-create-5"><?=__('Phone')?></label>
	    <div class="input-boxing clearfix">
	        <span class="icon-input">
	            <span class="glyphicons earphone"></span>
	        </span>
	        <input class="icon-left-width" id="group-create-5" type="text" name="data[GroupAddress][<?=$i?>][phone]" value="<?=Hash::get($groupAddress, 'phone')?>" placeholder="<?=__('Phone')?>..." />
	    </div>
	</fieldset>
	<fieldset>
	    <label for="group-create-7"><?=__('Site URL and email')?></label>
	    <div class="input-boxing clearfix">
	        <span class="icon-input">
	            <span class="glyphicons globe_af"></span>
	        </span>
	        <input class="icon-left-width" id="group-create-7" type="text" name="data[GroupAddress][<?=$i?>][url]" value="<?=Hash::get($groupAddress, 'url')?>" placeholder="<?=__('http://yoursite.com')?>..." />
	    </div>
	    <div class="input-boxing clearfix">
	        <span class="icon-input">
	            <span class="glyphicons message_empty"></span>
	        </span>
	        <input class="icon-left-width" id="group-create-8" type="text" name="data[GroupAddress][<?=$i?>][email]" value="<?=Hash::get($groupAddress, 'email')?>" placeholder="<?=__('Email')?>..."/>
	    </div>
	</fieldset>
	<!--div class="group-block-remove">
	    <span class="glyphicons circle_remove"> <i><?=__('Remove address')?></i></span>
	</div-->
</div>