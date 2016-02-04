<div class="the-price">
    <h2>
        <?php echo __d('billing', 'Free') ?>
    </h2>
</div>
<table class="table table-striped">
    <tr>
        <td>
            <?php if (!empty($subscription['BillingPlan']['description'])):?>
                <?php echo $subscription['BillingPlan']['description']; ?>
            <?php else: ?>
                <?php echo $subscription['BillingPlan']['title']; ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo __d('braintree', $subscription['BillingSubscription']['status']); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo __d('billing', 'Lifetime'); ?>
        </td>
    </tr>
</table>
