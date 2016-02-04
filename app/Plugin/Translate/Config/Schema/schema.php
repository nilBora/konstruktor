<?php

class TranslateSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $i18n = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'locale' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 6, 'key' => 'index'),
		'model' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'field' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index'),
		'content' => array('type' => 'text', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => true),
			'i18n_locale_index' => array('column' => 'locale', 'unique' => false),
			'i18n_model_index' => array('column' => 'model', 'unique' => false),
			'i18n_row_id_index' => array('column' => 'foreign_key', 'unique' => false),
			'i18n_field_index' => array('column' => 'field', 'unique' => false),
		),
		'tableParameters' => array('engine' => 'InnoDB', 'charset' => 'utf8', 'collate' => 'utf8_general_ci')
	);

}
