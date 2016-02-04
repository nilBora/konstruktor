<?php

/**
 * Class InvestCategory
 */
class GroupCategory extends AppModel {

	/**
	 * @return array
	 */
	public function getCategoriesList() {
		$InvestCategory = $this->find('all');
		$InvestCategory = Hash::combine($InvestCategory, '{n}.GroupCategory.id', '{n}.GroupCategory.name');
		$temp = array();
		foreach ($InvestCategory as $id => &$item) {
			$item = __($item);
			$temp[$id] = $item;
		}
		asort($temp);
		
		$result = array();
		foreach ($temp as $id => $item2) {
			$result[$id] = $InvestCategory[$id];
		}

		return $result;
	}

	/**
	 * @param $id - Category Id
	 * @return array
	 * @throws Exception
	 */
	public function getOne($id) {
		$item = $this->find('first', array('conditions' => array('GroupCategory.id' => (int) $id)));
		if (!$item) {
			throw new Exception(__('Category is not found'));
		}
		$item['GroupCategory']['name'] = __($item['GroupCategory']['name']);
		return $item;
	}
}