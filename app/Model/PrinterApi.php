<?
App::uses('AppModel', 'Model');
class PrinterApi extends AppModel {
	public $useTable = false;
	
	public function getCounters($aIP) {
		$server = (TEST_ENV) ? '54.148.50.109' : $_SERVER['SERVER_NAME'];
		$query = 'http://'.$server.':7777/getppc?pool=1000';
		foreach($aIP as $ip) {
			$query.= '&ip[]='.$ip;
		}
		
		$response = @file_get_contents($query);
		if (!$response) {
			throw new Exception(__('No response from Printer API'));
		}
		
		$response = json_decode($response, true);
		if (!is_array($response) || !isset($response['result'])) {
			throw new Exception(__('Bad response from Printer API'));
		}
		
		if ($response['result'] != 'ok') {
			if (isset($response['err_msg'])) {
				throw new Exception($response['err_msg']);
			} else {
				throw new Exception(__('Bad response from Printer API'));
			}
		}
		
		if (!isset($response['data'])) {
			throw new Exception(__('Bad response from Printer API'));
		}
		
		$result = array();
		foreach($response['data'] as $data) {
			$result[$data['address']] = $data['printed'];
		}
		return $result;
	}
}
