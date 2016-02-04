<?php
App::uses('ConnectionManager', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('AppShell', 'Console/Command');
App::uses('Folder', 'Utility');
//$dataSource = ConnectionManager::getDataSource('default');

class MailerShell extends AppShell {
    public $uses = array('User');

	public function main() {
		$aUser = $this->User->find('all');

		foreach($aUser as $user) {
			$currTime = time();
			$lastTime = strtotime(Hash::get($user, 'User.last_update'));

			//TODO дата с нынешней до last_update
			//если last_update давнее 12 часов, то брать последние 12 часов
			$id = $user['User']['id'];
			$date1 = $lastTime > ($currTime - 43200) ? date("Y-m-d H:i:s", $lastTime) : date("Y-m-d H:00:00", $currTime - 43200);
			$date2 = date("Y-m-d H:i:s");
			$data = $this->User->getTimeline($id, $date1, $date2, 0, true);

			//$this->set('data', $data);
			//$this->set('timeFrom', strtotime($date1));
			//$this->set('timeTo', strtotime($date2));

			if(count($data['events'])) {
				Debugger::dump($user['User']['id'].' '.$user['User']['full_name'].'     from: '.$date1.'     to: '.$date2);
				//Debugger::dump($data['events']);

				/*
				$Email = new CakeEmail('postmark');
				$Email->template('updates_mailer', 'mail')->viewVars( array('data' => $data, 'timeFrom' => strtotime($date1), 'timeTo' => strtotime($date2), 'userID' => $user['User']['id']))
					->to(Hash::get($user, 'User.username'))
					->subject('Last updates on Konstruktor.com')
					->send();
				*/
			}
		}
	}
}
