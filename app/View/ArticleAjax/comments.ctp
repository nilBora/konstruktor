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
<? if($currUserID) : ?>
	<div class="commentsArticle" >
		<div class="titleComment">
			<?=__('Leave your comment')?>
		</div>
		<div class="imgComment">
			<?php echo $this->Avatar->user($currUser, array('size' => 'thumb100x100','class'=>'rounded')); ?>
		</div>
		<form action="" method="" class="submitMessage" id="ArticleInnerForm">
			<textarea id="innerMessageTitle" class="form-control textAreaComment" name="data[message]"></textarea>
			<input type="button" value="Отправить" id="article-<?php echo $id ?>" class="btnComment submitBtn">
			<input type="hidden" name="data[article_id]" value="<?php echo $id?>">
			<input type="hidden" name="data[user_id]" value="<?php echo $currUserID?>">
		</form>
		<div class="clear"></div>
	</div>
	<script>
		$('#article-<?php echo $id ?>.submitBtn').off('click');
		$('#article-<?php echo $id ?>.submitBtn').on('click', function(event) {
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
				$.post( "/ArticleAjax/comments/<?php echo  $id ?>", $(this).parents('form').serialize(), function(response) {
					block.parents('#articleComments-<?php echo  $id ?>').html(response);
					Timeline.lEnableUpdate = true;
				});
			});
		});
		$('textarea').autosize();
	</script>
<? endif; ?>
<!------------------------------------------------------------------------ COMMENTS ------------------------------------------------------------------------->
<? if ($aEvents) : ?>
	<div class="treeComments">
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
					<?php echo $this->element('comment_element', array('class'=>'upperComment','user' => $user, 'commentAllow'=>$commentAllow ,'msg' => $messages[$msg_id]['message'], 'event' => $event['ArticleEvent'], 'child' => false, 'hasChilds' => isset($event['childs']) )) ?>
					<?
					if(isset($event['childs'])) {
						foreach($event['childs'] as $child) {
							$user = $aUsers[$child['user_id']];
							$msg_id = $child['msg_id'];
							?>
							<?php echo $this->element('comment_element', array('class'=>'subComment','user' => $user, 'commentAllow'=>$commentAllow ,'msg' => $messages[$msg_id]['message'], 'event' => $child, 'child' => true, 'hasChilds' => false)); ?>
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
<? endif; ?>
