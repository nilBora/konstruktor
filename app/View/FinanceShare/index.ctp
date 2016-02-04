<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($group, 'Group.id')));
	$this->Html->addCrumb(__('Finances'), array('controller' => 'FinanceProject', 'action' => 'index/'.$id));
	$this->Html->addCrumb(__('Add user'), array('controller' => 'FinanceShare', 'action' => 'index/'.$id));
?>
<style type="text/css">
.selectedUser { background-color: #22b5ae;}
</style>
<?= $this->element('Finance/project_top') ?>
<div class="financeSettings">
	<?= $this->element('Finance/project_nav') ?>
	<div class="groupAccess clearfix">
<?
		foreach ($aUsers as $item) {
			switch ($item['User']['shareState']) {
				case FinanceShare::STATE_APPROVE : $class = 'access'; break;
				case FinanceShare::STATE_DECLINE : $class = 'denied'; break;
				default: $class = '';
			}
?>
		<div class="item clearfix <?= $class ?>">
			<?php echo $this->Avatar->user($currUser, array(
				'class' => 'ava',
				'onerror' => "this.src='/img/no-photo.jpg'",
				'size' => 'thumb50x50'
			)); ?>

			<div class="info">
				<span class="name"><?=$item['User']['full_name']?></span>
				<!--<span class="position">Веб дизайнер</span>-->
			</div>
			<div class="buttonsControls">
				<a href="<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'index', $id, '?' =>array('user' => $item['User']['id']))) ?>">
					<div class="accept <? if ($user == $item['User']['id']) {?>selectedUser<? } ?>"><span class="glyphicons wrench"></span></div>
				</a>
				<a href="<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'deleteUser', $id, $item['User']['id'])) ?>">
					<div class="remove"><span class="glyphicons bin"></span></div>
				</a>
			</div>
		</div>
		<? } ?>
	</div>

	<div class="row emailAndUser fixedLayout">
		<div class="col-sm-7">
			<form id="finance-share-searchUser">
				<div class="form-group">
					<label><?=__('User E-mail')?></label>
					<input type="text" value="" placeholder="" class="form-control">
					<button type="submit" class="btn btn-default textIconBtn"><span class="glyphicons search"></span><?=__('Search')?></button>
				</div>
			</form>
		</div>
		<div class="col-sm-5 groupAccess" id="finance-share-searchResult"></div>
	</div>

	<div class="accessSettings <? if (!$user) { ?>hide<? } ?>" id="finance-share-accessSettings">
		<?= $this->element('Finance/share_settings') ?>
	</div>
</div>

<!-- Templates -->
<script type="text/x-tmpl" id="tmpl-finance-share-searchResult">
<div class="item clearfix finance-share-sendInvite" data-id="{%=o.id%}" >
	<img alt="" src="{%=o.urlImg%}" class="ava blockLine" onerror="this.src='/img/no-photo.jpg'">
	<div class="info">
		<span class="name">{%=o.fullName%}</span>
		<span class="position"></span>
	</div>
	<div class="buttonsControls">
		<a href="javascript:void(0)" id="finance-share-searchResult-settings">
			<div class="accept"><span class="glyphicons ok_2"></span></div>
		</a>
		<a href="javascript:void(0)" id="finance-share-searchResult-delete">
			<div class="remove"><span class="glyphicons bin"></span></div>
		</a>
	</div>
</div>
</script>

<script type="text/x-tmpl" id="tmpl-finance-share-ajaxLoader">
<img src="/img/ajax_loader.gif" alt="">
</script>

<script type="text/x-tmpl" id="tmpl-finance-share-emptyResult">
<?= __('User not found') ?>
</script>
<!-- /Templates -->

<script type="text/javascript">
$(document).ready(function () {
	$('select, .checkboxStyle').styler();
	// search user
	$('#finance-share-searchUser').on('submit', function () {
		var email = $(this).find('input').val();
		if (!email) {
			return false;
		}
		$('#finance-share-searchResult').html(tmpl('tmpl-finance-share-ajaxLoader'));
		$.post(financeURL.searchUser, {email: email}, function (response) {
			if (!response.user.User) {
				$('#finance-share-searchResult').html(tmpl('tmpl-finance-share-emptyResult'));
			} else {
				var user = response.user.User;
				var media = response.user.UserMedia;
				//
				var userId = user.id;
				var projectId = <?=$id?>;
				// show search result
				$('#finance-share-searchResult').html(tmpl('tmpl-finance-share-searchResult', {
					id: user.id,
					fullName: user.full_name,
					urlImg: media.url_img
				}));
				// on delete search result
				$('#finance-share-searchResult-delete').on('click', function () {
					$('#finance-share-searchResult').empty();
					$('#finance-share-accessSettings').addClass('hide');
					$.post(financeURL.deleteUser + '/' + projectId + '/' + userId);
				});

				// show settings for search result
				$('#finance-share-accessSettings').load('/FinanceShare/settings/'+projectId+'?user='+userId);
				$('#finance-share-accessSettings').removeClass('hide');

				// send invite and settings
				$('#finance-share-searchResult-settings').on('click', function () {
					$('#finance-share-form').submit();
				});
			}
		});
		return false;
	});
});
</script>
