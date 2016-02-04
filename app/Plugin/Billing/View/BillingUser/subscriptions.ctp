<?php
    echo $this->Html->css('Billing.forms', array('inline' => false));
    echo $this->Html->css('Billing.panels', array('inline' => false));

	$user_id = Hash::get($currUser, 'User.id');
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), '/User/view/'.$user_id);
	$this->Html->addCrumb(__d('billing', 'Subscriptions'), array('plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'subscriptions'));
?>
<?php //debug($transactions);?>
<div class="payment">
    <ul id="mySubscriptions" class="nav nav-pills nav-justified" role="tablist">
        <li class="active">
            <a href="#subscriptions" aria-controls="subscriptions" role="tab" data-toggle="tab"><?php echo __d('billing', 'Subscriptions') ?></a>
        </li>
        <li>
            <a href="#transactions" aria-controls="transactions" role="tab" data-toggle="tab"><?php echo __d('billing', 'Transactions') ?></a>
        </li>
    </ul>

    <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="subscriptions">
        <?php if(count($subscriptions)):?>
        <div class="row" style="max-width: 870px; margin: 0 auto;">
            <?php foreach($subscriptions as $subscription):?>
                <div class="col-xs-12 col-md-6 col-plan active-subsctiption">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <?php echo $subscription['BillingGroup']['title'] ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <?php
                                if(isset($subscription['BraintreeSubscription'])&&!empty($subscription['BraintreeSubscription'])):
                                    echo $this->element('Billing.subscriptions/paid', array('subscription' => $subscription));
                                else:
                                    echo $this->element('Billing.subscriptions/free', array('subscription' => $subscription));
                                endif;
                            ?>
                        </div>
                        <div class="panel-bg"></div>
                    </div>
                    <div class="billing-submit">
                        <?php
                            echo $this->Html->link(__d('billing', 'Change'),
                                array('controller' => 'billing_subscriptions', 'action' => 'plans', $subscription['BillingGroup']['slug']),
                                array('class' => 'btn btn-default', 'role' => 'button')
                            );
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <?php echo __d('billing', 'You are not subscribed to any subscription yet.') ?>
            </div>
        <?php endif; ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="transactions">
        <?php if($transactions->valid()): ?>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th><?php echo __d('billing', 'Subscription'); ?></th>
                <th><?php echo __d('billing', 'Source'); ?></th>
                <th><?php echo __d('billing', 'Date & Time'); ?></th>
                <th><?php echo __d('billing', 'Amount'); ?></th>
                <th><?php echo __d('billing', 'Status'); ?></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $transaction): ?>
                    <?php
                        $plan = array();
                        foreach($plans as $_plan):
                            if(in_array($transaction->planId, $_plan['BillingPlan']['remote_plans'])):
                                $plan = $_plan;
                                break;
                            endif;
                        endforeach;
                        if(empty($plan)):
                            continue;
                        endif;
                    ?>
                    <tr>
                        <td>
                            <?php //echo $plan['BillingGroup']['title'].' '.$plan['BillingPlan']['title']; ?>
                            <?php echo $plan['BillingPlan']['title']; ?>
                        </td>
                        <td>
                            <?php
                                if(isset($transaction->paypalDetails)){
                                    echo $this->Html->image($transaction->paypalDetails->imageUrl, array('width' => 28, 'height' => 19));
                                    echo $transaction->paypalDetails->payerEmail;
                                } else {
                                    echo $this->Html->image($transaction->creditCardDetails->imageUrl, array('width' => 28, 'height' => 19));
                                    echo $transaction->creditCardDetails->maskedNumber;
                                }
                            ?>
                        </td>
                        <td><?php echo $transaction->updatedAt->format('Y-m-d H:i:s'); ?></td>
                        <td><?php echo $transaction->amount.' '.$transaction->currencyIsoCode; ?></td>
                        <td><?php echo ucfirst(str_replace('_', ' ', $transaction->status)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <?php echo __d('billing', 'There was no any transactions yet.') ?>
            </div>
        <?php endif; ?>
    </div>
    </div>
</div>
