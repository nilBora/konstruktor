<?php

echo $this->Html->css('Billing.panels', array('inline' => false));

	$user_id = Hash::get($currUser, 'User.id');
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), '/User/view/'.$user_id);
	$this->Html->addCrumb(__d('billing', 'Subscriptions'), array('plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'subscriptions'));
	$this->Html->addCrumb(__d('billing', 'Members'), array('plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'plans'.'/members'));
?>
<div class="row">
	<?php echo $this->Html->tag('h2', $group['BillingGroup']['title']) ?>
	<?php echo $this->Form->create(false, array('id' => 'BillingSubscriptionPlansForm', 'url' => array('controller' => 'billing_subscriptions', 'action' => 'payment'))); ?>
	<div class="col-md-6 col-md-offset-3 col-plan members">
	    <div class="panel panel-primary">
	        <div class="panel-heading">
	            <h3 class="panel-title"><?php echo __d('billing', 'Expand available members')?></h3>
	            <div id="priceInfo" class="price-info"><?php echo __d('billing', '1 user - 4$ per month') ?></div>
				<?php if(isset($currentSubscription['BillingSubscription']['remote_plan_id'])):?>
					<div><?php echo __d('billing', 'You are already subscribed to %d members', $currentSubscription['BillingSubscription']['limit_value'])?></div>
				<?php endif; ?>
	        </div>
	        <div class="panel-body">
	            <div class="form-group">
	            <?php
	                echo $this->Form->input('qty', array(
	                    'type' => 'text',
	                    'id' => 'BillingMembersQuantity',
	                    'label' => __d('billing', 'Members quantity'),
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
	                    'id' => 'BillingMembersPeriod',
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
	                        'id' => 'BillingMembersTotal',
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
<?php //debug($currentSubscription); ?>

<script>
$(function() {
	var addOns = $.parseJSON('<?php echo json_encode($addOns) ?>');
    function calculateBillingTotal(){
        currentPlan = $('#BillingMembersPeriod').val();
        localAddOns = addOns[currentPlan];
        var total = totalVal = 0;
        $.each(localAddOns, function(key, value){
            total = total + parseInt($('#BillingMembersQuantity').val())*value;
			totalVal = totalVal + value;
        });
		$('#BillingMembersTotal').val(total+' $');
		$('#priceInfo').html('1 user - '+(+totalVal).toFixed(4).replace(/\.0+$/,'')+'$ per month');
    }
    $('#BillingMembersQuantity').bind("paste cut keyup", function(){
        currVal = $(this).val();
        reg = new RegExp('^[0-9]+$');
        if((currVal == '')||!reg.test(currVal)){
            $(this).val(1);
        }
        calculateBillingTotal();
    });
    $('#BillingMembersPeriod').on('change', function(){
        calculateBillingTotal();
    });
    calculateBillingTotal();

    $('select').styler();
});
</script>
