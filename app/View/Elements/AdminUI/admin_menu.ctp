<?php
$aNavBar = array(
	'Billing' => array(
		'label' => __('Billing'),
		'href' => '#',
		'children' => array(
			'Subscriptions' => array(
				'label' => __('Subscriptions'),
				'href' => array('plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'index')
			),
			'Groups' => array(
				'label' => __('Groups'),
				'href' => array('plugin' => 'billing', 'controller' => 'billing_groups', 'action' => 'index')
			),
			'Plans' => array(
				'label' => __('Plans'),
				'href' => array('plugin' => 'billing', 'controller' => 'billing_plans', 'action' => 'index')
			),
		)
	),
	/*
	'Products' => array(
		'label' => __('Products'),
		'href' => '#',
		'children' => array(
			'Types' => array(
				'label' => __('Product types'),
				'href' => array('controller' => 'AdminProductTypes', 'action' => 'index')
			),
			'Products' => array(
				'label' => __('Products'),
				'href' => array('controller' => 'AdminProducts', 'action' => 'index')
			),
		)
	),
	'Contractors' => array(
		'label' => __('Contractors'),
		'href' => array('controller' => 'AdminContractors', 'action' => 'index')
	),
	'Orders' => array(
		'label' => __('Orders'),
		'href' => '',
		'children' => array(
			'OrderList' => array(
				'label' => __('Orders list'),
				'href' => array('controller' => 'AdminOrders', 'action' => 'index')
			),
			'OrderPayments' => array(
				'label' => __('Order payments'),
				'href' => array('controller' => 'AdminOrders', 'action' => 'process')
			),
	)),
	'Faq' => array(
		'label' => __('FAQ'),
		'href' => array('controller' => 'AdminFaq', 'action' => 'index')
	),
	*/
);
?>
<ul class="nav" id="side-menu">
    <li>
        <a href="<?php echo $this->Html->url(array('admin' => true, 'plugin' => false, 'controller' => 'dashboard', 'action' => 'index'))?>">
			<i class="fa fa-dashboard fa-fw"></i> Dashboard
		</a>
    </li>
	<?php
		foreach($aNavBar as $id => $item) {
			$label = $item['label'];
			$url = isset($item['href']) ? $item['href'] : '#';
			if (isset($item['children'])) {
				$label.= $this->Html->tag('span', '', array('class' => 'fa arrow'));

			}
			$link = $this->Html->link($label, $url, array('escape' => false));
			$submenu = '';
			if (isset($item['children'])) {
				foreach($item['children'] as $_item) {
					$submenu[] = $this->Html->tag('li', $this->Html->link($_item['label'], $_item['href'], array('escape' => false)));
				}
				$submenu = $this->Html->tag('ul', implode("\n", $submenu), array('class' => 'nav nav-second-level'));
			}
			echo $this->Html->tag('li', $link.$submenu);
		}
	?>
</ul>
