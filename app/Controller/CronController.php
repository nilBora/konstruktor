<?
class CronController extends AppController {

	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->layout=null;
	}

	public function mailer() {
		$this->loadModel('User');
		// Check the action is being invoked by the cron dispatcher
		if (!defined('CRON_DISPATCHER')) { $this->redirect('/Mytime'); exit(); }

		//no view
		//$this->autoRender = false;

		$this->layout = 'ajax';

		$aUser = $this->User->find('all');
		foreach($aUser as $user) {
			$currTime = time();
			$lastTime = strtotime(Hash::get($user, 'User.last_update'));

			$id = $user['User']['id'];
			$date1 = $lastTime > ($currTime - 43200) ? date("Y-m-d H:i:s", $lastTime) : date("Y-m-d H:00:00", $currTime - 43200);
			$date2 = date("Y-m-d H:00:00", $currTime);
			$data = $this->User->getTimeline($id, $date1, $date2, 0, true);

			$this->set('data', $data);
			$this->set('timeFrom', strtotime($date1));
			$this->set('timeTo', strtotime($date2));

			if(count($data['events'])) {
				
				$Email = new CakeEmail('postmark');
				$Email->template('updates_mailer', 'mail')->viewVars( array('data' => $this->User->getTimeline($id, $date1, $date2, 0), 'timeFrom' => strtotime($date1), 'timeTo' => strtotime($date2)))
					->to(Hash::get($user, 'User.username'))
					->subject('Last updates on Konstruktor.com')
					->send();
			}
		}

		return;
	}
}
