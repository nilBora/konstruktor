<?
App::uses('AppModel', 'Model');
class Order extends AppModel {
	
	public $hasMany = array('OrderType');
	
	public function timelineEvents($currUserID, $date, $date2) {
		$conditions = $this->dateRange('Order.created', $date, $date2);
		$conditions['Order.contractor_id'] = $currUserID;
		return $this->find('all', compact('conditions'));
	}
}
