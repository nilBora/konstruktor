<?
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
class Article extends AppModel {
	public $name = 'Article';

	public $hasOne = array(
        'ArticleMedia' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('ArticleMedia.object_type' => 'Article')
        )
    );

    public $actsAs = array('Ratingable');

    /*
    public $validate = array(
        'title' => array(
            'checkNotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field must not be blank',
            )
        )
    );
    */
	/**
	 * @param $q
	 * @param bool $currUserID
	 * @param int $limit
	 * @return array|null
	 */
    public function search($q, $currUserID = false, $limit = 20, $additionalConditions = null) {
        if(mb_strlen($q) == 1) {
            $conditions = array('OR' => array('Article.title LIKE ?' => $q.'%'));
        } else {
            if(!preg_match('/[A-Za-z]/', $q)) {
                $t = $this->transliterateArray($q);

				$sql[] = array('Article.title LIKE ?' => '%'.$q.'%');
				$sql[] = array('Article.title LIKE ?' => $q.'%');

				$conditions = array('OR' => $sql);
			} else {
				$conditions = array(
					'OR' => array(
						array('Article.title LIKE ?' => '%'.$q.'%'),
						array('Article.title LIKE ?' => $q.'%')
					)
				);
			}
		}

		if ($additionalConditions) {
			$conditions = array_merge($conditions, $additionalConditions);
		}

		if ($currUserID) {
			$conditions['owner_id'] = $currUserID;
		} else {
			$conditions['published'] = 1;
		}

		$order = array('Article.title');
		return $this->find('all', compact('conditions', 'order'));
	}

	/**
	 * @param $currUserID
	 * @param $date
	 * @param $date2
	 * @param int $view
	 * @param bool $mail
	 * @return array|null
	 */
	public function timelineEvents($currUserID, $date, $date2, $view = 0, $mail = false) {
		$this->loadModel('Subscription');

		// свои статьи на таймлайне
		//$conditions = $this->dateRange('Article.created', $date, $date2);
		$conditions = $this->dateRange('Article.created', $date, $date2);
		if($mail) $conditions = $this->dateTimeRange('Article.created', $date, $date2);
		//$conditions['owner_id'] = $currUserID;
		$conditions['published'] = 1;
		$order = 'Article.created DESC';
		//$aArticles = $this->find('all', compact('conditions', 'order'));

		//выбираем айдишники, которые ложаться во временной интервал.
		$fields = array('Article.id');
		unset($conditions['owner_id']);

		$conditions['OR'][] = array(
			'owner_id' => $currUserID
		);
		//статьи из подписок на сообщества
		$aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($currUserID, 'group');
		if($aSubscriptions) {
			$objID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');
			$conditions['OR'][] = array(
				'Article.group_id' => $objID,
			);
		}

		// статьи из подписок на пользователей
		$aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($currUserID, 'user');
		if($aSubscriptions) {
			$objID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');
			$conditions['OR'][]= array(
				'Article.owner_id' => $objID,
				'Article.group_id' => null
			);
		}
		$this->Behaviors->load('Containable');
		$aArticles = $this->find('all', array(
			'contain' => array('ArticleMedia'),
			'conditions' => $conditions
		));

		return $aArticles;
	}

	/**
	 * @param $currUserID
	 * @param $date
	 * @param $date2
	 * @return array
	 */
	public function lastArticles($currUserID, $date, $date2) {
		$this->loadModel('Statistic');
		$this->loadModel('User');
		$this->loadModel('Skill');

		$user = $this->User->findById($currUserID);
		$extractedSkills = explode(', ', Hash::get($user, 'User.skills'));
		$conditions = array( 'OR' => array('rus' => $extractedSkills, 'eng' => $extractedSkills) );
		$aSkills = $this->Skill->find('all', compact('conditions'));
		$aSkillCat = Hash::extract($aSkills, '{n}.Skill.article_cat_id');
		$aSkillCat = array_combine($aSkillCat, $aSkillCat);
		unset($aSkillCat['']);

		$conditions = array('Article.cat_id' => $aSkillCat);



		$this->Behaviors->load('Containable');
		$fields = array('Article.id', 'Article.owner_id', 'Article.group_id', 'Article.title',
			'Article.video_url', 'Article.type', 'Article.published', 'Article.created',
			'Article.modified', 'Article.cat_id', 'Article.deleted', 'ArticleMedia.*'
		);
		$aArticles = $this->find('all', array(
			'fields' => $fields,
			'contain' => array('ArticleMedia'),
			'conditions' => $conditions
		));
		if($aArticles && count($aArticles)>1) {
			$aArticles = Hash::combine($aArticles, '{n}.Article.id', '{n}');
			$AID = Hash::extract($aArticles, '{n}.Article.id');

			$conditions = array(
				'Statistic.type' => 1,
				'Statistic.pk' => $AID,
				'Statistic.visitor_id <>' => $currUserID
			);

			$statData = $this->Statistic->find('all', compact('conditions') );
			if($statData) {
				$temp = array();

				foreach( $statData as $visit ) {
					if( !isset( $aArticles[ $visit['Statistic']['pk'] ]['Article'][ 'stat' ] ) ) $aArticles[ $visit['Statistic']['pk'] ]['Article'][ 'stat' ] = 0;
					$aArticles[ $visit['Statistic']['pk'] ]['Article'][ 'stat' ]++;
				}

				foreach( $aArticles as &$group ) {
					if( !isset( $group['Article'][ 'stat' ] ) ) $group['Article'][ 'stat' ] = 0;
				}
			}
			usort($aArticles, function ($a, $b) { return $b['Article']['stat'] - $a['Article']['stat']; });
		}

		$AID = Hash::extract($aArticles, '{n}.Article.id');

		// вся статистика по группам
		$gStats = $this->Statistic->query('SELECT Stat.pk, Count.cnt
											  FROM statistic  Stat
												   INNER JOIN (SELECT pk, count(pk) as cnt
																 FROM statistic WHERE statistic.type = 1
																GROUP BY pk) Count ON Stat.pk = Count.pk GROUP BY Stat.pk ORDER BY Count.cnt DESC');
		$gStats = array_slice($gStats, 0, 3);
		$gStats = Hash::extract($gStats, '{n}.Stat.pk' );
		$gStats = array_diff($gStats, $AID);
		$gTop = $this->find('all', array(
			'fields' => $fields,
			'contain' => array('ArticleMedia'),
			'conditions' => array(
				'Article.id' => $gStats,
				'Article.published' => 1,
			),
		));
		$return = array_merge($aArticles, $gTop);

		return array_slice($return, 0, 3);
	}

	/**
	 * Find most popular articles and return array
	 *
	 * @param $currUserID
	 * @return array
	 */
	public function popularArticles($currUserID) {
		/** @var User $userModel */
		$this->loadModel('User');
		$userModel = $this->User;
		/** @var Statistic $statisticModel */
		$this->loadModel('Statistic');
		$statisticModel = $this->Statistic;
		/** @var Skill $skillModel */
		$this->loadModel('Skill');
		$skillModel = $this->Skill;
		/** @todo add user interest to DB and then find article by user interests */

		$conditions = array('Article.published' => 1, 'Article.deleted' => 0, 'Article.title !=' =>'');
		$fields = ['Article.id',
			'Article.owner_id',
			'Article.title',
			'Article.body',
			'Article.cat_id',
			'Article.group_id',
			'Article.created',
			'Article.shared',
			'Article.hits',
			'ArticleMedia.*',
			'User.*',
			'UserMedia.*',
			'GroupMedia.*',
			'Group.title'];

		$limit = 3;
		$order = 'Article.hits DESC';

		$joins = array(
			array(
				'table' => 'groups',
				'alias' => 'Group',
				'type' => 'LEFT',
				'conditions' => array(
					'Article.group_id = Group.id'
				)
			),array(
				'table' => 'media',
				'alias' => 'GroupMedia',
				'type' => 'LEFT',
				'conditions' => array(
					'GroupMedia.object_id = Group.id',
					'GroupMedia.object_type = "Group"',
				)
			),array(
				'table' => 'users',
				'alias' => 'User',
				'type' => 'LEFT',
				'conditions' => array(
					'Article.owner_id = User.id'
				)
			),array(
				'table' => 'media',
				'alias' => 'UserMedia',
				'type' => 'LEFT',
				'conditions' => array(
					'UserMedia.object_id = Article.owner_id',
					'UserMedia.object_type = "User"',
				)
			)
		);

		$popularArticles = $this->find('all', compact('conditions', 'order', 'limit','fields','joins'));

		$ids = Hash::extract($popularArticles, '{n}.Article.id');

		/** @var Media $mediaModel */
		$this->loadModel('Media.Media');
		$mediaModel = $this->Media;

		$conditions = array('object_id' => $ids, 'object_type' => 'Article');
		$articleMedias = $mediaModel->find('all', compact('conditions'));

		foreach ($popularArticles as $keyArticle => $popularArticle) {
			foreach ($articleMedias as $keyMedia => $articleMedia) {
				if ($articleMedia['Media']['object_id'] == $popularArticle['Article']['id']) {
					$popularArticles[$keyArticle]['Media'] = $articleMedia['Media'];
				}
			}
		}

		return $popularArticles;
	}

	public function similarArticles($currCat,$currArt) {
		/** @var User $userModel */
		$this->loadModel('User');
		$userModel = $this->User;
		/** @var Statistic $statisticModel */
		$this->loadModel('Statistic');
		$statisticModel = $this->Statistic;
		/** @var Skill $skillModel */
		$this->loadModel('Skill');
		$skillModel = $this->Skill;
		/** @todo add user interest to DB and then find article by user interests */

		$conditions = array('Article.published' => 1, 'Article.deleted' => 0, 'Article.title !=' =>'', 'Article.cat_id' => $currCat,'Article.id !='=>$currArt);
		$fields = ['Article.id',
			'Article.owner_id',
			'Article.title',
			'Article.body',
			'Article.cat_id',
			'Article.group_id',
			'Article.created',
			'Article.shared',
			'Article.hits',
			'ArticleMedia.*',
			'User.*',
			'UserMedia.*',
			'GroupMedia.*',
			'Group.title'];

		$limit = 3;
		$order = 'Article.hits DESC';

		$joins = array(
			array(
				'table' => 'groups',
				'alias' => 'Group',
				'type' => 'LEFT',
				'conditions' => array(
					'Article.group_id = Group.id'
				)
			),array(
				'table' => 'media',
				'alias' => 'GroupMedia',
				'type' => 'LEFT',
				'conditions' => array(
					'GroupMedia.object_id = Group.id',
					'GroupMedia.object_type = "Group"',
				)
			),array(
				'table' => 'users',
				'alias' => 'User',
				'type' => 'LEFT',
				'conditions' => array(
					'Article.owner_id = User.id'
				)
			),array(
				'table' => 'media',
				'alias' => 'UserMedia',
				'type' => 'LEFT',
				'conditions' => array(
					'UserMedia.object_id = Article.owner_id',
					'UserMedia.object_type = "User"',
				)
			)
		);
		$similarArticles = $this->find('all', compact('conditions', 'order', 'limit','fields','joins'));

		$ids = Hash::extract($similarArticles, '{n}.Article.id');

		/** @var Media $mediaModel */
		$this->loadModel('Media.Media');
		$mediaModel = $this->Media;

		$conditions = array('object_id' => $ids, 'object_type' => 'Article');
		$articleMedias = $mediaModel->find('all', compact('conditions'));

		foreach ($similarArticles as $keyArticle => $similarArticle) {
			foreach ($articleMedias as $keyMedia => $articleMedia) {
				if ($articleMedia['Media']['object_id'] == $similarArticle['Article']['id']) {
					$similarArticles[$keyArticle]['Media'] = $articleMedia['Media'];
				}
			}
		}

		return $similarArticles;
	}
}
