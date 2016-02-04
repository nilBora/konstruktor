<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(__('Devices').': '.__('New order'), array('controller' => 'Device', 'action' => 'checkout'));
?>

<style type="text/css">
.device-lists .device-list-cell:last {border-bottom: medium none;}
</style>
<?=$this->Form->create('Contractor', array('class' => 'contactForm'))?>
<div class="device-lists col-md-10 col-sm-10 col-xs-12">
<?
    $aItems = array(
        1 => __('mob.spots'),
        4 => __('stat.spots'),
        5 => __('print copies')
    );
    $total = 0;
    foreach($aProductTypes as $i => $productType) {
        $qty = $this->request->data($i.'.ProductType.qty');
        if ($qty || $qty == 0) {
            if ($productType['ProductType']['id'] != Configure::read('device.typePrinter')) {
                $total += intval($qty) * intval($productType['ProductType']['arenda_price']);
            }
?>
    <div class="device-list-cell">
        <div class="device-list-h clearfix">
            <figure class="row col-md-2 col-sm-2 col-xs-12">
                <?=$this->element('product_image', $productType['ProductType'])?>
                <span class="size">x<?=$qty?></span>
            </figure>
            <div class="description col-md-10 col-sm-10 col-xs-12">
                <p>
                    <?=$productType['ProductType']['descr']?>
                </p>
            </div>
        </div>
        <div class="device-list-b">
            <div class="min-size"><?=__('Minimal quantity')?> <br/> <?=$productType['ProductType']['min_qty']?> <?=$aItems[$productType['ProductType']['id']]?></div>
            <div class="devise-item-size clearfix">
                <div class="box-input">
                    <input type="hidden" name="data[OrderType][<?=$i?>][product_type_id]" value="<?=$productType['ProductType']['id']?>" />
                    <input type="text" class="checkQty" name="data[OrderType][<?=$i?>][qty]" value="<?=$qty ? $qty : '0'?>" data-min_qty="<?=$productType['ProductType']['min_qty']?>" />
                </div>
                <div class="price-month">
                    <span class="<?=($productType['ProductType']['id'] == Configure::read('device.typePrinter')) ? '' : 'priceMonth'?>"><?=$this->element('arenda_price', $productType['ProductType'])?></span>
                </div>
                <div class="remove">
                    <a class="remove-link" href="#" onclick="if ($('.device-list-cell').length > 1) { $(this).closest('.device-list-cell').remove(); }"><?=__('Remove')?></a>
                </div>
            </div>
        </div>
    </div>
<?
        }
    }
?>
    <div class="lease-terms clearfix">
        <div class="select-year">
            <label for=""><?=__('Rent period')?></label>
        <!--
            <label for="">Срок аренды</label>
            <select class="formstyler" name="" id="drop-device-info-select-1">
                <option  value="0">2 года</option>
                <option value="1">3 года</option>
                <option value="2">4 года</option>
                <option value="3">5 лет</option>
                <option selected="selected" value="4">6 лет</option>
            </select>
        -->
<?
    $fieldName = 'Order.period';
    $options = array('name' => 'data[Order][period]');
    echo $this->element('select_period', compact('fieldName', 'options'));
?>
                <span class="end-price">
                    <?=$this->element('sum', array('sum' => $total))?>/<?=__('month')?>
                </span>
        </div>
        <!--div class="info-block">
            <span>*</span> При условии аренды на 5 лет
        </div-->
    </div>
</div>



<div class="device-order-form col-md-10 col-sm-10 col-xs-12">
<?
    echo $this->Form->input('Contractor.contact_person', array(
        'div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'label' => array('text' => __('Contact person')),
        'placeholder' => __('Contact person').'...'
    ));
    echo $this->Form->input('Contractor.email', array(
        'div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'type' => 'text',
        'placeholder' => __('Email').'...'
    ));
    echo $this->Form->input('Contractor.phone', array(
        'div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'type' => 'text',
        'placeholder' => __('Contractor phone').'...',
        'style' => 'max-width:274px'
    ));
    echo $this->Form->input('Contractor.title', array(
        'div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'label' => array('text' => __('Contractor name')),
        'placeholder' => __('Contractor name').'...'
    ));
    echo $this->Form->input('Contractor.details', array(
        'div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'type' => 'text',
        'label' => array('text' => __('Contractor details')),
        'placeholder' => __('Contractor details').'...'
    ));
?>
<script type="text/javascript">
$(document).ready(function(){
    $("#ContractorPhone").mask("+9(999) 999-9999");
    $(".checkQty").mask("9?9999");
});
</script>
        <div class="form-group nbb">
            <button type="button" class="btn btn-primary"><?=__('Create order')?></button>
            <!-- p class="help-block">На вашем счету недостаточно средств</p -->
        </div>
</div>
<?=$this->Form->end()?>

<script type="text/javascript">
var maxQty;

function checkQty(e) {

	var total = 0, sum = 0;
	var min_qty = $(e).data('min_qty');

	$('.devise-item-size').each(function(){
		input_val =	parseInt($(e).val());
		if (input_val >= min_qty && input_val <= maxQty) {
		sum = ($('.priceMonth .sum', this).length) ? parseFloat($('.priceMonth .sum', this).text().replace(/\$/, '')) : 0;
		total+= sum * parseFloat($('input.checkQty', this).val());
	}
	});
	$('.end-price .sum').html('$' + total);

	if (input_val < min_qty || input_val > maxQty) {
		alert('<?=__('Min.qty')?>: ' + min_qty + '<?=__('items')?>\n<?=__('Max.qty')?>: ' + maxQty + '<?=__('items')?>');
		$(e).focus();
		$(e).select();
		return false;
	}

	return true;
}

function checkContractor() {
    if ($('#ContractorEmail').val().indexOf('@') < 0) {
        alert('<?=__('Incorrect Contractor email')?>');
        $('#ContractorEmail').focus();
        return false;
    }

    if (!$('#ContractorTitle').val()) {
        alert('<?=__('Please, enter Contractor data')?>');
        $('#ContractorTitle').focus();
        return false;
    }

    if (!$('#ContractorContactPerson').val()) {
        alert('<?=__('Please, enter Contractor data')?>');
        $('#ContractorContactPerson').focus();
    }
    return true;
}

$(document).ready(function(){
    maxQty = <?=Configure::read('device.maxQty')?>;

    $('.checkQty').change(function(){
        checkQty(this);
    });

    $('#ContractorCheckoutForm .btn-primary').click(function(){
        var lChecked = true;
        $('.checkQty').each(function(){
            if (lChecked) {
                lChecked = checkQty(this);
            }
        });
        if (lChecked && $('.checkQty').length && checkContractor()) {
            $('#ContractorCheckoutForm').submit();
        }
    });
});
</script>
