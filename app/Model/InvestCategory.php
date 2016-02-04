<?php

/**
 * Class InvestCategory
 */
class InvestCategory extends AppModel {

	public $useTable = 'invest_category';

	/**
	 * @return array
	 */
	public function search() {
		$order = 'InvestCategory.title';
		$InvestCategory = $this->find('all', compact('order'));
		$InvestCategory = Hash::combine($InvestCategory, '{n}.InvestCategory.id', '{n}');

		$temp = array();
		foreach ($InvestCategory as &$item) {
			$item['InvestCategory']['title'] = __($item['InvestCategory']['title']);
			$temp[$item['InvestCategory']['id']] = $item['InvestCategory']['title'];
		}
		asort($temp);
		$result = array();
		foreach ($temp as $id => $item2) {
			$result[] = $InvestCategory[$id];
		}

		return array(
			'aInvestCategory' => $result,
		);
	}

	/**
	 * @param $id - Category Id
	 * @return array
	 * @throws Exception
	 */
	public function getOne($id) {
		$item = $this->find('first', array('conditions' => array('InvestCategory.id' => (int) $id)));
		if (!$item) {
			throw new Exception(__('Category is not found'));
		}
		$item['InvestCategory']['title'] = __($item['InvestCategory']['title']);
		return array(
			'aInvestCategory' => $item,
		);
	}
}