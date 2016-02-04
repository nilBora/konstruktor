<?php

if (isset($id)&&!empty($id)){

	if (!isset($actions)||empty($actions)) $actions = array('view', 'edit', 'delete');

	$_actions = array();
	foreach ($actions as $key=>$action){
		if (is_array($action)){
			$_actions[$key] = $action;
		} else {
			$_actions[$action] = array();
		}
	}
	$actions = $_actions;
	$url = array();
	if(isset($plugin)&&($plugin != false)) $url['plugin'] = $plugin;
	if(isset($plugin)&&($controller != false)) $url['controller'] = $controller;

	$buttons = '';
	if(array_key_exists('moveup', $actions)){
		$buttons .= $this->Html->link('',
			$url + array('action' => 'moveup', $id),
			array('class' => 'btn btn-sm btn-default', 'icon' => 'arrow-up', 'title'=>__('Move up'))
		);
	}
	if(array_key_exists('movedown', $actions)){
		$buttons .= $this->Html->link('',
			$url + array('action' => 'movedown', $id),
			array('class' => 'btn btn-sm btn-default', 'icon' => 'arrow-down', 'title'=>__('Move down'))
		);
	}
	if(array_key_exists('state', $actions)){
		$buttons .= $this->Html->stateLink($state,
			$url + array('action' => 'state', $id),
			array('class' => 'btn btn-sm btn-default', 'title'=>__('Change state'))
		);
	}
	if(array_key_exists('view', $actions)){
		$buttons .= $this->Html->link('',
			$url + array('action' => 'view', $id),
			array_merge(array('class' => 'btn btn-sm btn-primary', 'icon'=>'eye', 'title'=>__('View')), $actions['view'])
		);
	}
	if(array_key_exists('edit', $actions)){
		$buttons .= $this->Html->link('',
			$url + array('action' => 'edit', $id),
			array_merge(array('class' => 'btn btn-sm btn-warning', 'icon'=>'edit', 'title'=>__('Edit')), $actions['edit'])
		);
	}
	if(array_key_exists('delete', $actions)){
		$buttons .= $this->Html->link('',
			$url + array('action' => 'delete', $id, 'ext' => 'json'),
			array_merge(
				array(
					'class' => 'btn btn-sm btn-danger confirm-delete',
					'icon'=>'trash-o',
					'title'=>__('Delete'),
					'data-placeholder' => __('Are you sure you want to delete # %s?', $id)
				),
				$actions['delete']
			)
		);
	}
	echo $this->Html->div('btn-group', $buttons);

}
