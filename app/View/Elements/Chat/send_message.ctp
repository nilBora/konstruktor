<div class="sendForm clearfix">
	<?php echo $this->Avatar->user($currUser, array(
		'class' => 'ava',
		'size' => 'thumb100x100'
	)); ?>
    <div class="text"><?=__('Send message')?></div>

    <div class="leftBtns">
        <input id="chatFileChoose" type="file" class="fileuploader test attachFile" name="files[]" data-object_type="Chat" multiple/>
        <div id="sendChatSmile" class="icon_enter btn btn-default">
            <span class="smile"></span>
        </div>
    </div>

    <textarea></textarea>
    <div id="sendChatMsg" class="icon_enter btn btn-default" style="float: right;">
        <span class="submitArrow"></span>
    </div>
    <div class="clearfix"></div>
    <div class="formBottom clearfix">
        <div id="processRequest" style="padding-top: 4px; padding-left: 40px; display: none;"><img src="/img/ajax_loader.gif" alt="" style="height: 24px"/> <span style="float: none;"><?=__('Request is being processed')?></span></div>
        <div id="processFile" style="padding-top: 4px; padding-left: 40px; display: none;">
            <img src="/img/ajax_loader.gif" alt="" style="height: 24px"/>
            <span style="float: none;"><?=__('Processing file(s)...')?></span>
        </div>
    </div>
    <span id="chatUploadFiles"></span>
</div>
