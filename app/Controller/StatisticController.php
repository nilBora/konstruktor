<?php
App::uses('AppController', 'Controller');

/**
 * Class StatisticController
 * @property Statistic Statistic
 */
class StatisticController extends AppController {

	public $name = 'Statistic';
	public $layout = 'profile_new';
	public $components = array('RequestHandler');

	public function index() {

	}

	public function data() {
		$period = $this->request->data('period');
		$from = $this->request->data('from');
		$to = $this->request->data('to');
		$data = array(
			'profile' => $this->Statistic->profileData($this->currUserID, $period, $from, $to),
			'groups' => $this->Statistic->groupsData($this->currUserID, $period, $from, $to),
			'articles' => $this->Statistic->articlesData($this->currUserID, $period, $from, $to),
		);
		$this->set(compact('data'));
		$this->set('_serialize', array('data'));
	}

    public function getStats() {
        if($this->request->is('post')){
            $stats = json_decode(file_get_contents("php://input"));
            $startDate = $stats->start_date;
            $endDate = $stats->end_date;
            $this->loadModel('Profile');
            $this->loadModel('Group');
            $this->loadModel('Article');
            $data = $this->Statistic->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'profiles',
                        'alias' => 'Profile',
                        'type' => 'left',
                        'foreignKey' => true,
                        'conditions' => array(
                            'Statistic.type'=>0,
                        )
                    ),
                    array(
                        'table' => 'groups',
                        'alias' => 'Group',
                        'type' => 'left',
                        'foreignKey' => true,
                        'conditions' => array(
                            'Statistic.type'=>2,
                        )
                    ),
                    array(
                        'table' => 'articles',
                        'alias' => 'Article',
                        'type' => 'left',
                        'foreignKey' => true,
                        'conditions' => array(
                            'Statistic.type'=>1,
                        )
                    ),
                ),
                'conditions' => array(
                    'Statistic.created >' => $startDate,
                    'Statistic.created <' => $endDate,
                ),
                'fields' => array(
                    'Statistic.*',
                    'Group.*',
                    'Profile.*',
                    'Article.*',
                ),
                'group' => array('Statistic.id')
            ));
            $profiles = Array();
            $groups=Array();
            $articles=Array();
            foreach($data as $key=>$value) {
                $count = 0;
                if ($value['Statistic']['type'] == '0') {

                    foreach ($data as $key2 => $value2) {
                        if ($value['Statistic']['pk'] == $value2['Profile']['id'] && $value['Statistic']['created'] == $value2['Statistic']['created']) {
                            $count = $count + 1;
                        }
                    }
                    $profiles[] = array(
                        'datetime' => $value['Statistic']['created'],
                        'views' => $count
                    );
                } elseif ($value['Statistic']['type'] == '1') {
                    foreach ($data as $key2 => $value2) {
                        if ($value['Statistic']['pk'] == $value2['Article']['id'] && $value['Statistic']['created'] == $value2['Statistic']['created']) {
                            $count = $count + 1;
                        }
                    }
                    $articles[] = array(
                        'datetime' => $value['Statistic']['created'],
                        'views' => $count
                    );

                } elseif ($value['Statistic']['type'] == '2') {
                    foreach ($data as $key2 => $value2) {
                        if ($value['Statistic']['pk'] == $value2['Group']['id'] && $value['Statistic']['created'] == $value2['Statistic']['created']) {
                            $count = $count + 1;
                        }
                    }
                    $groups[] = array(
                        'datetime' => $value['Statistic']['created'],
                        'views' => $count
                    );

                }
            }
            $response['Profile'] = $profiles;
            $response['Article'] = $articles;
            $response['Group'] = $groups;
            $response = json_encode($response);
            echo $response;
        }
    }
}