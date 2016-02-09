<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), array('controller' => 'User', 'action' => 'view/'.$currUserID));
	$this->Html->addCrumb(__('Investment management'), array('controller' => 'InvestProject', 'action' => 'listAllSponsors'));

$group = $groupHeader;
//$this->element('Invest/project_top') ?>

<span></span>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<style>
	.container-fluid>.groupViewInfo{
		display: none;
	}
	.sponsors-list-page .header{

	}

	ul.edit-bar li{
	  display: inline-block;
	  vertical-align: middle;
	  color: #818181;
	  font-size: 11px;
	}
	.sponsors-list-page .header .strong{
		font-weight: bold;
	}
	.groupViewInfo .table-striped>thead>tr{
		background-color: #eeeeee;
	}
	.groupViewInfo .table-striped>tbody tr{

	}
	.InvestProject-totals .form-group{
		border: none;
		margin-bottom: 10px;
	}
	.InvestProject-totals .form-group input{
	    font-size: 12px;
	    border: 1px solid #ccc;
	    border-radius: 5px;
	    padding: 0 5px;
	}

	.needSum .jq-selectbox{
	    width: 50px;
	    float: right;
	    position: absolute !important;
	    top: 2px;
	    right: 16px;
	}
	.needSum .jq-selectbox .jq-selectbox__select{
	    border: none;
	    height: 30px;
	}
	.userArticles .edit-bar li, .userArticles .edit-bar li{
		display: inline-block;
		vertical-align: middle;
		color: #818181;
		font-size: 11px;
	}

	.groupViewInfo .table-striped >tbody tr:nth-child(even){
		background-color: #f9f9f9;
	}

	.table-invest {
		border: 1px solid #dcdcdc;
		width: 100% !important;
		font-family: 'Open Sans';
	}

	.table-invest tr,
	.table-invest th,
	.table-invest td {
		font-family: 'Open Sans';
	}

	.table-invest .thumb {
		width: 46px;
	}
	.table-invest td {
		border: 1px solid #dcdcdc;
	}
	.table-striped>tbody>tr:nth-child(odd) {
	    background-color: #FFF;
	}
	.groupViewInfo .table>thead>tr>th,
	.groupViewInfo .table>tbody>tr>th,
	.groupViewInfo .table>tfoot>tr>th,
	.groupViewInfo .table>thead>tr>td,
	.groupViewInfo .table>tbody>tr>td,
	.groupViewInfo	 .table>tfoot>tr>td {
	    padding: 8px;
	    line-height: 1.42857143;
	    vertical-align: top;
	    border: 1px solid #ddd;
	    color: #999;
	}
	.refound {
		opacity: 0.8;
	}
	.refound:hover {
		opacity: 1;
	}
	.refound, 
	.refound:hover{
		color: #999;
		text-decoration: none;
		line-height: 24px;
	}
	.refound:before{
		content: '';
	    font-size: 24px;
	    line-height: 24px;
	    position: absolute;
	    width: 24px;
	    height: 24px;
	    left: -34px;
	    background-image: url(/img/del.png);
	    background-repeat: no-repeat;
	    background-position: 50% 50%;
	}
	.all_sponsors_list{
		margin-bottom: 20px;
	}
	.all_sponsors_list .active{
		color: #12d09b;
	}
	.my_projects .table-striped>thead>tr ,.my_invests_projects  .table-striped>thead>tr {
    background-color: #eeeeee;
}
.my_projects .table>thead>tr>th,
.my_invests_projects .table>tbody>tr>th,
.my_projects .table>thead>tr>td,
.my_invests_projects .table>tbody>tr>td{
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border: 1px solid #ddd;
    color: #999;
	}
	.my_projects .table-striped >tbody tr:nth-child(even) , .my_invests_projects .table-striped >tbody tr:nth-child(even){
    background-color: #f9f9f9;
}
</style>

<div class="row taskViewTitle fixedLayout" style="">
<style>
.groupViewInfo{
	border-bottom: 3px solid #eee;
	margin-bottom: 5px;
	padding-bottom: 40px;
}
.userArticles .edit-bar li{
	display: inline-block;
	vertical-align: middle;
	color: #818181;
	font-size: 11px;
}
.InvestProject-totals .form-group{
	border: none;
	margin-bottom: 10px;
}
.InvestProject-totals .form-group input{
    font-size: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 0 5px;
}

