<?php

/**
 * Class Statistic
 * @property Group Group
 * @property Article Article
 */
class Statistic extends AppModel
{
	const TYPE_PROFILE = 0;
	const TYPE_ARTICLE = 1;
	const TYPE_GROUP = 2;

	public $useTable = 'statistic';
	public $targets = array(
		array('controller' => 'User', 'action' => 'view', 'type' => self::TYPE_PROFILE),
		array('controller' => 'Article', 'action' => 'view', 'type' => self::TYPE_ARTICLE),
		array('controller' => 'Group', 'action' => 'view', 'type' => self::TYPE_GROUP),
	);

	/**
	 * Adding data to statistic
	 * @param array $params
	 */
	public function addData($userId, array $params) {
		foreach ($this->targets as $target) {
			if ($target['controller'] === $params['controller'] && $target['action'] === $params['action'] && !empty($params['pass'][0])) {
				$this->save(array(
					'pk' => (int) $params['pass'][0],
					'type' => $target['type'],
					'created' => date('Y-m-d H:i:s'),
					'visitor_id' => $userId,
				));
			}
		}
	}

	/**
	 * @param $userId
	 * @param null $period
	 * @param null $from
	 * @param null $to
	 * @return array
	 */
	public function profileData($userId, $period = null, $from = null, $to = null) {
		$periodConditions = array();
		if ($from) {
			$periodConditions[] = "FinanceCalendar.date >= '$from'";
			if (!$to) {
				$to = date('Y-m-d', strtotime('+1 years', strtotime($from)));
			}
		}
		if ($to) {
			$periodConditions[] = "FinanceCalendar.date <= DATE_ADD('$to', INTERVAL 24 HOUR)";
			if (!$from) {
				$from = date('Y-m-d', strtotime('-1 years', strtotime($to)));
				$periodConditions[] = "FinanceCalendar.date >= DATE_SUB('$to', INTERVAL 1 Year)";
			}
		}
		if (!$from && !$to ) {
			switch ($period) {
				case null:
				case 'today':
					$periodConditions[] = 'FinanceCalendar.date = CURDATE()';
					break;
				case 'week':
					$periodConditions[] = 'FinanceCalendar.date <= CURDATE() AND FinanceCalendar.date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)';
					break;
				case 'month':
					$periodConditions[] = 'FinanceCalendar.date <= CURDATE() AND FinanceCalendar.date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)';
					break;
				case 'year':
					$periodConditions[] = 'FinanceCalendar.date <= CURDATE() AND FinanceCalendar.date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)';
					break;
			}
		}
		if ($from && $to) {
			$deltaDays = (strtotime($to) - strtotime($from))/(3600*24);
			if ($deltaDays < 0) {
				$deltaDays *= -1;
			}
		}
		$this->loadModel('FinanceCalendar');
		$days = $this->FinanceCalendar->find('all', array('conditions' => $periodConditions));
		$days = Hash::combine($days, '{n}.FinanceCalendar.id', '{n}.FinanceCalendar.date');
		$conditions = array(
			'Statistic.pk' => $userId,
			'Statistic.type' => self::TYPE_PROFILE,
			"Statistic.visitor_id <> $userId",
		);
		$result = $this->FinanceCalendar->find('all', array(
			'conditions' => array_merge($conditions, $periodConditions),
			'joins' => array(
				array(
					'table' => 'statistic',
					'alias' => 'Statistic',
					'type' => 'left',
					'conditions' => 'SUBSTR(Statistic.created,1,10) = FinanceCalendar.date'
				),
			),
			'fields' => array('Statistic.pk', 'COUNT(Statistic.id) visits', 'FinanceCalendar.date'),
			'group' => 'FinanceCalendar.date',
			'order' => 'FinanceCalendar.date',
		));
		$result = Hash::combine($result, '{n}.FinanceCalendar.date', '{n}.0.visits');
		$result2 = array();
		foreach ($days as $date) {
			$visits = isset($result[$date]) ? $result[$date] : 0;
			$result2[$date] = $visits;
		}
		$return = array(array('date', 'visits'));
		$i = 0;
		$count = count($result2);
		$fullVisits = 0;
		if (isset($deltaDays) && $deltaDays > 30) {
			$period = 'year';
		}
		foreach ($result2 as $date => $visits) {
			$i++;
			$fullVisits += $visits;
			if (in_array($period, array('quarter', 'year')) && (date("Y-m-t", strtotime($date)) !== date("Y-m-j", strtotime($date))) && $i !== $count) {
				continue;
			}
			if (in_array($period, array('quarter', 'year'))) {
				$day = __(date('M', strtotime($date)));
			} elseif ($period == 'month') {
				$day = date('j', strtotime($date));
			} else {
				$day = __(date('D', strtotime($date)));
				//$day .= ' (' . date('j', strtotime($date));
				//$day .= ' ' . __(date('M', strtotime($date))) . ')';
			}

			$return[] = array($day, $fullVisits);
			$fullVisits = 0;
		}

		return $return;
	}

