<?php
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');
class DeviceController extends SiteController {
    public $name = 'Device';
    public $layout = 'device';
    public $uses = array('User', 'ProductType', 'Contractor', 'Order', 'OrderType', 'OrderProduct');

    public function beforeRender() {
        $this->set('balance', $this->User->getBalance($this->currUserID));
        $this->set('PU_', '$');
        $this->set('_PU', '');

        $this->set('pageTitle', $this->pageTitle);
    }

    public function index() {
        $this->redirect(array('controller' => $this->name, 'action' => 'orders'));
    }

    public function checkout($productTypeID = 1) {

        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data('Contractor')) {
                $this->request->data('Contractor.id', $this->currUserID);
                $this->request->data('Order.contractor_id', $this->currUserID);
                if ($this->Contractor->save($this->request->data('Contractor'))) {
                    // Save order
                    $this->Order->saveAll($this->request->data);
                    return $this->redirect(array('action' => 'orders'));
                }
            } else { // get data prom panel

            }
        }

		//get User information about bay device
		// if (!$this->request->data('Contractor')) {
		// 	$contractor = $this->Contractor->findById($this->currUserID);
		// 	if ($contractor) {
		// 		$this->request->data('Contractor', $contractor['Contractor']);
		// 	}
		// }

		$this->set('aProductTypes', $this->ProductType->find('all'));
		$this->pageTitle = __('New order');
	}

    public function orders() {
        $conditions = array('contractor_id' => $this->currUserID);
        $order = 'Order.created DESC';
        $aOrders = $this->Order->find('all', compact('conditions', 'order'));
        $this->set('aOrders', $aOrders);
        $aProductTypeOptions = $this->ProductType->find('all');
        $this->set('aProductTypeOptions', Hash::combine($aProductTypeOptions, '{n}.ProductType.id', '{n}'));

        $aID = Hash::extract($aOrders, '{n}.Order.id');
        $aRowset = $this->OrderProduct->findAllByOrderId($aID);
        $aDistrib = array();
        foreach($aRowset as $row) {
            $order_id = $row['OrderProduct']['order_id'];
            $product_type_id = $row['Product']['product_type_id'];
            if (intval($row['OrderProduct']['user_id'])) {
                $aDistrib[$order_id][$product_type_id][] = $row;
            }
        }
        $this->set('aDistrib', $aDistrib);

        $aID = Hash::extract($aRowset, '{n}.OrderProduct.user_id');
        $aUsers = $this->User->getUsers($aID);
        $this->set('aUsers', $aUsers);

        $this->pageTitle = __('My orders');
    }

    public function view($id) {
        $order = $this->Order->findById($id);
        $this->set('order', $order);

        $this->set('canDistribute', $order['Order']['contractor_id'] == $this->currUserID);
        /*
        $aProductTypeOptions = $this->ProductType->find('all');
        $this->set('aProductTypeOptions', Hash::combine($aProductTypeOptions, '{n}.ProductType.id', '{n}'));
        */
        $aProductTypes = $this->OrderProduct->getOrderProductsByTypes($id);

        /* $aProductTypes = array();
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
        */
        $this->set('aProductTypes', $aProductTypes);

        $aDistributedProducts = $this->OrderProduct->getByTypes($id, true);
        $this->set('aDistributedProducts', $aDistributedProducts);

        $aID = Hash::extract($aDistributedProducts, '{n}.{n}.OrderProduct.user_id');
        $aUsers = $this->User->getUsers($aID);
        $this->set('aUsers', $aUsers);

        $aProductTypeOptions = $this->ProductType->find('all');
        $this->set('aProductTypeOptions', $this->ProductType->options());

        $s = sprintf('%09d', $id);
        $order_num = substr($s, 0, 3).'-'.substr($s, 3, 3).'-'.substr($s, 6, 3);
        $this->pageTitle = __('View order').' '.$order_num;
    }
}
