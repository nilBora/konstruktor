<?
App::uses('AppModel', 'Model');
class Balance extends AppModel {
	public $useTable = 'balance_operations';
	
	const CREDIT = 1; // inc balance
	const DEBIT = 2; // dec balance
	
	public $belongsTo = 'User';

	public function change($user_id, $sum, $oper_type, $descr = '') {
		$oper_id = 0;
		try {
			$this->getDataSource()->begin();
			$sum = ($oper_type == self::DEBIT) ? -abs($sum) : abs($sum);
			$this->clear();
			$this->save(compact('user_id', 'oper_type', 'sum', 'descr'));
			$oper_id = $this->id;
			
			$balance = $this->User->getBalance($user_id);
			$this->User->save(array('id' => $user_id, 'balance' => $balance + $sum));
			
			$this->getDataSource()->commit();
		} catch (Exception $e) {
			$this->getDataSource()->rollback();
			return false;
		}
		return $oper_id;
	}
}