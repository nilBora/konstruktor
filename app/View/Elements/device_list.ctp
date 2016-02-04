<form id="devicePanelForm" action="<?=$this->Html->url(array('controller' => 'Device', 'action' => 'checkout'))?>" method="post">
		<ul class="drop-device-list">
<?

	foreach($aDevices as $i => $device) {
?>
            <li class="drop-device-list-cell clearfix">
            	<input type="hidden" name="data[<?=$i?>][ProductType][id]" value="<?=$device['ProductType']['id']?>" />
                <figure class="drop-device-icon">
                	<?=$this->element('product_image', $device['ProductType'])?>
                </figure>
                <div class="drop-device-info">
                    <div class="title"><?=$device['ProductType']['title']?></div>
                    <div class="text-descript">
                        <p>
                        	<?=$device['ProductType']['teaser']?>
                        </p>
                        <div class="month-amount"><?=$this->element('arenda_price', $device['ProductType'])?></div>
                        <div class="select-option clearfix">
                            <div class="box-input" style="margin-top: 10px;">
                                <input type="text" class="deviceQty" name="data[<?=$i?>][ProductType][qty]" value="0" />
                            </div>
                            <!--div class="box-select page-menu">
<?
	$fieldName = 'period_'.$i;
	$options = array('name' => 'data['.$i.'][Order][period]');
	echo $this->element('select_period', compact('fieldName', 'options'));
?>
                            </div-->
                        </div>
                    </div>
                </div>
            </li>
<?
	}
?>
        </ul>
</form>


<script type="text/javascript">
	CheckDevices = function() {
		allow = false;
		var fields = $('.deviceQty');

		for(var i=0; i<fields.length; i++) {
			if( $(fields[i]).val() != '0' && $(fields[i]).val().length > 0 )
				allow = true;
		}

		if(allow) {
			$('.order-device').removeClass('disabled');
		} else {
			$('.order-device').removeClass('disabled').addClass('disabled');
		}
	}

	$('.deviceQty').on('keyup keydown keypress change', function() {

		// Backspace, tab, enter, end, home, left, right
		// We don't support the del key in Opera because del == . == 46.
		var controlKeys = [8, 9, 13, 35, 36, 37, 39];
		// IE doesn't support indexOf
		var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
		// Some browsers just don't raise events for control keys. Easy.
		// e.g. Safari backspace.
		if (!event.which || // Control keys in most browsers. e.g. Firefox tab is 0
			(49 <= event.which && event.which <= 57) || (96 <= event.which && event.which <= 105) ||  // Always 1 through 9
			(48 == event.which && $(this).attr("value")) || // No 0 first digit
			isControlKey) { // Opera assigns values for control keys.
				CheckDevices();
				return;
		} else {
			event.preventDefault();
		}

	})
</script>