.needSum .jq-selectbox{
    width: 50px;
    float: right;
    position: absolute !important;
    top: 2px;
    right: 16px;
}
.needSum .jq-selectbox .jq-selectbox__select{
    border: none;
    height: 30px;
}
.userArticles .edit-bar li, .userArticles .edit-bar li{
	display: inline-block;
	vertical-align: middle;
	color: #818181;
	font-size: 11px;
}

.fixedLayout{
	max-width: 1170px;
	margin: 20px auto 40px;
}
#sorted-styler{
	float: right;
}
.edit-bar a{
	color: #999;
	text-decoration: none;
	font-size: 19px;
	padding: 5px;

}
.edit-bar .glyphicons:before{
	position: relative;
}
.edit-bar .glyphicons.eye_open:before{
	top: 	0px;
}
.edit-bar .glyphicons.edit:before{
	top: 0px;
}
.groupPackHead .arrow, .groupFilterPackHead .arrow  {
    -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    transform: rotate(90deg);
}
.groupPackHead.collapsed .arrow, .groupFilterPackHead.collapsed .arrow {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
}
</style>
<div class="col-sm-5 col-sm-push-7 controlButtons" style="margin-top: 10px;">
		<div style="display: inline-block;margin-right: 10px; font-size: 18px;">
			<?=__('Sort');?>
		</div>
		<?php
		$options = array('date' => __('Create date'));
		echo $this->Form->select('sorted', $options);
		?>
		<?php //echo $this->Html->link(__('Create project'), 'javascript:add()', array('class' => 'btn btn-default pull-left'))?>
</div>
	<div class="col-sm-7 col-sm-pull-5">
		<h1 style="float: left; margin: 0;font-size: 24px; text-transform: uppercase; color: #444444;"><?= __('Investment management') ?></h1>
	</div>
</div>
<!--div class="">
	<div class="row groupViewInfo fixedLayout">
	    <div class="col-sm-8">
	        <div class="thumb">
				<?php echo $this->Avatar->group($group, array(
					'size' => 'thumb200x200'
				)); ?>
			</div>
	        <h1><?=Hash::get($group, 'Group.title')?></h1>
	    </div>
	    <div class="col-sm-4">
	        <?php // $this->element('Invest/project_nav')?>
	    </div>
	</div>
</div-->
<div class="">
	<div class="row all_sponsors_list" style="font-size: 16px; color: #999;">
	    <div class="col-sm-2  investors_list active" style="cursor: pointer;">
	        <?=__('Investors list')?>
	    </div>
	    <div class="col-sm-2 my_investments" style="cursor: pointer;">
	        <?=__('My investments')?>
	    </div>
	</div>
