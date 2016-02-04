<div class="fixedLayout">
	<div class="row groupViewInfo">
		<div class="col-sm-8">
			<div class="thumb">
				<?php echo $this->Avatar->group($group, array(
					'size' => 'thumb200x200'
				)); ?>
			</div>
			<h1><?=$group['Group']['title']?></h1>
		</div>
		<div class="col-sm-4">
			<div class="controlButtons">
	<?
		if (!$joined && !($isGroupAdmin || $isGroupResponsible)) {
	?>
				<a id="joinGroup" class="btn btn-default" href="javascript:void(0)" onclick="Group.join(<?=$group['Group']['id']?>, <?=$currUserID?>)">
					<?=__('Join this group')?>
					<span class="joined hide"><?=__('Your invitation was sent to the group administrator')?></span>
				</a>
	<?
		}
	?>

	<?php ?>


						<!--<button data-toggle="dropdown" id="dropdownMenu1" type="button" class="btn btn-default dropdown-toggle">
							Отправить сообщение
						</button>
						<div aria-labelledby="dropdownMenu1" role="menu" class="dropdown-menu">
							<div class="dropdown-wrap">
								<div class="dropdown-close">
									<span class="glyphicons circle_remove"></span>
								</div>
								<div class="dropdown-body inner-content">
									<div class="comments-box-send">
										<img alt="" src="img/temp/smallava1.jpg">
										<div class="comments-box-send-info">
											Оставьте свой комментарий
										</div>
										<form>
											<div class="comments-box-send-form">
												<div class="comments-box-textarea">
													<textarea rows="3"></textarea>
												</div>
												<div class="comments-box-submit">
													<button class="btn btn-default"><span class="glyphicons send"></span></button>
												</div>
											</div>
										</form>
										<div class="comments-box-send-info bottom-info">
											<div class="comments-box-bottom-buttons">
												<a class="btn btn-default" href="#">
													<span class="glyphicons paperclip"></span>
												</a>
												<a class="btn btn-default" href="#">
													<span class="glyphicons facetime_video"></span>
												</a>

											</div>
											Для отправки сообщения нажмите Enter
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>-->

	<?
		if ($isGroupAdmin || $isGroupResponsible) {
	?>
				<a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'groupArticles', $group['Group']['id']))?>">
					<?=__('Articles')?>
				</a>
	<?
		}
		if ($isGroupMember) {
	?>
				<a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'members', $group['Group']['id']))?>">
					<?=__('Members')?>
				</a>
	<?
		}
		if ($isGroupAdmin || $isGroupResponsible) {
	?>
				<a class="btn btn-default smallBtn" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'edit', $group['Group']['id']))?>">
					<span class="glyphicon glyphicon-wrench glyphicons wrench"></span>
				</a>
	<?
		}
	?>

			</div>
		</div>
	</div>
	<? if (!$group['GroupAddress']) {

		echo __('No addresses yet');

		} else {
	?>
		<?php foreach($group['GroupAddress'] as $id=>$address){
			$aAddress[$address['country']][] = $address;
		}?>
		<?php
			$i=0;
			foreach($aAddress as $country=>$address):?>
		<div class="groupOffices clearfix">
						<div class="country">
							<img class="addressFlag" src="/img/flags/48/<?=$country;?>.png" /><?=$countryNames[$country]?>
							<?php if($i==0):?>
								<!--<div class="headOffice"><?=__('Head Office')?></div>-->
							<?php endif;?>
						</div>
						<div class="office">
					<?php foreach($address as $id=>$office):
					?>

							<div class="item">
	<?if($office['head_office']){?>
								<div class="headOffice"><?=__('Head Office')?></div>
	<?}?>
								<?php if($office['zip_code']) { ?> <div><?=$office['zip_code']?></div> <?php } ?>
								<?php if($office['address']) { ?> <div class="address"><?=$office['address']?></div> <?php } ?>
								<?php if($office['phone']) { ?> <div class="phone"><?=$office['phone']?></div> <?php } ?>
								<?php if($office['fax']) { ?> <div class="fax"><?=__('Fax')?>: <?=$office['fax']?></div> <?php } ?>
								<?php if(strlen($office['url']) > 8) { ?> <a href="<?=$office['url']?>" class="underlink"><?=$office['url']?></a><br> <?php } ?>
								<?php if($office['email']) { ?><div><a href="mailto: <?=$office['email']?>" class="underlink"><?=$office['email']?></a></div> <?php } ?>
							</div>
				   <?php endforeach;?>
						</div>
					</div>
		<?php $i++;
		endforeach;?>
	<?php }?>
</div>
