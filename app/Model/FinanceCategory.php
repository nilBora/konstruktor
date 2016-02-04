<?php

class FinanceCategory extends AppModel {

	public $useTable = 'finance_category';

	public $hasMany = array(
		'CategoryHasOperation' => array(
			'className' => 'FinanceOperationHasCategory',
			'foreignKey' => 'category_id',
			'dependent' => true,
		)
	);

	public function search($projectId, $type = null, $q = null) {
		$this->loadModel('FinanceShare');
		$conditions = array(
			'FinanceCategory.project_id' => $projectId
		);
		if (FinanceShare::isPartAccess()) {
			$conditions['FinanceCategory.id'] = FinanceShare::$get['categories'];
		}
		if ($q !== null) {
			$conditions['FinanceCategory.name LIKE ?'] = '%' . $q . '%';
		}
		if ($type !== null) {
			$conditions['FinanceCategory.type'] = (int) $type;
		}
		$financeCategory = $this->find('all', array('conditions' => $conditions));
		return array(
			'aFinanceCategory' => $financeCategory,
		);
	}

	public function getOne($id) {
		$item = $this->find('first', array('conditions' => array(
			'FinanceCategory.id' => $id,
		)));
		if (!$item) {
			throw new Exception('Category is not found');
		}
		return array(
			'aFinanceCategory' => $item,
		);
	}

	public function addCategory ($projectId, $data) {
		$data['FinanceCategory']['project_id'] = $projectId;
		$this->save($data);
		return $this->id;
	}

	public function deleteCategory($id) {
		$item = $this->find('first', array('conditions' => array(
			'FinanceCategory.id' => $id,
		)));
		if (!$item) {
			throw new Exception('Category is not found');
		}
		$this->delete($id);
	}

	public function getStatistic($projectId, $currency, $period = null, $from = null, $to = null) {
		$periodCondition = '';
		if ($from === null && $to === null) {
			switch ($period) {
				case 'week':
					$periodCondition = ' AND finance_operation.created <= DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND finance_operation.created >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)';
					break;
				case 'month':
					$periodCondition = ' AND finance_operation.created <= DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND finance_operation.created >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)';
					break;
				case 'quarter':
					$periodCondition = ' AND finance_operation.created <= DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND finance_operation.created >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)';
					break;
				case 'year':
					$periodCondition = ' AND finance_operation.created <= DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND finance_operation.created >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)';
					break;
			}
		} else if ($from !== null && $to === null) {
			$from = substr($from, 0, 10);
			$periodCondition = " AND finance_operation.created >= '$from'";
			$periodCondition .= " AND finance_operation.created <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
		} else if ($from === null && $to !== null) {
			$to = substr($to, 0, 10);
			$periodCondition = " AND finance_operation.created <= '$to'";
		} else {
			$from = substr($from, 0, 10);
			$to = substr($to, 0, 10);
			$periodCondition = " AND finance_operation.created >= '$from'";
			$periodCondition .= " AND finance_operation.created <= DATE_ADD('$to', INTERVAL 1 DAY)";
		}

		$sql = "
			SELECT finance_category.id, finance_category.name, -1*SUM(finance_operation.amount) total
			FROM finance_category, finance_operation, finance_operation_has_category
			WHERE finance_category.id = finance_operation_has_category.category_id AND finance_operation.id = finance_operation_has_category.operation_id
				AND finance_category.type = 1
				AND finance_operation.currency = '$currency'
				AND finance_category.project_id = $projectId
				$periodCondition
			GROUP BY finance_category.id
		";
		$data = $this->query($sql);
		$result = array(
			array('name', 'total')
		);
		foreach ($data as $item) {
			$result[] = array($item['finance_category']['name'], (double) $item[0]['total']);
		}
		return $result;
	}