</div>
<div class="row my_projects ">
	<?php
		$i = 0;
		foreach ($my_projects as $project): ?>
				<?php
						$results = Hash::extract($project, '{n}.InvestProject');
						$results = @array_unique($results);
						$pr = $results['0'];
						$count_sponsors = 0;
					//	var_dump($results);
				?>
				<div class="col-sm-8 project" style="margin: 25px 0;">
					<div class='row'>
						<div class="col-sm-8 groupFilterPackHead <?php echo ($i == 0)?'':'collapsed'?>" style="text-transform: uppercase; color: #666666; font-weight: bold; font-size: 16px;" data-num="<?=$i?>"> <?=$pr['name'];?>
							<span class="flow-right fa fa-caret-right arrow" style="margin-right: 10px;"></span>
						</div>
					</div>
					<!--div class="groupFilterPackHead collapsed" data-num="<?=$i?>"> <?=$pr['name'];?> <span class="flow-right fa fa-angle-right arrow" style="margin-right: 10px;"></span></div-->
					<div class=" row section-content" id="groupFilterPack-<?=$i?>" style="display: <?php echo ($i == 0)?'block':'none';?>; margin-top: 15px;">

							<div class=" col-sm-12">
								<table class="table table-striped table-invest">
									<thead>
										<tr div="col-sm-12">
											<th div="col-sm-4" style="padding: 20px 10px; text-align: center;  color: #666; font-size: 16px;"><?= __('Sponsor') ?></th>
											<th div="col-sm-2" style="padding: 20px 10px; text-align: center; color: #666; font-size: 16px;"><?= __('Date and time') ?></th>
											<!--th><?= __('Reward') ?></th-->
											<th div="col-sm-3" style="padding: 20px 10px; text-align: center; color: #666; font-size: 16px;"><?= __('Amount') ?></th>

											<th div="col-sm-3" style="padding: 20px 10px; text-align: center; color: #666; font-size: 16px;"><?= __('Status') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($project as $reward): ?>
											<?php foreach ($reward['Sponsors'] as $sponsor): ?>
												<tr>
													<td>
														<div class="thumb" style="padding-left: 10px; display: table-cell; vertical-align: top;">
															<?php echo $this->Avatar->user($users[$sponsor['user_id']], array('size' => 'thumb50x50', 'class' => 'rounded')); ?>
														</div>

														<div style="display: table-cell; padding-left: 20px;color: #666666; font-size: 14px; font-weight: bold; vertical-align: middle;">
															<?php echo $users[$sponsor['user_id']]['User']['full_name']?>
														</div>
													</td>
													<td style="    vertical-align: middle; text-align: center; font-size: 14px; color: #999;"><?php echo $sponsor['created'] ?></td>
													<!--td><?php echo $sponsor['InvestReward']['name'] ?></td-->
													<td style="    vertical-align: middle; text-align: center; color: #12d09b; font-size: 18px;">$<?php echo $sponsor['amount'] ?></td>

													<td style="position: relative; text-align: center; vertical-align: middle; font-size: 14px; color: #999;">
														<div class="">
															<?=__('Open');?>
														</div>
														<?if($currUserID == $sponsor['user_id'] || $currUserID == $pr['user_id'] ):?>
														<div class="" style="position: absolute; top: 50%; right: -110px; margin: -12px 0 0;">
															<a class="refound"  href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'refundReward', $sponsor['id'] ))?>">
																<?=__('Refund')?>
															</a>

														</div>

													<?php endif;?>
													</td>
												</tr>
												<?php $count_sponsors++;?>
											<?php endforeach; ?>
										<?php endforeach; ?>


										<tr>
											<td>
												<div class="thumb" style="padding-left: 10px; display: table-cell; vertical-align: top;">
													<img src="/img/no-photo.jpg?1451486170" class="thumb avatar rounded " alt="Fafaf Afsa">
												</div>

												<div style="display: table-cell; padding-left: 20px;color: #666666; font-size: 14px; font-weight: bold; vertical-align: middle;">Fafaf Afsa	</div>
											</td>

											<td style="vertical-align: middle; text-align: center; font-size: 14px; color: #999;">2016-01-21 14:25:40</td>

											<td style="vertical-align: middle; text-align: center; color: #12d09b; font-size: 18px;">$500.00</td>

											<td style="position: relative; text-align: center; vertical-align: middle; font-size: 14px; color: #999;">
												<div class="">Open</div>

												<div class="" style="position: absolute; top: 50%; right: -110px; margin: -12px 0 0;">
													<a class="refound" href="/InvestProject/refundReward/2">Возврат</a>
												</div>
											</td>
										</tr>

										<tr>
											<td>
												<div class="thumb" style="padding-left: 10px; display: table-cell; vertical-align: top;">
													<img src="/img/no-photo.jpg?1451486170" class="thumb avatar rounded " alt="Fafaf Afsa">
												</div>

												<div style="display: table-cell; padding-left: 20px;color: #666666; font-size: 14px; font-weight: bold; vertical-align: middle;">Николай Валентинович</div>
											</td>

											<td style="vertical-align: middle; text-align: center; font-size: 14px; color: #999;">2016-01-21 14:25:40</td>

											<td style="vertical-align: middle; text-align: center; color: #12d09b; font-size: 18px;">$500.00</td>

											<td style="position: relative; text-align: center; vertical-align: middle; font-size: 14px; color: #999;">
												<div class="">Open</div>

												<div class="" style="position: absolute; top: 50%; right: -110px; margin: -12px 0 0;">
													<a class="refound" href="/InvestProject/refundReward/2">Возврат</a>
												</div>
											</td>
										</tr>

										<tr>
											<td>
												<div class="thumb" style="padding-left: 10px; display: table-cell; vertical-align: top;">
													<img src="/img/no-photo.jpg?1451486170" class="thumb avatar rounded " alt="Fafaf Afsa">
												</div>

												<div style="display: table-cell; padding-left: 20px;color: #666666; font-size: 14px; font-weight: bold; vertical-align: middle;">Fafaf Afsa	</div>
											</td>

											<td style="vertical-align: middle; text-align: center; font-size: 14px; color: #999;">2016-01-21 14:25:40</td>

											<td style="vertical-align: middle; text-align: center; color: #12d09b; font-size: 18px;">$500.00</td>

											<td style="position: relative; text-align: center; vertical-align: middle; font-size: 14px; color: #999;">
												<div class="">Open</div>

												<div class="" style="position: absolute; top: 50%; right: -110px; margin: -12px 0 0;">
													<a class="refound" href="/InvestProject/refundReward/2">Возврат</a>
												</div>
											</td>
										</tr>

										<tr>
											<td>
												<div class="thumb" style="padding-left: 10px; display: table-cell; vertical-align: top;">
													<img src="/img/no-photo.jpg?1451486170" class="thumb avatar rounded " alt="Fafaf Afsa">
												</div>

												<div style="display: table-cell; padding-left: 20px;color: #666666; font-size: 14px; font-weight: bold; vertical-align: middle;">Fafaf Afsa	</div>
											</td>

											<td style="vertical-align: middle; text-align: center; font-size: 14px; color: #999;">2016-01-21 14:25:40</td>

											<td style="vertical-align: middle; text-align: center; color: #12d09b; font-size: 18px;">$500.00</td>

											<td style="position: relative; text-align: center; vertical-align: middle; font-size: 14px; color: #999;">
												<div class="">Open</div>

												<div class="" style="position: absolute; top: 50%; right: -110px; margin: -12px 0 0;">
													<a class="refound" href="/InvestProject/refundReward/2">Возврат</a>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>


						<div class="col-sm-12 header" style="color: #666; font-size: 16px;">
							<div class="row">
								<div class="col-sm-3">
									<?=$count_sponsors;//__('%s sponsors',$count_sponsors)?>
								</div>
								<div class="col-sm-3">
									<?=__('$%s collected', $pr['funded_total'])?>
								</div>
								<div class="col-sm-3">
									<?=__('1% commissions')?>
								</div>
							</div>

						</div>
					</div>

				</div>

		<?php
	 	$i++;
	 	endforeach; ?>
