<script type="text/javascript">
    if ((/iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
        $('#sendChatSmile').popover({
            html : true,
            placement: 'top',
            class: 'smilesPopover',
            trigger: 'click',
            content: function() {
              return $('#popover_content_wrapper').html();
            }
        });

        $('body').on('touchstart', function(e) {
            if( !($(e.target).is('#sendChatSmile') || $(e.target).is('.smileSelect') || $(e.target).parents('#sendChatSmile').length > 0) ) {
                $('#sendChatSmile').popover('hide');
            }
        });
    } else if((navigator.userAgent.indexOf("Safari") > -1) || (navigator.userAgent.indexOf("Mozilla") > -1)) {
        $('#sendChatSmile').popover({
            html : true,
            placement: 'top',
            class: 'smilesPopover',
            trigger: 'click',
            content: function() {
              return $('#popover_content_wrapper').html();
            }
        });
    } else {
        $('#sendChatSmile').popover({
            html : true,
            placement: 'top',
            class: 'smilesPopover',
            trigger: 'focus',
            content: function() {
              return $('#popover_content_wrapper').html();
            }
        });
        console.log(navigator.userAgent);
    }

    $(document).off('click', '.submitMessage .smileSelect');
    $(document).on('click', '.submitMessage .smileSelect', function() {
        var textarea = $('textarea', $($(this).parents('.submitMessage')));
        text = textarea.val().length > 0 ? ' '+$(this).text() : ''+$(this).text();
        textarea.val( textarea.val() + text );

        $('#sendChatSmile').popover('hide');
        $('.inner #sendChatSmile').popover('hide');
    });
</script>

<!------------------------------------------------------------------------ COMMENTS ------------------------------------------------------------------------->
<?
    if($currUserID){
?>
<h3><?=__('Share your thoughts')?></h3>

<div id='article-<?= $id ?>' class="outer submitMessage">
    <?php echo $this->Avatar->user($currUser, array(
        'class' => 'pull-left',
        'style' => 'width:50px;',
        'size' => 'thumb100x100'
    ));?>
    <form class="message" id="ArticleEventCommentsForm">
        <div class="form-group">
            <div id="sendChatSmile" class="icon_enter btn btn-default"><span class="smile"></span></div>
            <label><?=__('Leave your comment')?></label>
            <textarea id="message-title" class="form-control" name="data[message]"></textarea>
            <input type="hidden" name="data[user_id]" value="<?=$currUserID?>">
            <button type="button" class="btn btn-default submitBtn"><span class="submitArrow"></span></button>
        </div>
        <input type="hidden" name="data[article_id]" value="<?=$id?>">
        <div class="clearfix"></div>
    </form>
</div>
<script>
//$(document).ready(function(){

    $('#article-<?= $id ?> .submitBtn').off('click');
    $('#article-<?= $id ?> .submitBtn').on('click', function(event) {
        Timeline.lEnableUpdate = false;

		//Adding parent Id to comments message if it message from timeline
		if ($('.commentsContainer').length) {
			var parent_id = $(this).closest('.commentsContainer').find('.commentLinkactive').data('comments-id');
			var inputParent = '<input type="hidden" name="data[parent_id]" value="'+parent_id+'">'
			$(this).closest('.form-group').prepend(inputParent);
		}

        var parent = $(this).parents('.submitMessage');
        if( $('textarea', parent).val().length < 1 ) {
            alert("Message can not be empty");
            Timeline.lEnableUpdate = true;
            return false;
        }
        var block = $(this);

        $.post( "/ArticleAjax/addComment.json", $(this).parents('form').serialize(), function(response) {

            $.post( "/ArticleAjax/comments/<?= $id ?>", $(this).parents('form').serialize(), function(response) {
                block.parents('#articleComments-<?= $id ?>').html(response);
                Timeline.lEnableUpdate = true;
            });
        });
    });
    $('textarea').autosize();
//});
</script>
<?
    }
?>
<!------------------------------------------------------------------------ COMMENTS ------------------------------------------------------------------------->

<?
    if ($aEvents) {
?>
<h3><?=__('Discussion')?></h3>
<div class="fixedLayout taskDiscussion">

<?
        //проверка для неавторизованных пользователей
        $commentAllow = true;
        if(!$currUserID){
            $commentAllow = false;
        }
        foreach($aEvents as $event) {
            $user = $aUsers[$event['ArticleEvent']['user_id']];
            if ($event) {
                $msg_id = $event['ArticleEvent']['msg_id'];
?>
    <div class="commentBlock">
        <?=$this->element('comment_element', array('user' => $user, 'commentAllow'=>$commentAllow ,'msg' => $messages[$msg_id]['message'], 'event' => $event['ArticleEvent'], 'child' => false, 'hasChilds' => isset($event['childs']) )) ?>
<?
                if(isset($event['childs'])) {
                    foreach($event['childs'] as $child) {
                        $user = $aUsers[$child['user_id']];
                        $msg_id = $child['msg_id'];
?>
        <?=$this->element('comment_element', array('user' => $user, 'commentAllow'=>$commentAllow ,'msg' => $messages[$msg_id]['message'], 'event' => $child, 'child' => true, 'hasChilds' => false)); ?>
<?
                    }
                }
?>
    </div>
<?
            }
        }
?>
</div>
<?
    }
?>
