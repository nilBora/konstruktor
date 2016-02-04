
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
<div class="popup-back" style="display: none; position: fixed; top: 0px; z-index: 500; left: 0px; width: 100%; bottom: 0px; background: rgba(204, 204, 204,0.8);"></div>
<div class="popup-content" style="z-index: 501;  position: fixed; left: 50%;  top: 20px;  display: none;" >
	<div class="" style="background: #fff; left: -50%; position: relative;overflow: auto;max-height: 600px;">
		<div class="popup-header">
			<h2 class="desc"><?=__('Project Description')?></h2>
			<div class="close-button">
				<span class="glyphicons remove_2 i2"></span>
			</div>
		</div>
		<form id="invest-project-create" action="/InvestProject/addProject" method="post">
			<div class="col-sm-12 " style="border-bottom: 2px solid #eee;padding: 0 35px;">
			<input type="hidden" name="InvestProject[group_id]" value="<?=$group['Group']['id']?>"/>
			<input type="hidden" name="InvestProjectAvatar[id]" value="" id="invest-project-avatar"/>
			<div class="form-group InvestProject-name">
				<input name="InvestProject[name]" maxlength="150" data-label="<?= __('Project name') ?>" required="true" type="text" class="form-control" placeholder="<?= __('Project name') ?>" value="" />
			</div>
			<?=$this->Redactor->redactor('body')?>
			<div class="row InvestProject-totals">
				<div class="col-sm-4">
					<div class="form-group">
						<div class="needSum">
								<input name="InvestProject[total]" style="padding-right: 15px;" type="number" min="0" step="0.01" data-label="<?= __('Necessary sum') ?>" required="true" class="form-control" placeholder="<?= __('Necessary sum') ?>" value="" />
								<!--select name="InvestProject[currency]" class="currency" style="width: 50px;">
										<option value="USD"><?= $this->Money->symbolFor('USD') ?></option>
										<option value="EUR"><?= $this->Money->symbolFor('EUR') ?></option>
										<option value="RUB"><?= $this->Money->symbolFor('RUB') ?></option>
								</select-->
								<span style="position: absolute; top: 8px; right: 20px;" value="USD"><?= $this->Money->symbolFor('USD') ?></span>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<div class="">
							<input name="InvestProject[duration]" data-label="<?= __('Project duration') ?>" required="true" type="number" min="1" max="60" step="1" class="form-control" placeholder="<?= __('Project duration (to 60 days)') ?>" value="" />
						</div>
					</div>
				</div>
				<div class="col-sm-4 paymentsAndTerms">
					<select name="InvestProject[currency]" class="currency" style="width: 50px;">
							<option value="">Системы оплаты</option>
							<option value="paypal">paypal</option>
							<option value="visa">visa</option>
					</select>
				</div>
			</div>
			</div>
			<div class="col-sm-12" style="padding: 10px 35px 20px;">
				<div class="clearfix hidden-xs"></div>
				<div class="row">
					<div class="col-sm-3" style="text-transform: uppercase;padding: 5px 20px 0 ;">
						<?=__('Reward')?>
					</div>
					<div class="col-sm-3 leftFormBlock">
						<a href="javascript:void(0)" class="addNewInfo btn btn-default" id="invest-project-reward-add-button">
							<span class=""><?= __('Add') ?></span>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-9 reward" id="invest-project-reward-list"></div>
				</div>
				<button class="btn btn-primary save" style="position: absolute;right: 35px;bottom: 20px;background-color: #FFBA4C;color: #fff; border: none;" type="submit"><?= __('Save') ?></button>
			</div>

		</form>
		<div style="clear: both;"></div>
	</div>
</div>