</div>
<div class="row my_invests_projects " style="display: none;">
	<div class="col-sm-8 project" style="margin: 25px 0;">
		<div class='row'>
			<!--div class="col-sm-8 groupFilterPackHead <?php echo ($i == 0)?'':'collapsed'?>" style="text-transform: uppercase; color: #666666; font-weight: bold; font-size: 16px;" data-num="<?=$i?>"> <?=$pr['name'];?>
				<span class="flow-right fa fa-caret-right arrow" style="margin-right: 10px;"></span>
			</div-->
		</div>
		<!--div class="groupFilterPackHead collapsed" data-num="<?=$i?>"> <?=$pr['name'];?> <span class="flow-right fa fa-angle-right arrow" style="margin-right: 10px;"></span></div-->
		<div class=" row section-content" id="groupFilterPack-<?=$i?>" style="display: block; margin-top: 15px;">

				<div class=" col-sm-12">
					<table class="table  table-striped">
						<thead>
							<tr div="col-sm-12">
								<th div="col-sm-4" style="padding: 20px 10px; text-align: center;  color: #666; font-size: 16px;"><?= __('Project name') ?></th>
								<th div="col-sm-4" style="padding: 20px 10px; text-align: center;  color: #666; font-size: 16px;"><?= __('Author of the project') ?></th>
								<th div="col-sm-2" style="padding: 20px 10px; text-align: center; color: #666; font-size: 16px;"><?= __('Date and time') ?></th>
								<!--th><?= __('Reward') ?></th-->
								<th div="col-sm-3" style="padding: 20px 10px; text-align: center; color: #666; font-size: 16px;"><?= __('Amount') ?></th>

								<th div="col-sm-3" style="padding: 20px 10px; text-align: center; color: #666; font-size: 16px;"><?= __('Status') ?></th>
								<th div="col-sm-3" style="padding: 20px 10px; text-align: center; color: #666; font-size: 16px;"></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($my_invests as $invested): ?>
								<?php //foreach ($reward['Sponsors'] as $sponsor): ?>
									<tr>
										<td style="    vertical-align: middle; text-align: center; font-size: 14px; color: #999;">
											<div style="display: table-cell; padding-left: 10px;color: #666666; font-size: 14px; font-weight: bold; vertical-align: middle;">
												<?php echo $invested['InvestProject']['name'] ?>
											</div>

										</td>
										<td>
											<div class="thumb" style=" display: table-cell; vertical-align: top;">
												<?php echo $this->Avatar->user($users[$invested['InvestProject']['user_id']], array('size' => 'thumb50x50', 'class' => 'rounded')); ?>
											</div>

											<div style="display: table-cell; padding-left: 10px;color: #666666; font-size: 14px; font-weight: bold; vertical-align: middle;">
												<?php echo $users[$invested['InvestProject']['user_id']]['User']['full_name']?>
											</div>
										</td>
										<td style="    vertical-align: middle; text-align: center; font-size: 14px; color: #999;">
											<?php echo $invested['InvestSponsor']['created'] ?>
										</td>
										<!--td><?php echo $sponsor['InvestReward']['name'] ?></td-->
										<td style="    vertical-align: middle; text-align: center; color: #12d09b; font-size: 18px;">
											$<?php echo $invested['InvestSponsor']['amount'] ?>
										</td>
										<td style="position: relative; text-align: center; vertical-align: middle; font-size: 14px; color: #999;">
											<div class="">
												<?=__('Open');?>
											</div>

										</td>
										<td style="position: relative; text-align: center; vertical-align: middle; font-size: 14px; color: #999;">
											<div class="edit-bar">
												<a style="font-size: 14px;" href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'view', $invested['InvestProject']['id'] ))?>">
													<span class="glyphicons eye_open"></span> <span><?=__('see project');?></span>
												</a>
											</div>
											<?if($currUserID == $invested['InvestSponsor']['user_id'] || $currUserID == $invested['InvestProject']['user_id'] ):?>
											<div class="" style="position: absolute; top: 30px; right: -100%;">
												<a class="refound"  href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'refundReward', $sponsor['id'] ))?>">
													<?=__('Refund')?>
												</a>

											</div>

										<?php endif;?>
										</td>
									</tr>
									<?php $count_sponsors++;?>
								<?php //endforeach; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>


			<!--div class="col-sm-12 header" style="color: #666; font-size: 16px;">
				<div class="row">
					<div class="col-sm-2">
						<?=$count_sponsors;//__('%s sponsors',$count_sponsors)?>
					</div>
					<div class="col-sm-3">
						<?=__('$%s collected', $pr['funded_total'])?>
					</div>
					<div class="col-sm-2">
						<?=__('1% commissions')?>
					</div>
				</div>

			</div-->
		</div>

	</div>
	<?php
		$i = 0;
		foreach ($investedProject as $invested): ?>
				<?php
						$results = Hash::extract($project, '{n}.InvestProject');
						$results = @array_unique($results);
						$pr = $results['0'];
						$count_sponsors = 0;
					//	var_dump($results);
				?>


		<?php
	 	$i++;
	 	endforeach; ?>
