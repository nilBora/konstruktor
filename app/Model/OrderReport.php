<?
App::uses('AppModel', 'Model');
App::uses('OrderProduct', 'Model');
class OrderReport extends AppModel {
	
	protected $OrderProduct;
	
	public function calcPayment($orderData) {
		$this->loadModel('OrderProduct');
		
		$orderID = $orderData['Order']['id'];
		$endPeriod = time();
		$startPeriod = strtotime($this->_getStartPaymentDate($orderID, $orderData['Order']['created']));
		$period_count = $this->_getPeriodsCount($startPeriod, $endPeriod, $orderID);
		$aData = array();
		if ($period_count) {
			$orderProducts = $this->OrderProduct->getOrderProducts($orderID);
			foreach($orderProducts as $product) {
				$lPrinter = ($product['ProductType']['id'] == Configure::read('device.typePrinter'));
				$data = array(
					'contractor_id' => $orderData['Order']['contractor_id'],
					'order_id' => $orderID,
					'period_count' => ($lPrinter) ? 1 : $period_count, // для принтера - берем по факту
					'product_type_id' => $product['ProductType']['id'],
					'product_id' => $product['Product']['id'],
					'qty' => ($lPrinter) ? '-' : 1,
					'price' => $product['ProductType']['arenda_price']
				);
				if ($lPrinter) {
					$data['Printer'] = $product['Product'];
				}
				
				$aData[] = $data;
			}
		}
		return $aData;
	}

	private function _getStartPaymentDate($order_id, $created) {
		return $created;
	}
	
	private function _getPeriodsCount($startPeriod, $endPeriod, $orderID = null) {
		return floor(($endPeriod - $startPeriod) / MONTH);
	}
	
}
