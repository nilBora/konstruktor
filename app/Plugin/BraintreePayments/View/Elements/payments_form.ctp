<?php
$this->Html->css('Billing.forms', array('inline' => false));
$this->Html->script('https://js.braintreegateway.com/v2/braintree.js', array('inline' => false));
?>
<div class="container">
    <div class="row">
        <?php echo $this->Flash->render() ?>
        <div class="col-xs-12 col-md-8 col-md-offset-2 payment">
            <ul id="paymentTabs" class="nav nav-pills nav-justified" role="tablist">
                <?php if(!empty($customer->paymentMethods)): ?>
                    <li role="presentation" class="active">
                        <a href="#payments" aria-controls="payments" role="tab" data-toggle="tab"><?php echo __d('billing', 'Saved payments') ?></a>
                    </li>
                <?php endif ?>
                <li role="presentation" class="<?php echo empty($customer->paymentMethods) ? 'active' : '' ?>">
                    <a href="#add-card" aria-controls="add-card" role="tab" data-toggle="tab"><?php echo __d('billing', 'Add card') ?></a>
                </li>
                <li role="presentation">
                    <a href="#add-paypal" aria-controls="add-paypal" role="tab" data-toggle="tab"><?php echo __d('billing', 'Add Paypal') ?></a>
                </li>
            </ul>
            <div class="tab-content">
                <?php if(!empty($customer->paymentMethods)): ?>
                    <div role="tabpanel" class="tab-pane active" id="payments">
                    <?php echo $this->Form->create(null, array('id' => 'choose-checkout', 'url' => $formUrl)) ?>
                        <?php foreach($customer->paymentMethods as $id=>$paymentMethod): ?>
                            <div class="form-group">
                                <?php
                                    if($paymentMethod instanceof Braintree_PayPalAccount) {
                                        $label = $this->Html->image($paymentMethod->imageUrl, array('width' => 28, 'height' => 19))
                                            .' '.$paymentMethod->email;
                                        echo $this->Form->radio('payment_method',
                                            array($paymentMethod->token => $label),
                                            array('name' => 'payment_method', 'hiddenField' => false)
                                        );
                                    } elseif($paymentMethod instanceof Braintree_CreditCard) {
                                        $label = $this->Html->image($paymentMethod->imageUrl, array('width' => 28, 'height' => 19))
                                            .' '.$paymentMethod->maskedNumber;
                                        echo $this->Form->radio('payment_method',
                                            array($paymentMethod->token => $label),
                                            array('name' => 'payment_method', 'hiddenField' => false)
                                        );
                                    }
                                ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="text-center">
                            <?php echo $this->Form->submit($formButtonText, array('class' => 'btn btn-primary')) ?>
                        </div>
                    <?php echo $this->Form->end() ?>
                    </div>
                <?php endif ?>
                <div role="tabpanel" class="tab-pane <?php echo empty($customer->paymentMethods) ? 'active' : '' ?>" id="add-card">
                <?php echo $this->Form->create(null, array('id' => 'card-checkout', 'url' => $formUrl,  'autocomplete' => 'off')) ?>
                    <div class="form-group">
                        <label><?=__d('billing', 'Card number')?></label>
                        <div id="braintree-card-number" class="description contentEditable"></div>
                    </div>
                    <div class="form-group">
                        <label><?=__d('billing', 'CVV')?></label>
                        <div id="braintree-card-cvv" class="description contentEditable"></div>
                    </div>
                    <div class="form-group">
                        <label><?=__d('billing', 'Card expiration date')?></label>
                        <div id="braintree-card-expirationMonth" class="description contentEditable expDate"></div>
                        <div class="description contentEditable expDateDivider">/</div>
                        <div id="braintree-card-expirationYear" class="description contentEditable expDate"></div>
                    </div>
                    <div class="braintreeError"></div>
                    <div class="text-center">
                        <?php echo $this->Form->submit($formButtonText, array('class' => 'btn btn-primary')) ?>
                    </div>
                <?php echo $this->Form->end() ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="add-paypal">
                <?php echo $this->Form->create(null, array('id' => 'paypal-checkout', 'url' => $formUrl)) ?>
                    <div id="paypal-container"></div>
                    <div class="text-center">
                        <?php echo $this->Form->submit( $formButtonText, array('class' => 'btn btn-primary')) ?>
                    </div>
                <?php echo $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->Html->scriptStart(); ?>
braintree.setup("<?= $clientToken ?>", "custom", {
    id: "card-checkout",
    hostedFields: {
        number: {
            selector: "#braintree-card-number",
            placeholder: "ex: 4005519200000004"
        },
        cvv: {
            selector: "#braintree-card-cvv",
            placeholder: "ex: 100"
        },
        expirationMonth: {
            selector: "#braintree-card-expirationMonth",
            placeholder: "<?php echo date('m')?>"
        },
        expirationYear: {
            selector: "#braintree-card-expirationYear",
            placeholder: "<?php echo date('y')?>"
        },
        styles: {
            "input": {
                "font-size": "24px",
                "color": "#313131"
            },
            ".expDate": {
                "display": "inline-block",
                "width": "100px",
                "text-align": "center",
            },
            ".invalid": {
              "color": "red"
            },
        }
    },
    onError: function(error){
        $('.braintreeError').html(error.message).show();
        setTimeout(function() {
            $('.braintreeError').fadeOut(800);
        }, 3000);
    }
});
braintree.setup("<?= $clientToken ?>", "paypal", {
    id: "paypal-checkout",
    container: "paypal-container",
    singleUse: false
});
$('select').styler();
<?php echo $this->Html->scriptEnd(); ?>