</div>
<!--div class="sponsors-list-page">
	<div class="row header">
		<div class="col-sm-1">
			<span class="strong"><?=__('Total:')?></span>
		</div>
		<div class="col-sm-2">
			<?php //__('%s sponsors',count($sponsors))?>
		</div>
		<div class="col-sm-3">
			<?php //__('$%s collected', $investProject['InvestProject']['funded_total'])?>
		</div>
		<div class="col-sm-2">
			<?=__('1% commissions')?>
		</div>
	</div>
	<div class="row groupViewInfo fixedLayout">
		<?php /*if(!empty($sponsors)): ?>
			<div class="col-sm-8">

			</div>

		<?php else: ?>
			<div class="row investmentsMoney fixedLayout">
			<?= __('The project has no sponsors') ?>
			</div>
		<?php endif;*/ ?>
	</div>
</div-->
<div class="popup-back" style="display: none; position: fixed; top: 0px; z-index: 500; left: 0px; width: 100%; bottom: 0px; background: rgba(204, 204, 204,0.8);"></div>
<?= $this->element('Invest/project_edit')?>
<script type="text/javascript">
function edit(){
	//   popup-content
	var body = $('body');
	var popup = $('.popup-back');
	var content = $('.popup-edit-content');
	body.append(popup);
	popup.show();
	body.append(content);
	content.show();


}
$(document).ready(function () {
// Init
$('.close-button').on('click',function(){
	var popup = $('.popup-back');
	var content = $('.popup-edit-content');
	popup.hide();
	content.hide();
})
$('.all_sponsors_list .investors_list').on('click',function(){
	//if($(this).hasClass('investors_list') && !$(this).hasClass('active')){
		$('.my_investments').removeClass('active');
		$('.my_invests_projects').hide();
		$('.investors_list').addClass('active');
		$('.my_projects').show();
	//}
	/*if($(this).hasClass('my_investments') && !$(this).hasClass('active')){
		$('investors_list').removeClass('active');
		$(this).addClass('active');
		$('.my_projects').hide();
		$('.my_invests_projects').show();
	}*/

});
$('.all_sponsors_list .my_investments').on('click',function(){
	/*if($(this).hasClass('investors_list') && !$(this).hasClass('active')){
		$('my_investments').removeClass('active');
		$('.my_invests_projects').hide();
		$(this).addClass('active');
		$('.my_projects').show();
	}*/
	//if($(this).hasClass('my_investments') && !$(this).hasClass('active')){
		$('.investors_list').removeClass('active');
		$('.my_investments').addClass('active');
		$('.my_projects').hide();
		$('.my_invests_projects').show();
	//}

});
	$('select, input.filestyle, input.checkboxStyle').styler({fileBrowse: 'Загрузить фото'});
	var investRemoveMedia = function (id) {
		$.post(investURL.removeMedia + '/' + id);
	};

	$('.groupFilterPackHead').off('tap');
    $('.groupFilterPackHead').on('tap', function() {
        thisElem = $(this);
        $(this).toggleClass('collapsed');
        if( $(this).hasClass('collapsed') ) {
            $('#groupFilterPack-'+$(this).data('num')).addClass('hiding');
            setTimeout((function() {
                $('#groupFilterPack-'+$(thisElem).data('num')).css('display', 'none');
            }), 450);
        } else {
            $('#groupFilterPack-'+$(this).data('num')).css('display', '');
            $('#groupFilterPack-'+$(this).data('num')).removeClass('hiding');
        }
    });
// Avatar Uploader
	$('#invest-project-avatar-uploader').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var id = response.data[0].Media.id;
				var src = response.data[0].Media.url_download;
				var oldAvatarId = $('#invest-project-avatar').val();
				if (oldAvatarId) {
					investRemoveMedia(oldAvatarId);
				}
				$('#invest-project-avatar').val(id);
				$('#invest-project-avatar-img').attr('src', src);
			});
            // progress
            $('#invest-project-avatarProgress').height(0);
            $('#invest-project-avatarProgress #progress-stats').html('');
            $('#invest-project-avatarProgress .progress-bar').attr('style', 'width: 0%');
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#invest-project-avatarProgress').height(20);
            $('#invest-project-avatarProgress #progress-stats').html(progress + '%');
            $('#invest-project-avatarProgress .progress-bar').attr('style', 'width: ' + progress + '%');
		}
	});