	/**
	 * @param $userId
	 * @param null $period
	 * @param null $from
	 * @param null $to
	 * @return array
	 */
	public function groupsData($userId, $period = null, $from = null, $to = null) {
		$periodConditions = array();
		if ($from) {
			$periodConditions[] = "Statistic.created >= '$from'";
		}
		if ($to) {
			$periodConditions[] = "Statistic.created <= DATE_ADD('$to', INTERVAL 24 HOUR)";
		}
		if (!$from && !$to ) {
			switch ($period) {
				case null:
				case 'today':
					$periodConditions[] = 'SUBSTR(Statistic.created,1,10) = CURDATE()';
					break;
				case 'week':
					$periodConditions[] = 'SUBSTR(Statistic.created,1,10) <= CURDATE() AND SUBSTR(Statistic.created,1,10) >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)';
					break;
				case 'month':
					$periodConditions[] = 'SUBSTR(Statistic.created,1,10) <= CURDATE() AND SUBSTR(Statistic.created,1,10) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)';
					break;
				case 'year':
					$periodConditions[] = 'SUBSTR(Statistic.created,1,10) <= CURDATE() AND SUBSTR(Statistic.created,1,10) >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)';
					break;
			}
		}
		$this->loadModel('Group');
		$groups = $this->Group->find('all', array(
			'conditions' => array('Group.owner_id' => $userId),
			'fields' => array('Group.id', 'Group.owner_id', 'Group.title'),
		));
		$groups = Hash::combine($groups, '{n}.Group.id', '{n}.Group.title');
		$this->virtualFields['visits'] = 'COUNT(id)';
		$statistic = $this->find('all', array(
			'conditions' => array_merge(array(
				'Statistic.pk' => array_keys($groups),
				'Statistic.type' => self::TYPE_GROUP,
				"Statistic.visitor_id <> $userId",
			), $periodConditions),
			'group' => 'Statistic.pk',
			'fields' => array('Statistic.pk', 'Statistic.visits')
		));
		$statistic = Hash::combine($statistic, '{n}.Statistic.pk', '{n}.Statistic.visits');
		$return = array(array('name', 'visits'));
		foreach ($groups as $id => $name) {
			$visits = isset($statistic[$id]) ? $statistic[$id] : 0;
			$return[] = array($name, (int) $visits);
		}

		return $return;
	}

	/**
	 * @param $userId
	 * @param null $period
	 * @param null $from
	 * @param null $to
	 * @return array
	 */
	public function articlesData($userId, $period = null, $from = null, $to = null) {
		$periodConditions = array();
		if ($from) {
			$periodConditions[] = "Statistic.created >= '$from'";
		}
		if ($to) {
			$periodConditions[] = "Statistic.created <= DATE_ADD('$to', INTERVAL 24 HOUR)";
		}
		if (!$from && !$to ) {
			switch ($period) {
				case null:
				case 'today':
					$periodConditions[] = 'SUBSTR(Statistic.created,1,10) = CURDATE()';
					break;
				case 'week':
					$periodConditions[] = 'SUBSTR(Statistic.created,1,10) <= CURDATE() AND SUBSTR(Statistic.created,1,10) >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)';
					break;
				case 'month':
					$periodConditions[] = 'SUBSTR(Statistic.created,1,10) <= CURDATE() AND SUBSTR(Statistic.created,1,10) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)';
					break;
				case 'year':
					$periodConditions[] = 'SUBSTR(Statistic.created,1,10) <= CURDATE() AND SUBSTR(Statistic.created,1,10) >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)';
					break;
			}
		}
		$this->loadModel('Article');
		$articles = $this->Article->find('all', array(
			'conditions' => array('Article.owner_id' => $userId, 'Article.deleted' => '0'),
			'fields' => array('Article.id', 'Article.owner_id', 'Article.title'),
		));
		$articles = Hash::combine($articles, '{n}.Article.id', '{n}.Article.title');

		$this->virtualFields['visits'] = 'COUNT(id)';
		$statistic = $this->find('all', array(
			'conditions' => array_merge(array(
					'Statistic.pk' => array_keys($articles),
					'Statistic.type' => self::TYPE_ARTICLE,
					"Statistic.visitor_id <> $userId",
				), $periodConditions),
			'group' => 'Statistic.pk',
			'fields' => array('Statistic.pk', 'Statistic.visits')
		));
		$statistic = Hash::combine($statistic, '{n}.Statistic.pk', '{n}.Statistic.visits');
		$return = array(array('name', 'visits'));
		foreach ($articles as $id => $name) {
			$visits = isset($statistic[$id]) ? $statistic[$id] : 0;
			$return[] = array($name, $visits);
		}

		return $return;
	}
}