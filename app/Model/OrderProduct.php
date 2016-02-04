<?
App::uses('AppModel', 'Model');
App::uses('Product', 'Model');
class OrderProduct extends AppModel {
	
	public $belongsTo = array('Product');
	/*
	public $hasMany = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id'
		)
	);
	*/
	
	public function getOrderProducts($orderID, $lDistributed = null) {
		App::import('Model', 'Product');
		$this->Product = new Product();
		
		$conditions = array('order_id' => $orderID);
		if (!is_null($lDistributed)) {
			$conditions['user_id > '] = intval($lDistributed);
		}
		$orderProducts = $this->find('all', compact('conditions'));
		$aProductID = Hash::extract($orderProducts, '{n}.OrderProduct.product_id');
		return $this->Product->find('all', array(
			'conditions' => array('Product.id' => $aProductID), 
			'order' => array('Product.product_type_id', 'Product.serial')
		));
	}
	
	public function getOrderProductsByTypes($orderID, $lDistributed = null) {
		$aProducts = $this->getOrderProducts($orderID, $lDistributed);
		
		$aProductTypes = array();
		foreach($aProducts as $product) {
			$product_type_id = $product['ProductType']['id'];
			if (!isset($aProductTypes[$product_type_id])) {
				$aProductTypes[$product_type_id]['ProductType'] = $product['ProductType'];
				$aProductTypes[$product_type_id]['ProductType']['qty'] = 1;
			} else {
				$aProductTypes[$product_type_id]['ProductType']['qty']++;
			}
			$aProductTypes[$product_type_id]['Products'][] = $product['Product'];
		}
		return $aProductTypes;
	}
	
	public function getByTypes($orderID, $lDistributed = null) {
		$conditions = array('order_id' => $orderID);
		if (!is_null($lDistributed)) {
			if ($lDistributed) {
				$conditions['user_id > '] = 0;
			} else {
				$conditions['user_id = '] = 0;
			}
		}
		$orderProducts = $this->find('all', compact('conditions'));
		$aProductTypes = array();
		foreach($orderProducts as $product) {
			$product_type_id = $product['Product']['product_type_id'];
			$aProductTypes[$product_type_id][] = $product;
		}
		return $aProductTypes;
	}
	
	public function timelineEvents($currUserID, $date, $date2) {
		$conditions = $this->dateRange('OrderProduct.distrib_date', $date, $date2);
		$conditions['OrderProduct.user_id'] = $currUserID;
		return $this->find('all', compact('conditions'));
	}
}
