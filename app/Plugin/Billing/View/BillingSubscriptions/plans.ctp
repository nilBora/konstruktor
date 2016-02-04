<?php echo $this->Html->css('Billing.panels', array('inline' => false)); ?>

<?php
	$user_id = Hash::get($currUser, 'User.id');
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), '/User/view/'.$user_id);
	$this->Html->addCrumb(__d('billing', 'Subscriptions'), array('plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'subscriptions'));
	$this->Html->addCrumb(__d('billing', 'Disc space'), array('plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'plans'.'/disc-space'));
?>
<div class="row">
    <?php echo $this->Html->tag('h2', $group['BillingGroup']['title'], array('class' => 'page-header')) ?>
    <?php echo $this->Flash->render() ?>
    <?php echo $this->Form->create(false, array('id' => 'BillingSubscriptionPlansForm', 'url' => array('controller' => 'billing_subscriptions', 'action' => 'payment'))); ?>
    <?php foreach($group['BillingPlan'] as $plan): ?>
    <div class="col-xs-12 col-md-3 col-plan">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo $this->Html->tag('h3', $plan['title'], array('class' => 'panel-title')) ?>
            </div>
            <div class="panel-body">
                <?php if(isset($plan['BraintreePlan'])&&!empty($plan['BraintreePlan'])): ?>
                    <?php foreach($plan['BraintreePlan'] as $remotePlan):?>
                        <?php
                        $current = '';
                        if(isset($currentSubscription['BraintreePlan']->id)
                            &&($remotePlan->id == $currentSubscription['BraintreePlan']->id)):
                            $current = __d('billing', 'current');
                        endif;
                        ?>
                        <div class="form-group">
                        <?php
                            $label = '';
                            if($remotePlan->price != 0){
                                //temporarely only for 1 month and 1 year
                                if($remotePlan->billingFrequency == 12){
                                    $label .= ' 1'.__d('billing', 'year');
                                } else {
                                    $label .= ' '.$remotePlan->billingFrequency.__d('billing', 'month');
                                }
                                $price = $remotePlan->price;
                                foreach($remotePlan->discounts as $discount){
                                    $price -= $discount->amount;
                                }
                                $label .= ' - '.$price.'$';
                            } else {
                                $label .= __d('billing', 'Free');
                            }
                            if(!empty($current)){
                                $label .= ' ('.$current.')';
                            }
                            echo $this->Form->radio('plan',
                                array($remotePlan->id => $label),
                                array('name' => 'plan', 'hiddenField' => false, 'checked' => (!empty($current) ? true : false))
                            );
                        ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="form-group">
                        <?php
                            $current = '';
                            if(!isset($currentSubscription['BraintreePlan'])&&($plan['free'] == true)):
                                $current = __d('billing', 'current');
                            endif;
                            $label = __d('billing', 'Free');
                            if(!empty($current)){
                                $label .= ' ('.$current.')';
                            }
                            echo $this->Form->radio('plan',
                                array($plan['id'] => $label),
                                array('name' => 'plan', 'hiddenField' => false, 'checked' => (!empty($current) ? true : false))
                            );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="panel-bg"></div>
        </div>
        <?php if(!isset($currentSubscription['BraintreePlan'])&&($plan['free'] == true)): ?>
            <!-- Already subscribed to free plan -->
        <?php else: ?>
            <div class="billing-submit">
                <?= $this->Form->button(__d('billing', 'Subscribe'), array('type' => 'submit', 'class' => "btn btn-default", 'role' => "button")) ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php echo $this->Form->end(); ?>
</div>

<script>
$(function() {
    $('[type="radio"]').styler();
});
</script>