// Gallery
	var investProjectGallery = {
		templates: {
			gallery: 'tmpl-invest-project-gallery',
			videoInputs: 'tmpl-invest-project-video-inputs',
			imageInputs: 'tmpl-invest-project-image-inputs'
		},
		containers: {
			gallery: '#invest-project-gallery',
			videoInputs: '#invest-project-video-inputs',
			imageInputs: '#invest-project-image-inputs'
		},
		images: [],
		addItem: function (item) {
			this.images.push(item);
		},
		addVideo: function (link) {
			if (!link) {
				return;
			}
			if (!Youtube.isValidLink(link)) {
				alert('<?= __('Invalid youtube video') ?>');
				return;
			}
			var image = this.videoImage(link);
			if (!image) {
				return;
			}
			var videoId = this.videoId(link);
			this.addItem({
				type: 'video',
				image: image,
				videoId: videoId
			});
			this.render();
		},
		addImage: function (image, id) {
			if (!image || !id) {
				return;
			}
			this.addItem({
				type: 'image',
				image: image,
				id: id
			});
			this.render();
		},
		deleteItem: function (index) {
			if (!this.images[index]) {
				return;
			}
			if (this.images[index].type == 'image') {
				investRemoveMedia(this.images[index].id);
			}
			this.images.splice(index, 1);
			this.render();
		},
		render: function () {
			$(this.containers.gallery).html(tmpl(this.templates.gallery, this.images));
			$(this.containers.videoInputs).html(tmpl(this.templates.videoInputs, this.images));
			$(this.containers.imageInputs).html(tmpl(this.templates.imageInputs, this.images));
			// events for gallery element's
			$(this.containers.gallery).find('.remove').on('click', function () {
				investProjectGallery.deleteItem($(this).data('index'));
			});
		},
		videoId: function (url) {
			var videoId = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
			if(videoId == null || !videoId[1]) {
				return;
			}
			videoId = videoId[1];
			return videoId;
		},
		videoImage: function (url) {
			var videoId = this.videoId(url);
			if (videoId) {
				return 'http://img.youtube.com/vi/' + videoId + '/default.jpg';
			}
		}
	}
	$('#invest-project-addVideo-modal button').on('click', function () {
		var $input = $('#invest-project-addVideo-modal input');
		investProjectGallery.addVideo($input.val());
		$input.val('');

	});
	$('#invest-project-gallery-uploadImage').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var image = response.data[0].Media.image;
				var id = response.data[0].Media.id;
				investProjectGallery.addImage(image, id);
			});
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
		}
	});
