<?php if ($aEvents) { ?>
<div class="fixedLayout taskDiscussion">
<?php
    //проверка для неавторизованных пользователей
	$commentAllow = true;
	if(!$currUserID){
		$commentAllow = false;
	}
	//TODO: limit for last 5 comments
	foreach($aEvents as $event) {
		$user = $aUsers[$event['ArticleEvent']['user_id']];
		if ($event) {
			$msg_id = $event['ArticleEvent']['msg_id'];
?>
	<div class="commentBlock">
		<?= $this->element('comment_element', array('user' => $user, 'commentAllow'=>$commentAllow ,'msg' => $messages[$msg_id]['message'], 'event' => $event['ArticleEvent'], 'child' => false, 'hasChilds' => count($event['ArticleChildEvent']) )) ?>
		<?php
			if(count($event['ArticleChildEvent'])) {
				foreach($event['ArticleChildEvent'] as $child) {
					$user = $aUsers[$child['user_id']];
					$msg_id = $child['msg_id'];
		?>
		<?= $this->element('comment_element', array('user' => $user, 'commentAllow'=>$commentAllow ,'msg' => $messages[$msg_id]['message'], 'event' => $child, 'child' => true, 'hasChilds' => false)); ?>
		<?php
				}
			}
		?>
	</div>
<?php
		}
	}
?>
</div>
<?php } ?>
<!-- Add comments block -->
<?php if($currUserID){ ?>
<div id="article-<?= $id ?>" class="outer submitMessage">
	<?php echo $this->Avatar->user($currUser, array('size' => 'thumb100x100')); ?>
	<form class="message" id="ArticleEventCommentsForm">
		<div class="form-group">
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
	$('.reply.answer').off('click');
	$('.reply.answer').on('click', function(e){
		e.preventDefault();
		if($(this).closest('.item').children('.submitMessage').length < 1){
			parent_id = $(this).data('parent_id');
			commentForm = $(this).closest('.commentsContainer').children('.submitMessage').clone(true);
			$('<input>').attr({
			    type: 'hidden',
			    name: 'data[parent_id]',
				value: parent_id
			}).appendTo($(commentForm).children('form'));
			$(this).closest('.item').append(commentForm);
		}
		e.stopImmediatePropagation();
	});
	$('#article-<?= $id ?> .submitBtn').off('click');
	$('#article-<?= $id ?> .submitBtn').on('click', function(event) {
		Timeline.lEnableUpdate = false;
		var parent = $(this).parents('.submitMessage');
		if( $('textarea', parent).val().length < 1 ) {
			alert("Message can not be empty");
			Timeline.lEnableUpdate = true;
			return false;
		}
		$.post( "/ArticleAjax/addComment.json", $(this).parents('form').serialize(), function(response) {
			$.post( "/ArticleAjax/timeline_comments/<?= $id ?>", $(this).parents('form').serialize(), function(response) {
				$('#articleComments-<?= $id ?>').html(response);
				Timeline.lEnableUpdate = true;
			});
		});
	});
	$('textarea').autosize();
//});
</script>
<?php } ?>
<!-- Add comments block end-->
