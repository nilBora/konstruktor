<?php

class ServiceShell extends AppShell {
	/**
	 * Connection used
	 *
	 * @var string
	 */
	public $connection = 'default';

	public $uses = array('User', 'Group');

	public function startup() {
		Configure::write('debug', 2);

		$this->out(__d('cake_console', '<warning>Konstruktor Service Shell</warning>'));
		$this->hr(0, 100);

		if (!config('database')) {
			$this->out('<warning>'.__d('cake_console', 'Your database configuration was not found in .env files.').'</warning>');
		}

		if (!empty($this->params['connection'])) {
			$this->connection = $this->params['connection'];
		}

		if (!empty($this->params['plugin'])) {
			$this->type = $this->params['plugin'];
		}
	}

	/**
	 * Display help/options
	 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__d('cake_console', 'Cake Service Shell')
			)->addOption('connection', array(
				'short' => 'c',
				'default' => 'default',
				'help' => __d('cake_console', 'Set db config <config>. Uses \'default\' if none is specified.')
			))->addSubcommand('ratings', array(
				'help' => __d('cake_console', 'Update ratings for all users and groups'),
			))->addSubcommand('merry_christmas_card', array(
				'help' => __d('cake_console', '2015 christmas card'),
			))
		;
		return $parser;
	}

	public function ratings(){
		//User ratings
		$this->out(__d('cake_console', '<info>-- User ratings --</info>'));
		$maxRatedUser = $this->User->find('first', array(
			'fields' => array('MAX(User.karma) AS karma'),
		));
		$this->out(__d('cake_console', '<info>- Max users karma %s</info>', $maxRatedUser[0]['karma']));
		$options = array(
			'conditions' => array('User.karma !=' => 0),
			'order' => array('User.id' => 'ASC'),
			'page' => 0,
			'limit' => 100,
			'rcursive' => -1,
			'callbacks' => false
		);
		$users = $this->User->find('all', $options);
		while($users != null){
			foreach($users as $user){
				$rating = round((100/$maxRatedUser[0]['karma'])*$user['User']['karma'], 2);
				$this->out(__d('cake_console', '- User %s have rating %s', $user['User']['full_name'], $rating));
				$this->User->updateAll(
					array('User.rating' => $rating),
					array('User.id' => $user['User']['id'])
				);
			}
			$users = $this->User->find('all', array_merge($options, array('page' => $options['page']++)));
		}

		//Group ratings
		$this->out(__d('cake_console', '<info>-- Group ratings --</info>'));
		$maxRatedGroup = $this->Group->find('first', array(
			'fields' => array('MAX(Group.karma) AS karma'),
		));
		$this->out(__d('cake_console', '<info>- Max groups karma %s</info>', $maxRatedGroup[0]['karma']));
		$options = array(
			'conditions' => array('Group.karma !=' => 0),
			'order' => array('Group.id' => 'ASC'),
			'page' => 0,
			'limit' => 100,
			'rcursive' => -1,
			'callbacks' => false
		);
		$groups = $this->Group->find('all', $options);
		while($groups != null){
			foreach($groups as $group){
				$rating = round((100/$maxRatedGroup[0]['karma'])*$group['Group']['karma'], 2);
				$this->out(__d('cake_console', '- Group %s have rating %s', $group['Group']['title'], $rating));
				$this->Group->updateAll(
					array('Group.rating' => $rating),
					array('Group.id' => $group['Group']['id'])
				);
			}
			$groups = $this->Group->find('all', array_merge($options, array('page' => $options['page']++)));
		}
	}

	public function merry_christmas_card(){
		$this->loadModel('User');
		$this->loadModel('UserEvent');
		$this->loadModel('UserEventShare');

		$daata = array();
		$data['UserEvent'] = array(
			'created' => '2015-12-24 00:00:00',
			'event_time' => '2015-12-25 00:00:00',
			'event_end_time' => '2015-12-25 23:59:59',
			'type' => 'entertain',
			'user_id' => 1,
			'title' => 'Merry Christmas',
			'descr' => '<img src="/img/merry_christmas.jpg" width="420" height="256">',
			'shared' => 1,
		);
		$users = $this->User->find('list');
		foreach($users as $userId){
			$data['UserEventShare'][] = array(
				'user_id' => $userId,
				'acceptance' => 1
			);
		}
		$this->UserEvent->saveAssociated($data, array('deep' => true));
	}

	public function ratings_regenerate(){

		$this->loadModel('User');
		$this->loadModel('Group');
		//Fix user with non existing groups
		$this->User->bindModel(
	        array('hasMany' => array(
	                'Group' => array(
	                    'className' => 'Group',
						'foreignKey' => 'owner_id'
	                )
	            )
	        )
	    );
		$this->User->Behaviors->load('Containable');
		$users = $this->User->find('all', array(
			'contain' => array('Group'),
			//'conditions' => array('Group.id' => null),
			'order' => array('User.id' => 'ASC')
		));
		$this->User->Behaviors->unload('Containable');
		foreach($users as $user){
			if(empty($user['Group'])){
				$data = array(
					'Group' =>array(
						'owner_id' => $user['User']['id'],
						'title' => __('My Group'),
						'hidden' => 1,
						'cat_id' => 0
					),
					'GroupAdministrator' => array(
						'role' => __('Administrator'),
						'user_id' => $user['User']['id'],
						'approved' => 1,
						'sort_order' => 0,
						'show_main' => 1,
						'approve_date' => date('Y-m-d H:i:s'),
					)
				);
				$data['Group']['finance_project_id'] = $this->Group->addFinanceProject($data['Group'], $user['User']['id'], true);
				$this->Group->saveAssociated($data);
				$this->out(__d('cake_console', '- Add non existin default group for user %s - %s - %s', $user['User']['id'], $user['User']['username'], $user['User']['full_name']));
			}
		}
		//Drop existing ratings
		$this->loadModel('Rating');
		$this->Rating->deleteAll(
			array('Rating.context LIKE' => 'Rating%'),
			true,
			true
		);
		$this->User->updateAll(
			array('User.karma' => 0, 'User.rating' => 0),
			array('User.karma !=' => 0)
		);
		$this->Group->updateAll(
			array('Group.karma' => 0, 'Group.rating' => 0),
			array('Group.karma !=' => 0)
		);

		//Set new ratings
		$ratingModels = array(
			'ArticleEvent', 'Article', 'BillingSubscription', 'GroupMember',
			'Group', 'InvestProject', 'Note', 'ProjectEvent', 'Project',
			'Subscription', 'Task', 'UserEvent', 'User'
		);
		foreach($ratingModels as $model){
			$this->out(__d('cake_console', '- Remove all %s contexted records', $model));
			$this->loadModel($model);
			$ids = $this->$model->find('list', array(
				'fields' => array($model.'.id'),
				'order' => array($model.'.id' => 'ASC')
			));
			$this->$model->Behaviors->load('Ratingable');
			foreach($ids as $id){
				$this->out(__d('cake_console', '- Rating for %s with id %s', $model, $id));
				$this->$model->rate($id, true);
			}
		}
		$this->ratings();
	}

}
