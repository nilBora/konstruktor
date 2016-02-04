<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(__('Devices').': '.__('My orders'), array('controller' => 'Device', 'action' => 'checkout'));
?>

<div class="device-order col-sm-12">
    <div class="device-order-t-header clearfix  col-xs-12">
        <div class="number col-sm-3 col-xs-12"><?=__('Order ID')?></div>
        <div class="ingredients col-sm-7 col-xs-7"><?=__('Order items')?></div>
        <div class="price col-sm-2 col-xs-2"><?=__('Sum')?></div>
    </div>

<?
    $total = 0;
    foreach($aOrders as $order) {
        $order_id = $order['Order']['id'];
?>
    <div class="device-order-t-cell col-sm-12 clearfix">
        <div class="number-order col-sm-3 col-xs-12">
            <a href="<?=$this->Html->url(array('controller' => 'Device', 'action' => 'view', $order_id))?>"><?=$this->element('order_num', $order['Order'])?></a>
            <div class="order-data"><?=$this->LocalDate->date($order['Order']['created'])?></div>
        </div>
        <div class="item-order-list col-sm-9 col-xs-12 clearfix">
            <ul class="list-item-device">
<?
        $sum = 0;
        $totalMonth = 0;
        foreach($order['OrderType'] as $orderType) {
            $productType = $aProductTypeOptions[$orderType['product_type_id']]['ProductType'];
            $sum = ($productType['id'] == Configure::read('device.typePrinter')) ? 0 : $orderType['qty'] * $productType['arenda_price'];
            $totalMonth+= $sum;
            $total+= $sum;
?>

                <li class="clearfix">
                    <div class="list-item-inner col-xs-9">
                        <figure class="device-image">
                            <?=$this->element('product_image', $productType)?>
                            <span class="size">x<?=$orderType['qty']?></span>
                        </figure>
                        <div class="description"><?=$productType['title']?> (<?=$this->element('arenda_price', $productType)?>)</div>
<?
            if (isset($aDistrib[$order_id]) && isset($aDistrib[$order_id][$productType['id']])) {
                foreach($aDistrib[$order_id][$productType['id']] as $row) {
                    $user_id = $row['OrderProduct']['user_id'];
                    $user = $aUsers[$user_id];
?>
                        <div class="description clearfix">
                            <?php echo $this->Avatar->user($currUser, array(
                                'class' => 'floatL',
                                'style' => 'width: 50px',
                                'size' => 'thumb50x50'
                            )); ?>
                            <span>
                                <?=$user['User']['full_name']?>
                                <?=__('Printed')?>: <?=$row['Product']['prev_counter']?>
                            </span>

                            <span>ID: <?=$row['Product']['serial']?></span>
                            <span class="pull-right"><?=$this->element('sum', array('sum' => $productType['arenda_price']))?></span>
                        </div>
<?
                }
            }
?>                    </div>
                    <div class="item-order-price col-xs-3"><?=$this->element('sum', array('sum' => $sum))?></div>
                </li>
<?
        }
?>
            </ul>
            <div class="total-month">
                <div class="total-month-label col-xs-9"><?=__('Total, month')?></div>
                <div class="total-month-price col-xs-3"><?=$this->element('sum', array('sum' => $totalMonth))?></div>
            </div>
        </div>
    </div>
<?
    }
?>

    <div class="device-order-total clearfix"><div><?=$this->element('sum', array('sum' => $total))?></div></div>
</div>