// Imperavi-redactor image-uploader
	// button to redactor panel
	$('#redactor-toolbar-0').append($('#tmpl-invest-redactorUploadButton').html());
	$('#imperaviImageUploadButton').on('click', function () {
		$('#imperaviImageUpload').trigger('click');
	});
	var investProjectBodyImages = [];
	$('#imperaviImageUpload').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var id = response.data[0].Media.id;
				var imgUrl = response.data[0].Media.url_download;
				var imgName = response.data[0].Media.orig_fname;
				$('.redactor-editor').append('<p><img src="' + imgUrl + '" alt="' + imgName + '"></p>');
				$('.redactor_box.redactor').redactor('code.sync');
				investProjectBodyImages.push(id);
				$('#invest-project-body-inputs').html(tmpl('tmpl-invest-project-body-inputs', investProjectBodyImages));
			});
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
		}
	});
// Reward's

	//$('#invest-project-reward-add-button').trigger('click');
// Video
	$('[name="InvestProject[video]"]').on('blur', function () {
		if ($(this).val()) {
			if (!Youtube.isValidLink($(this).val())) {
				alert('<?= __('Invalid youtube video') ?>');
			}
		}
	});
// Submit
	$('#invest-project-create').on('submit', function () {
        // ipad validation
        var required = $('#invest-project-create').find('[required="true"]');
        for(var i = 0; i <= (required.length - 1);i++)
        {
            if($(required[i]).val() == '')
            {
                alert("'" + $(required[i]).data('label') + "'" + ' <?= __('is required') ?>');
                return false;
            }
        }

		// duration
		if ($('[name="InvestProject[duration]"]').val() != parseInt($('[name="InvestProject[duration]"]').val())) {
			alert("<?= __('Enter a integer number to Project duration') ?>");
			return false;
		}
		if ($('[name="InvestProject[duration]"]').val() > 60) {
			alert('<?= __('Project duration (60 days maximum)') ?>');
			return false;
		}

        // youtube link
        if ($('[name="InvestProject[video]"]').val()) {
            if (!Youtube.isValidLink($('[name="InvestProject[video]"]').val())) {
                alert('<?= __('Invalid youtube video') ?>');
                return false;
            }
        }
	});
// fix select
	$('.jq-selectbox.jqselect.category ul').height(300);
});
</script>
