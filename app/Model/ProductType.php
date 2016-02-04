<?
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
class ProductType extends AppModel {
	
	public $hasOne = array(
		'Media' => array(
			'foreignKey' => 'object_id',
			'conditions' => array('Media.object_type' => 'ProductType', 'Media.main' => 1),
			'dependent' => true
		)
	);
	
	public $validate = array(
		'title' => 'notempty'
	);
	
	public function options() {
		$aOptions = Hash::combine($this->find('all'), '{n}.ProductType.id', '{n}');
		$aIcons = array(
			1 => 'ipad',
			4 => 'imac',
			5 => 'print'
		);
		foreach($aOptions as $id => &$option) {
			$option['ProductType']['icon'] = $aIcons[$id];
		}
		return $aOptions;
	}
}
