<?php

App::uses('ModelBehavior', 'Model');
App::uses('AppModel', 'Model');

class KonstruktorTranslateBehavior extends ModelBehavior {

/**
 * Used for runtime configuration of model
 */
	public $runtime = array();

/**
 * Field names
 *
 * @var array
 */
	public $translationFields = array();

	public function setup(Model $model, $config = array()) {
		$this->settings[$model->alias] = array();
		$this->runtime[$model->alias] = array('fields' => array());
		$this->translateModel($model);
		$this->translationFields[$model->alias] = $config['fields'];
		$this->settings[$model->alias]['allTranslations'] = isset($config['allTranslations']) ? $config['allTranslations'] : true;
	}

/**
 * Callback
 *
 * @return void
 * @access public
 */
	public function cleanup(Model $model) {
		unset($this->settings[$model->alias]);
		unset($this->runtime[$model->alias]);
	}

	public function bindTranslations(Model $model) {
		$this->settings[$model->alias]['allTranslations'] = true;
	}

	public function unbindTranslations(Model $model) {
		$this->settings[$model->alias]['allTranslations'] = false;
	}

/**
 * Get field names for Translation
 *
 * @param object $model
 * @return array
 * @access public
 */
	public function getTranslationFields(Model $model) {
		if (Hash::numeric(array_keys($this->translationFields[$model->alias]))) {
			return $this->translationFields[$model->alias];
		} else {
			return array_keys($this->translationFields[$model->alias]);
		}
	}

/**
 * afterFind Callback
 *
 * @param array $results
 * @param boolean $primary
 * @return array Modified results
 * @access public
 */
	public function afterFind(Model $model, $results, $primary = false) {
		$locale = $this->_getLocale($model);

		if (empty($locale) || empty($results)) {
			return $results;
		}

		$fields = $this->getTranslationFields($model);
		$RuntimeModel = $this->translateModel($model);

		if ($primary && isset($results[0][$model->alias])) {
			$i = 0;
			foreach ($results as $result) {
				if (!isset($result[$model->alias][$model->primaryKey])) {
					continue;
				}

				$translations = $RuntimeModel->find('all', array(
					'conditions' => array(
						$RuntimeModel->alias . '.model' => $model->alias,
						$RuntimeModel->alias . '.foreign_key' => $result[$model->alias][$model->primaryKey],
						$RuntimeModel->alias . '.field' => $fields,
					),
				));
				foreach ($translations as $translation) {
					$field = $translation[$RuntimeModel->alias]['field'];

					// Translated row
					if ($translation[$RuntimeModel->alias]['locale'] == $locale &&
						isset($results[$i][$model->alias][$field])) {
						$results[$i][$model->alias][$field] = $translation[$RuntimeModel->alias]['content'];
						$results[$i][$model->alias]['locale'] = $translation[$RuntimeModel->alias]['locale'];
					}

					// Other translations
					if ($this->settings[$model->alias]['allTranslations'] && isset($results[$i][$model->alias][$field])) {
						if (!isset($results[$i][$field . 'Translation'])) {
							$results[$i][$field . 'Translation'] = array();
						}
						$results[$i][$field . 'Translation'][] = $translation[$RuntimeModel->alias];
					}
				}

				$i++;
			}
		}

		return $results;
	}

	public function afterSave(Model $model, $created, $options = array()){
		if(!isset($model->id)){
			$model->id = $model->getLastInsertId();
		}
		return $this->saveTranslation($model, $model->data, false);
	}


/**
 * Save translation only (in i18n table)
 *
 * @param object $model
 * @param array $data
 * @param boolean $validate
 */
	public function saveTranslation(Model $model, $data = null, $validate = true) {
		$model->data = $data;
		if (!isset($model->data[$model->alias])) {
			return false;
		}

		$locale = $this->_getLocale($model);
		if (empty($locale)) {
			return false;
		}

		$locales = Configure::read('Config.languages');
		if(empty($locales)){
			$locales = array('eng');
		}

		$RuntimeModel = $this->translateModel($model);
		$conditions = array(
			'model' => $model->alias,
			'foreign_key' => $model->id,
			'locale' => $locales,
			'field' => $this->translationFields[$model->alias]
		);
		$translations = $RuntimeModel->find('list', array(
			'fields' => array($RuntimeModel->alias . '.locale', $RuntimeModel->alias . '.id'),
			'conditions' => $conditions,
		));
		foreach($locales as $_locale){
			foreach($this->translationFields[$model->alias] as $field){
				$value = Hash::get($model->data, $model->alias.".".$_locale.".".$field);
				if(empty($value)){
					continue;
				}
				$RuntimeModel->create();
				$conditions = array_merge($conditions, array(
					'locale' => $_locale,
					'field' => $field,
					'content' => $value,
				));
				if (array_key_exists($locale, $translations)) {
					$result = $RuntimeModel->save(array(
						$RuntimeModel->alias => array_merge($conditions, array('id' => $translations[$_locale]))
					));
				} else {
					$result = $RuntimeModel->save(array(
						$RuntimeModel->alias => $conditions
					));
				}
				if (!$result) {
					return false;
				}
			}
		}
		return true;
	}

/**
 * afterDelete Callback
 *
 * @return void
 * @access public
 */
	public function afterDelete(Model $model) {
		$RuntimeModel = $this->translateModel($model);
		$conditions = array('model' => $model->alias, 'foreign_key' => $model->id);
		$RuntimeModel->deleteAll($conditions);
	}

/**
 * Get selected locale for model
 *
 * @return mixed string or false
 * @access protected
 */
	protected function _getLocale(Model $model) {
		if (!isset($model->locale) || is_null($model->locale)) {
			$model->locale = Configure::read('Config.language');
		}

		return $model->locale;
	}

/**
 * Get instance of model for translations
 *
 * @return object
 * @access public
 */
	public function &translateModel(Model $model) {
		if (!isset($this->runtime[$model->alias]['model'])) {
			if (!isset($model->translateModel) || empty($model->translateModel)) {
				$className = 'I18nModel';
			} else {
				$className = $model->translateModel;
			}

			$this->runtime[$model->alias]['model'] = ClassRegistry::init($className, 'Model');
		}
		if (!empty($model->translateTable) && $model->translateTable !== $this->runtime[$model->alias]['model']->useTable) {
			$this->runtime[$model->alias]['model']->setSource($model->translateTable);
		} elseif (empty($model->translateTable) && empty($model->translateModel)) {
			$this->runtime[$model->alias]['model']->setSource('translate_i18n');
		}
		return $this->runtime[$model->alias]['model'];
	}

}

if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
/**
 * @package	 Croogo.Translate.Model.Behavior
 */
	class I18nModel extends AppModel {

		public $name = 'I18nModel';

		public $useTable = 'translate_i18n';

		public $displayField = 'field';
	}

}
