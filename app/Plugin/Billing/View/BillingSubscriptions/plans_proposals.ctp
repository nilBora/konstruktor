<?php echo $this->Html->css('Billing.panels', array('inline' => false)); ?>

<div class="row">
	<?php echo $this->Html->tag('h2', __d('billing', $group['BillingGroup']['title'])) ?>
	<?php echo $this->Form->create(false, array('id' => 'BillingSubscriptionPlansForm', 'url' => array('controller' => 'billing_subscriptions', 'action' => 'payment'))); ?>
	<div class="col-md-6 col-md-offset-3 col-plan members">
	    <div class="panel panel-primary">
	        <div class="panel-heading">
	            <h3 class="panel-title"><?php echo __d('billing', 'Expand available proposals to tasks')?></h3>
	            <div id="priceInfo" class="price-info"><?php echo __d('billing', '1 proposal - 1$ per month') ?></div>
				<?php if(isset($currentSubscription['BillingSubscription']['remote_plan_id'])):?>
					<div><?php echo __d('billing', 'You are already use %d proposals', $currentSubscription['BillingSubscription']['limit_value'])?></div>
				<?php endif; ?>
	        </div>
	        <div class="panel-body">
	            <div class="form-group">
	            <?php
	                echo $this->Form->input('qty', array(
	                    'type' => 'text',
	                    'id' => 'BillingRequestsQuantity',
	                    'label' => __d('billing', 'Proposal quantity'),
	                    'value' => (isset($currentSubscription['BillingSubscription']['limit_value']) ? $currentSubscription['BillingSubscription']['limit_value'] : 1),
	                    'div' => false
	                ));
	            ?>
	            </div>
	            <div class="form-group">
	            <?php
	                $plans = $addOns = array();
	                foreach($group['BillingPlan'] as $billingPlan):
	                    foreach($billingPlan['BraintreePlan'] as $braintreePlan):
	                        $plans[$braintreePlan->id] = __d('braintree', $braintreePlan->name);
	                        foreach($braintreePlan->addOns as $addOn):
	                            $addOns[$braintreePlan->id][$addOn->id] = $addOn->amount;
	                        endforeach;
	                    endforeach;
	                endforeach;
	                echo $this->Form->input('plan', array(
	                    'type' => 'select',
	                    'id' => 'BillingRequestsPeriod',
	                    'options' => $plans,
	                    'label' => __d('billing', 'Period'),
						'value' => (isset($currentSubscription['BillingSubscription']['remote_plan_id']) ? $currentSubscription['BillingSubscription']['remote_plan_id'] : ''),
	                ));
	            ?>
	            </div>
	            <div class="form-group button clearfix">
	                <?php
	                    echo $this->Form->input('summ', array(
	                        'type' => 'text',
	                        'id' => 'BillingRequestsTotal',
	                        'readonly' => true,
	                        'div' => false,
	                        'label' => __d('billing', 'Total').':'
	                    ));
	                ?>
	                <?= $this->Form->button(__d('billing', 'Subscribe'), array('type' => 'submit', 'class' => "btn btn-primary", 'role' => "button")) ?>
	            </div>
	        </div>
	        <div class="panel-bg"></div>
	    </div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

<script>
$(function() {
	var addOns = $.parseJSON('<?php echo json_encode($addOns) ?>');
    function calculateBillingTotal(){
        currentPlan = $('#BillingRequestsPeriod').val();
        localAddOns = addOns[currentPlan];
        var total = totalVal = 0;
        $.each(localAddOns, function(key, value){
            total = total + parseInt($('#BillingRequestsQuantity').val())*value;
			totalVal = totalVal + value;
        });
		$('#BillingRequestsTotal').val(total+' $');
		$('#priceInfo').html('1 proposal - '+(+totalVal).toFixed(4).replace(/\.0+$/,'')+'$ per month');
    }
    $('#BillingRequestsQuantity').bind("paste cut keyup", function(){
        currVal = $(this).val();
        reg = new RegExp('^[0-9]+$');
        if((currVal == '')||!reg.test(currVal)){
            $(this).val(1);
        }
        calculateBillingTotal();
    });
    $('#BillingRequestsPeriod').on('change', function(){
        calculateBillingTotal();
    });
    calculateBillingTotal();

    $('select').styler();
});
</script>
