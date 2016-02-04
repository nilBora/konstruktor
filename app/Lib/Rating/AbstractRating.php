<?php
/**
 * Abstract rating class
 *
 */
abstract class AbstractRating {

/**
 * Configurations for this object. Settings passed from authenticator class to
 * the constructor are merged with this property.
 *
 * @var array
 */
	protected $_config = array();

	protected $context = null;

/**
 * Constructor
 *
 * @param array $config Array of config.
 */
	public function __construct($context, $config = array()) {
		$this->context = str_replace('Rating', '', get_class($this));
		$this->config($config);
		$this->Rating = ClassRegistry::init('Rating');
	}

/**
 * Get/Set the config
 *
 * @param array $config Sets config, if null returns existing config
 * @return array Returns configs
 */
	public function config($config = null) {
		if (is_array($config)&&!empty($config)) {
			$this->_config = Hash::merge($this->_config, $config);
		}
		return $this->_config;
	}

	protected function validate($action, $foreignModel, $foreignKey, array $data = array()){
		if(!isset($data[$this->context][$foreignKey])||empty($data[$this->context][$foreignKey])){
			return false;
		}
		$rate = $this->Rating->find('first', array(
			'conditions' => array(
				'Rating.foreign_model LIKE' => $foreignModel,
				'Rating.foreign_id' => $data[$this->context][$foreignKey],
				'Rating.context LIKE' => 'Rating.'.$this->context.'.'.$action,
				//potentially primary key maybe differ than integer or have another name
				'Rating.context_id' => $data[$this->context]['id'],
			),
			'recursive' => -1
		));
		if(!empty($rate)){
			return false;
		}
		return true;
	}

	protected function create(array $data = array()){
		if(empty($data)){
			return false;
		}
		$this->Rating->create();
		if(!$this->Rating->saveMany($data)){
			//debug($this->Rating->validationErrors);
		}
	}

	protected function delete(integer $foreignId = null, $cascade = true){
		return false;
	}

	public function rate($data, $created = false){
		$config = $this->config();
		$result = false;
		foreach($config as $action=>$ratingData){
			$saveData = array();
			if(isset($ratingData['createdOnly'])&&($ratingData['createdOnly'] === true)){
				if(!$created){
					continue;
				}
			}
			foreach($ratingData['target'] as $foreignModel=>$foreignKeys){
				if(!is_array($foreignKeys)) $foreignKeys = array($foreignKeys);
				foreach($foreignKeys as $foreignKey){
					if(method_exists($this, $foreignKey)){
						$data = $this->$foreignKey($data);
					}
					$validatior = $action;
					if(!method_exists($this, $validatior)){
						$validatior = 'validate';
					}
					if($result = $this->$validatior($action, $foreignModel, $foreignKey, $data)){
						$saveData[] = array(
							'foreign_model' => $foreignModel,
							'foreign_id' => $data[$this->context][$foreignKey],
							'context' => 'Rating.'.$this->context.'.'.$action,
							'context_id' => $data[$this->context]['id'],
							'value' => $ratingData['value'],
						);
					}
				}
			}
			$this->create($saveData);
		}
	}

	//feature not implemented
	//abstract public function unrate(integer $id = null);

}