	public function getReport($projectId, $accountId, $month1, $month2) {
		$result = array('categories' => array());
		if (!$accountId) {
			return $result;
		}
		$addCondition1 = "o.created >= '$month1-01' AND o.created < DATE_ADD('$month1-01', INTERVAL 1 MONTH) AND o.account_id=$accountId";
		$addCondition2 = "o.created >= '$month2-01' AND o.created < DATE_ADD('$month2-01', INTERVAL 1 MONTH) AND o.account_id=$accountId";
		$sql = "
			SELECT category.name, category.type,  category.id,
				(SELECT GROUP_CONCAT(ohc1.operation_id) FROM finance_operation_has_category ohc1 WHERE ohc1.category_id=category.id) operation_id_list,
  				(SELECT SUM(o.amount) FROM finance_operation o
    				WHERE
      					$addCondition1
      					AND o.id IN (SELECT ohc2.operation_id FROM finance_operation_has_category ohc2 WHERE ohc2.category_id=category.id)) sum_amount_1,
  				(SELECT SUM(o.amount) FROM finance_operation o
    				WHERE
      					$addCondition2
						AND o.id IN (SELECT ohc2.operation_id FROM finance_operation_has_category ohc2 WHERE ohc2.category_id=category.id)) sum_amount_2
			FROM finance_category category
			WHERE category.project_id=$projectId
			ORDER BY category.type
		";
		$result['categories'] = $this->query($sql);

		return $result;
	}

	public function getBudget($projectId, $accountId, $month1, $month2, $month3, $month4, $categoryId = null) {
		$result = array('categories' => array());
		if (!$accountId) {
			return $result;
		}
		$addCondition1 = "o.created >= '$month1-01' AND o.created < DATE_ADD('$month1-01', INTERVAL 1 MONTH) AND o.account_id=$accountId";
		$addCondition2 = "o.created >= '$month2-01' AND o.created < DATE_ADD('$month2-01', INTERVAL 1 MONTH) AND o.account_id=$accountId";
		$addCondition3 = "o.created >= '$month3-01' AND o.created < DATE_ADD('$month3-01', INTERVAL 1 MONTH) AND o.account_id=$accountId";
		$addCondition4 = "o.created >= '$month4-01' AND o.created < DATE_ADD('$month4-01', INTERVAL 1 MONTH) AND o.account_id=$accountId";

		$sql = "
			SELECT category.name, category.type,  category.id,
				(SELECT GROUP_CONCAT(ohc1.operation_id) FROM finance_operation_has_category ohc1 WHERE ohc1.category_id=category.id) operation_id_list,
  				(SELECT SUM(o.amount) FROM finance_operation o
    				WHERE
      					$addCondition1
      					AND o.id IN (SELECT ohc2.operation_id FROM finance_operation_has_category ohc2 WHERE ohc2.category_id=category.id)) sum_amount_1,
      			(SELECT SUM(o.amount) FROM finance_operation o
    				WHERE
      					$addCondition2
      					AND o.id IN (SELECT ohc3.operation_id FROM finance_operation_has_category ohc3 WHERE ohc3.category_id=category.id)) sum_amount_2,
      			(SELECT SUM(o.amount) FROM finance_operation o
    				WHERE
      					$addCondition3
      					AND o.id IN (SELECT ohc4.operation_id FROM finance_operation_has_category ohc4 WHERE ohc4.category_id=category.id)) sum_amount_3,
  				(SELECT SUM(o.amount) FROM finance_operation o
    				WHERE
      					$addCondition4
						AND o.id IN (SELECT ohc5.operation_id FROM finance_operation_has_category ohc5 WHERE ohc5.category_id=category.id)) sum_amount_4
			FROM finance_category category
			WHERE category.project_id=$projectId".($categoryId ? " AND category.id=$categoryId " : " ").
			"ORDER BY category.type
		";
		$result['categories'] = $this->query($sql);
		
		$this->loadModel('Group');
		$this->loadModel('Project');
		$group = $this->Group->findByFinanceProjectId($projectId);
		if($group) {
			$aProjects = $this->Project->findAllByGroupId( Hash::get($group, 'Group.id') );
			$pID = Hash::extract($aProjects, '{n}.Project.id');
			$restricted = array_merge( 
				Hash::extract($aProjects, '{n}.ProjectFinance.income_id'),
				Hash::extract($aProjects, '{n}.ProjectFinance.expense_id'),
				Hash::extract($aProjects, '{n}.ProjectFinance.tax_id'),
				Hash::extract($aProjects, '{n}.ProjectFinance.percent_id')
			);
			$return = array();
			
			foreach($result['categories'] as $category) {
				if(!in_array($category['category']['id'], $restricted)) {
					$return[] = $category;
				}
			}
			$result['categories'] = $return;
		}

		return $result;
	}
}