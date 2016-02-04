<?php

$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
 ?>


<style>
.controlButtons a{
	color: #999;
	text-decoration: none;
	font-size: 19px;
	padding: 5px;

}
.controlButtons .glyphicons:before{
	position: relative;
}
.controlButtons .glyphicons.eye_open:before{
	top: -1px;
}
.controlButtons .glyphicons.edit:before{
	top: -3px;
}
</style>

<div class="popup-edit-content" style="z-index: 501;  position: fixed; left: 50%;  top: 20px;  display: none;" >
	<div class="" style="background: #fff; left: -50%; position: relative;overflow: auto;max-height: 600px;">
		<div class="popup-header">
			<h2 class="desc"><?=__('Project Description')?></h2>
			<div class="close-button">
				<span class="glyphicons remove_2 i2"></span>
			</div>
		</div>
		<form id="invest-project-create" action="/InvestProject/editProject" method="post">
			<div class="col-sm-12 " style="border-bottom: 2px solid #eee;padding: 0 35px;">
			<input type="hidden" name="InvestProject[id]" value="<?=$investProject['InvestProject']['id']?>" id="invest-project-avatar"/>
			<div class="form-group InvestProject-name">
				<input name="InvestProject[name]" maxlength="150" id="InvestProjectName" data-label="<?= __('Project name') ?>" required="true" type="text" class="form-control" placeholder="<?= __('Project name') ?>" value="<?=$investProject['InvestProject']['name']?>" />
			</div>
			<?=$this->Redactor->tiny('body',array('value' => $investProject['InvestProject']['body']))?>
			<div class="row InvestProject-totals">
				<div class="col-sm-4">
					<div class="form-group">
						<div class="needSum">
								<input name="InvestProject[total]"  style="padding-right: 20px;" id="InvestProjectTotal" type="number" step="0.01" min="0" data-label="<?= __('Necessary sum') ?>" required="true" class="form-control" placeholder="<?= __('Necessary sum') ?>" value="<?=$investProject['InvestProject']['total']?>" />
								<!--select  name="InvestProject[currency]" class="currency" style="width: 50px;">
										<option selected value="USD"><?= $this->Money->symbolFor('USD') ?></option>
										<option value="EUR"><?= $this->Money->symbolFor('EUR') ?></option>
										<option value="RUB"><?= $this->Money->symbolFor('RUB') ?></option>
								</select-->
								<span style="position: absolute; top: 8px; right: 22px;" value="USD"><?= $this->Money->symbolFor('USD') ?></span>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<div class="">
							<input name="InvestProject[duration]" id="InvestProjectDuration" data-label="<?= __('Project duration') ?>" required="true" type="number" min="1" max="60" step="1" class="form-control" placeholder="<?= __('Project duration (to 60 days)') ?>" value="<?=$investProject['InvestProject']['duration']?>" />
							<input type="hidden" name="InvestProject[currency]" value="USD" id="invest-project-avatar"/>

						</div>
					</div>
				</div>
				<div class="col-sm-4 paymentsAndTerms">
					<select id="InvestProjectCurrency" class="currency" style="width: 50px;">
							<option value="">Системы оплаты</option>
							<option value="paypal">PayPal</option>
							<option value="visa">Visa</option>
					</select>
				</div>
			</div>
			</div>
			<div class="col-sm-12" style="padding: 10px 35px 20px;">
				<div class="clearfix hidden-xs"></div>
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-sm-3" style="text-transform: uppercase;padding: 5px 20px 0 ;">
						<?=__('Reward')?>
					</div>
					<div class="col-sm-3 leftFormBlock">
						<a href="javascript:void(0)" class="addNewInfo btn btn-default" id="invest-project-reward-edit-add-button">
							<span class=""><?= __('Add') ?></span>
						</a>
					</div>
				</div>
				<div class="row" >
					<div class="col-sm-9 reward" id="invest-project-edit-reward-list">
						<?php
						$i = 0;
						foreach ($investProject['Rewards'] as $reward) {
						?>
						<div class="back" style="margin-top: 20px;">
							<span class="glyphicons " ></span>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group" style="margin-bottom: 10px;">
										<input name="InvestReward[<?=$i;?>][id]" type="hidden" class="form-control" placeholder="<?= __('Title') ?>" value="<?=$reward['id']?>" data-label="<?= __('Title') ?>" required="true" />
										<input name="InvestReward[<?=$i;?>][name]" type="text" class="form-control" placeholder="<?= __('Title') ?>" value="<?=$reward['name']?>" data-label="<?= __('Title') ?>" required="true" />
									</div>
									<div class="row">
										<div class="col-sm-6" >
											<div class="form-group"  style="margin-bottom: 10px;">
												<div class="input-group">
													<input name="InvestReward[<?=$i;?>][total]" min="0" type="number" step="0.01" class="form-control" placeholder="<?= __('Amount') ?>" value="<?=$reward['total']?>" data-label="<?= __('Amount') ?>" data-label="<?= __('Amount') ?>" required="true"/>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group"  style="margin-bottom: 10px;">
												<div class="input-group">
													<!--div class="input-group-addon glyphicons calendar"></div-->
													<input type="text" class="form-control created-edit" value="<?=$reward['created']?>" data-label="<?= __('Delivery date') ?>" placeholder="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-<?=$i;?>" readonly/>
													<input name="InvestReward[<?=$i;?>][created]" type="hidden" placeholder="<?= __('Delivery date') ?>" value="<?=$reward['created']?>" data-label="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-<?=$i;?>-mirror"/>
												</div>
												<script>
												$('.created-edit').datetimepicker({
													format: '<?= $dateFormat?>',
													weekStart: 1,
													autoclose: 1,
													todayHighlight: 1,
													startView: 2,
													minView: 2,
													language:"<?=$lang?>",
													linkField: 'invest-project-reward-' + '<?=$i;?>' + '-mirror',
													linkFormat: 'yyyy-mm-dd hh:ii:ss'
												});

												$('#invest-project-reward-' + <?=$i;?>).datetimepicker().on('focus', function () {
													var dts = $('.datetimepicker');
													for(var i = 0; i <= (dts.length - 1);i++) {
														var dt = $(dts[i]);
														dt.css('left', parseInt(dt.css('left')) - 50);
													}
												});

												</script>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
								<div class="form-group"  style="margin-bottom: 10px;">
									<textarea name="InvestReward[<?=$i;?>][description]" type="text" class="form-control" placeholder="<?= __('Description') ?>" value="" data-label="<?= __('Description') ?>" required="true"  rows="5" cols="40"><?=$reward['description']?></textarea>
								</div>
								</div>
							</div>
						</div>
						<?php
							$i++;
						}?>
						<div id="reward-counter" data-counter="<?=$i?>"></div>
					</div>
				</div>
				<button class="btn btn-primary save" style="position: absolute;right: 35px;bottom: 20px;background-color: #FFBA4C;color: #fff; border: none;" type="submit"><?= __('Save') ?></button>
			</div>

		</form>
		<div style="clear: both;"></div>
	</div>
</div>
