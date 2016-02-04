<?
	$this->Html->script('device-manage', array('inline' => false));
?>
<div class="device-order col-sm-12">
    <div class="macro-order clearfix">
        <div class="status col-md-4 col-xs-12"><?=__('Order status')?>: <span><?=$this->element('order_status')?></span></div>
        <div class="data col-md-5 col-xs-12"><?=$this->LocalDate->date($order['Order']['created'])?></div>
    </div>
    <div class="items-stock col-sm-9 col-xs-12">
<?
	foreach($aProductTypes as $productType) {
?>
        <div class="col-xs-4">
            <?=$productType['ProductType']['title']?> <br/>
            <?=$this->element('product_image', $productType['ProductType'])?>
            <span class="item">x<?=$productType['ProductType']['qty']?></span>
        </div>
<?
	}
?>
    </div>
<?
	if ($canDistribute) {
?>
    <div class="distribution-devices col-xs-12">
        <div class="devices-title"><?=__('Distribute devices')?></div>
        <div class="devices-sub">
            <ul class="devices-list">
<?
		foreach($aProductTypes as $id => $productType) {
?>
				<li class="deviceType" id="deviceType_<?=$id?>"><a href="javascript:void(0)" onclick="DeviceManage.activate(<?=$id?>)"><?=$this->element('product_image', $productType['ProductType'])?></a></li>
<?
		}
?>
            </ul>
            <div class="devices-size">
                <label for="#"><?=__('Quantity')?></label>
                <input type="text" class="" value="" />
                <span class="help-text"></span>
            </div>
        </div>
        <div class="devices-add clearfix">
            <form class="add-user-email" action="<?=$this->Html->url(array('controller' => 'DeviceAjax', 'action' => 'findUser'))?>">
                <div class="form-group">
                    <label for="devices-add-email"><?=__('Email')?></label> <br/>
                    <input id="devices-add-email" type="text" name="data[email]" value="" placeholder="<?=__('Email')?>" />
                    <button type="button" class="btn btn-default glyphicons search"></button>
                </div>
                <div class="devices-add-sub clearfix">
                    <div class="id">id: -</div>
                    <div class="last-device"><?=__('Devices left')?>: <span class="devicesLeft">-</span> </div>
                </div>
            </form>
            <div class="groupAccess clearfix">
                <?=$this->element('Device/find_user')?>
            </div>
        </div>
    </div>
<?
	}
?>
    <div class="distribution-devices col-xs-12">
        <div class="head-device clearfix">
            <div class="devices-title"><?=__('Distributed devices')?></div>
            <!--form class="head-device-input" action="#">
                <input type="text" placeholder="Пользователь или номер ID"/>
                <button type="submit" class="glyphicons search"></button>
            </form-->
        </div>
        <div class="distributedProducts"></div>
    </div>
</div>
<script type="text/javascript">
var productTypes, errMsg, errMsg2, errMsg3;

$(document).ready(function(){
	errMsg = '<?=__('You cannot enter quantity more than %s items', '~n')?>';
	errMsg2 = '<?=__('You must enter quantity')?>';
	errMsg3 = '<?=__('All devices are distributed')?>';
	DeviceManage.init(<?=$order['Order']['id']?>, <?=json_encode($aProductTypeOptions)?>);
});

</script>

<script type="text/x-tmpl" id="distributed-products">
{%
	var prodType = o.productType.ProductType;
%}
<div class="device-items">
    <span class="icon glyphicons {%=prodType.icon%}"></span>
    <div class="groupAccess clearfix">
{%
	var blockedHtml = '';
	for(var i = 0; i < o.products[prodType.id].length; i++) {
		var product = o.products[prodType.id][i];
		var user = o.users[product.OrderProduct.user_id];
		if (product.OrderProduct.blocked) {
			blockedHtml+= tmpl('product', {product: product, user: user});
		} else {
			include('product', {product: product, user: user});
		}
	}
%}
	</div>
{%
	if (blockedHtml) {
%}
	<div class="groupAccess groupBanned clearfix">
		{%#blockedHtml%}
	</div>
{%
	}
%}
</div>
</script>

<script type="text/x-tmpl" id="product">
{%
	icon = (o.product.OrderProduct.blocked) ? 'ok' : 'ban';
	//onclick = (o.product.OrderProduct.blocked) ? ''
%}
	<div class="item clearfix">
	    <img class="ava blockLine" src="{%=o.user.UserMedia.url_img.replace(/noresize/, 'thumb100x100')%}" alt="" />
	    <div class="info">
	        <span class="name">{%=o.user.User.full_name%}</span>
	        <span class="position">id: {%=o.product.Product.serial%}</span>
	    </div>
<?
	if ($canDistribute) {
?>
	    <div class="buttonsControls">
	        <div class="accept" onclick="DeviceManage.block({%=o.product.OrderProduct.product_id%}, {%=(!o.product.OrderProduct.blocked)%})"><span class="glyphicons {%=icon%}"></span></div>
	        <div class="remove" onclick="DeviceManage.giveBack({%=o.product.OrderProduct.product_id%})"><span class="glyphicons bin"></span></div>
	    </div>
<?
	}
?>
	</div>
</script>
