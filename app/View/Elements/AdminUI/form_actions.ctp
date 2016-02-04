<?php
$_actions = array('save', 'cancel');

if (isset($actions)||!empty($actions)) {
	$actions = array_merge($_actions, $actions);
} else {
	$actions = $_actions;
}
if (!isset($sticky)) $sticky = false;

$html = '';
if(in_array('apply', $actions)){
	$html .= $this->Form->submit(__('Save changes'), array(
		'name' => 'apply',
	    'div' => false,
	    'class' => 'btn btn-sm btn-primary',
	));
	$html .= "&nbsp;";
}

if(in_array('save', $actions)){
	$html .= $this->Form->submit(__('Save and Close'), array(
		'name' => 'save',
	    'div' => false,
	    'class' => 'btn btn-sm btn-success',
	));
	$html .= "&nbsp;";
}

if(in_array('savenew', $actions)){
	$html .= $this->Form->submit(__('Save and Add new'), array(
		'name' => 'savenew',
	    'div' => false,
	    'class' => 'btn btn-sm btn-warning',
	));
	$html .= "&nbsp;";
}

if(in_array('cancel', $actions)){
	$html .= $this->Html->link(__('Cancel'),
		array('action' => 'index'),
		array('class' => 'btn btn-sm btn-default')
	);
}

$html = $this->Html->div('btn-group form-actions pull-right', $html."&nbsp;");

echo $html;
unset($html);
?>

