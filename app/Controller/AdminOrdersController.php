<?php
App::uses('AdminController', 'Controller');
class AdminOrdersController extends AdminController {
	public $name = 'AdminOrders';
	public $components = array('Auth', 'Table.PCTableGrid');
	public $uses = array('Order', 'OrderType', 'Contractor', 'ProductType', 'OrderProduct', 'OrderReport', 'PrinterApi', 'Balance', 'Product');
	
	public function index() {
		$this->paginate = array(
			'fields' => array('id', 'created', 'period', 'paid', 'shipped')
		);
		$this->PCTableGrid->paginate('Order');
	}
	
	public function edit($id = 0) {
		$aFlags = array('paid', 'shipped');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if (is_array($this->request->data('Order.status'))) {
				foreach($aFlags as $field) {
					$_field = 'Order.'.$field;
					$this->request->data($_field, in_array($field, $this->request->data('Order.status')));
				}
			}
			if ($this->Order->save($this->request->data)) {
				$id = $this->Order->id;
				$baseRoute = array('action' => 'index');
				return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
			}
		} elseif ($id) {
			$row = $this->Order->findById($id);
			$this->request->data = $row;
		} else {
			$this->request->data('Order.period', 0);
		}
		
		if ($id) {
			$status = array();
			foreach($aFlags as $field) {
				if ($this->request->data('Order.'.$field)) {
					$status[] = $field;
				}
			}
			$this->request->data('Order.status', $status);
			
			$this->set('aProducts', $this->OrderProduct->getOrderProducts($id));
		}
		
		$this->set('aContractorOptions', $this->Contractor->find('list'));
		$this->set('aProductTypeOptions', $this->ProductType->find('list'));
	}
	
	public function process() {
		$aData = array();
		try {
			// Получить данные на списание по заказам
			$orders = $this->Order->find('all');
			foreach($orders as $order) {
				$orderID = $order['Order']['id'];
				$aData[$orderID]['Order'] = $order['Order'];
				$aData[$orderID]['Total'] = array();
				$aData[$orderID]['Details'] = $this->OrderReport->calcPayment($order);
			}
			
			// Получить данные о счетчиках по всем принтерам сразу
			$aPrintersIP = Hash::extract($aData, '{n}.Details.{n}.Printer.ip');
			if ($aPrintersIP) {
				$printerCounters = $this->PrinterApi->getCounters($aPrintersIP);
			}
			
			// Подготовка данных и списание на основе полученных данных
			foreach($aData as $orderID => &$orderData) {
				
				// Подготовка данных и получение общей суммы по заказу
				$totalOrder = 0;
				foreach($orderData['Details'] as &$data) {
					if (isset($data['Printer'])) {
						$ip = $data['Printer']['ip'];
						$product_id = $data['Printer']['id'];
						$prev_counter = $data['Printer']['prev_counter'];
						$data['counter'] = (isset($printerCounters[$ip])) ? $printerCounters[$ip] : $prev_counter;
						$data['qty'] = $data['counter'] - $prev_counter;
					}
					$sum = $data['qty'] * $data['price'] * $data['period_count'];
					$totalOrder+= $sum;
				}
				$orderData['Total'] = $totalOrder;
				
				// Списание ср-в и корректировка счетчиков
				if ($totalOrder) {
					$this->Balance->getDataSource()->begin();
					
					$user_id = $orderData['Order']['contractor_id'];
					$oper_id = $this->Balance->change($user_id, $totalOrder, Balance::DEBIT, 'Charge payment for order '.$orderID.' (System)');
					foreach($orderData['Details'] as $data) {
						$this->OrderReport->save($data);
						if (isset($data['Printer'])) {
							// корректируем счетчик
							$this->Product->save(array('id' => $data['product_id'], 'prev_counter' => $data['counter']));
						}
					}
					
					$this->Balance->getDataSource()->commit();
				}
			}
		
		} catch (Exception $e) {
			$this->Balance->getDataSource()->rollback();
			$aData = array();
			$this->Session->setFlash($e->getMessage(), 'default', array(), 'error');
		}
		$this->set('aData', $aData);
		
		$aID = Hash::extract($aData, '{n}.Order.contractor_id');
		$aContractors = $this->Contractor->findAllById($aID);
		$this->set('aContractorOptions', Hash::combine($aContractors, '{n}.Contractor.id', '{n}.Contractor'));
	}
}
