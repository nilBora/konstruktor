<ul class="group-list">
	<? foreach ($aFinanceProjects as $financeProject) { ?>
		<li class="simple-list-item">
			<a
				<? if (in_array($financeProject['FinanceProject']['id'], $aInvites)) {?>
				href="javascript:void(0)"
			    <? } else { ?>
				href="<?= $this->Html->url(array('controller' => 'FinanceProject', 'action' => 'index', $financeProject['FinanceProject']['id'])) ?>"
				<? } ?>
			>
				<div class="user-list-item clearfix">
					<span class="glyphicons folder_closed">
						<? if ($financeProject['FinanceProject']['user_id'] !== $currUserID) { ?>
						<span class="glyphicons link" style="position: absolute; font-size: 16px; color:#fff; right: 18px; top: 10px"></span>
						<? } ?>
					</span>
					<div class="articlesInfo">
						<div class="title"><?= $financeProject['FinanceProject']['name'] ?></div>
						<!--<div class="size">$ 100 P 300</div>-->
					</div>
				</div>
				<? if (in_array($financeProject['FinanceProject']['id'], $aInvites)) {?>
				<div class="group-enter-btn clearfix">
					<a class="btn btn-default finance-share-inviteAccept"
						href="<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'acceptInvite', $financeProject['FinanceProject']['id'])) ?>"
						data-link="<?= $this->Html->url(array('controller' => 'FinanceProject', 'action' => 'index', $financeProject['FinanceProject']['id'])) ?>"
					><?=__('Accept')?></a>
					<a class="btn btn-default finance-share-inviteDecline" href="<?= $this->Html->url(array('controller' => 'FinanceShare', 'action' => 'declineInvite', $financeProject['FinanceProject']['id'])) ?>"><?=__('Decline')?></a>
				</div>
				<? } ?>
			</a>
		</li>
	<? } ?>
</ul>

<script>
$(document).ready(function(){
	$('.finance-share-inviteAccept').on('click', function () {
		var link = $(this).data('link');
		$.post($(this).attr('href'), function (response) {
			if (response) {
				alert(response);
				location.reload(false);
				return;
			}
			location.href = link;
		});
		return false;
	});
	$('.finance-share-inviteDecline').on('click', function () {
		$.post($(this).attr('href'), function (response) {
			if (response) {
				alert(response);
			}
			location.reload(false);
		});
		return false;
	});
});
</script>