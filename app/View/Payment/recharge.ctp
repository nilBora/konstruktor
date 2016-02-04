<div class="personal-account col-sm-12">
<?
	if (Hash::get($this->request->query, 'paid')) {
?>
	<div class="description"><?=__('Thank you for your payment')?></div>
<?
	} elseif (Hash::get($this->request->query, 'cancel')) {
?>
	<div class="description"><?=__('Your payment has been cancelled')?></div>
<?
	} else {
?>
    <div class="description"><?=__('Choose the suitable payment system to recharge your balance')?></div>
    <form action="<?=$this->Html->url(array('controller' => 'Payment', 'action' => 'paypal'))?>" method="post">
        <div class="payment-personal-account">
            <ul class="clearfix">
            	<!--
                <li class="active visa"></li>
                <li class="master-cart"></li>
                <li class="yandex-money"></li>
                <li class="qiwi"></li>
                -->
                <li class="paypal"></li>
            </ul>
        </div>
        <div class="payment-amount">
            <label for="payment-amount-input"><?=__('Recharge amount')?>, $</label> <br/>
            <div class="input-block page-menu">
                <input type="text" id="payment-amount-input" value="" name="data[total]" />
                <button type="button" class="btn btn-default btn-sm">
                    <span class="glyphicons send"></span>
                </button>
            </div>
        </div>
    </form>
<?
	}
?>
</div>
