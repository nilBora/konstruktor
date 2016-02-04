<div class="the-price">
    <h2>
        <?php echo $subscription['BraintreeSubscription']->nextBillAmount.' '.$subscription['BraintreePlan']->currencyIsoCode ?>
        <span class="subscript">/ <?php echo ($subscription['BraintreePlan']->billingFrequency == 12) ? __d('billing', 'yearly') : __d('billing', 'monthly') ?></span>
    </h2>
</div>
<table class="table table-striped">
    <?php if (isset($subscription['BraintreeSubscription']->addOns)&&!empty($subscription['BraintreeSubscription']->addOns)):?>
        <tr>
            <td>
                <?php
                    foreach($subscription['BraintreeSubscription']->addOns as $addOn){
                        echo $addOn->quantity.' '.__d('braintree', $addOn->name).'<br/>';
                    }
                ?>
				<?php if (!empty($subscription['BraintreePlan']->description)):?>
                	<?php echo  __d('braintree', $subscription['BraintreePlan']->description); ?>
				<?php endif; ?>
            </td>
        </tr>
	<?php elseif(!empty($subscription['BraintreePlan']->description)): ?>
		<tr>
	        <td>
	            <?php echo __d('braintree', $subscription['BraintreePlan']->description); ?>
	        </td>
	    </tr>
    <?php endif; ?>
    <tr>
        <td>
            <?php echo __d('braintree', $subscription['BraintreeSubscription']->status); ?>
        </td>
    </tr>
    <?php if(($subscription['BraintreeSubscription']->status != 'Canceled')&&isset($subscription['BraintreeSubscription']->nextBillingDate)):?>
        <tr>
            <td>
                <?php echo $subscription['BraintreeSubscription']->nextBillingDate->format('m/d/Y'); ?>
            </td>
        </tr>
    <?php endif; ?>
</table>
