<?php
App::uses('Model', 'Model');
class AppModel extends Model {

	protected $objectType = '';

	public function __construct($id = false, $table = null, $ds = null) {
		$this->_beforeInit();
		if(isset($_SERVER['REQUEST_URI'])) {
			if (strstr($_SERVER['REQUEST_URI'], '/test/Test/'))
			{
				$this->useDbConfig = 'test';
			}
		}
		parent::__construct($id, $table, $ds);
		$this->_afterInit();
	}

	protected function _beforeInit() {
		// Add here behaviours, models etc that will be also loaded while extending child class
	}

	protected function _afterInit() {
		// after construct actions here
	}

	/**
	 * Auto-add object type in find conditions
	 *
	 * @param array $query
	 * @return array
	 */
	public function beforeFind($query) {
		if ($this->objectType) {
			$query['conditions'][$this->objectType.'.object_type'] = $this->objectType;
		}
		return $query;
	}

	/*
	 * Wide custom validation rules
	 */
	public function alphaNumericWhitespaceDashUnderscore($check) {
		$value = array_values($check);
		$value = $value[0];
		return preg_match('/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd} \.\-_]+$/Du', $value);
	}




	private function _getObjectConditions($objectType = '', $objectID = '') {
		$conditions = array();
		if ($objectType) {
			$conditions[$this->alias.'.object_type'] = $objectType;
		}
		if ($objectID) {
			$conditions[$this->alias.'.object_id'] = $objectID;
		}
		return compact('conditions');
	}

	public function getOptions($objectType = '', $objectID = '') {
		return $this->find('list', $this->_getObjectConditions($objectType, $objectID));
	}

	public function getObject($objectType = '', $objectID = '') {
		return $this->find('first', $this->_getObjectConditions($objectType, $objectID));
	}

	public function getObjectList($objectType = '', $objectID = '') {
		return $this->find('all', $this->_getObjectConditions($objectType, $objectID));
	}

	/**
	 * Loads a model inside another model.
	 * Requires to have proper variables in parent model.
	 *
	 * @param mixed $models - model name or array of model names
	 */
	public function loadModel($models) {
		if (!is_array($models)) {
			$models = array($models);
		}
		foreach($models as $model) {
			App::import('Model', $model);
			if (strpos($model, '.') !== false) {
				list($plugin, $model) = explode('.', $model);
			}
			$this->$model = new $model();
		}
	}

	public function getTableName() {
		return $this->getDataSource()->fullTableName($this);
	}

	public function dateRange($field, $date1, $date2 = '') {
		// TODO: implement for free date2
		$date1 = date('Y-m-d 00:00:00', strtotime($date1));
		$date2 = date('Y-m-d 23:59:59', strtotime($date2));
		return array($field.' >= ' => $date1, $field.' <= ' => $date2);
	}

	public function dateTimeRange($field, $date1, $date2 = '') {
		// TODO: implement for free date2
		$date1 = date('Y-m-d H:i:s', strtotime($date1));
		$date2 = date('Y-m-d H:i:s', strtotime($date2));
		return array($field.' >= ' => $date1, $field.' <= ' => $date2);
	}

	public function transliterateRegex ($txt)
	{
		$txt=mb_strtolower($txt);
		$sr=array("кс","ей","ов","а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я");
		$se=array("(x|[kc]s)","e[yi]","o(v|ff)","a","b","[vw]","g","d","e","[yj]o","z(h)*","[zs]","[iy]","[jy]","[kc]","l","m","n","o","p","r","s","t","(u|oo)","f","(k|c)*h","(c|ts)","ch","sh","s(h)*(c)*h","","y","","e","[yj]*u","[yj]a");
		return str_replace($sr,$se,$txt);
	}

	public function transliterate($st, $lUrlMode = false) {
		// Сначала заменяем "односимвольные" фонемы.
		$st = mb_convert_encoding($st, 'cp1251', 'utf8');
		$st = strtr($st, "абвгдеёзийклмнопрстуфхыэ", "abvgdeeziyklmnoprstufhye");
		$st = strtr($st, "АБВГДЕЁЗИЙКЛМНОПРСТУФХЫЭ", "ABVGDEEZIYKLMNOPRSTUFHYE");

		// Затем - "многосимвольные".
		$st = strtr($st, array(
			"ж"=>"zh", "ц"=>"c", "ч"=>"ch", "ш"=>"sh", "щ"=>"shch", "ь"=>"", "ъ"=>"", "ю"=>"ju", "я"=>"ja",
			"Ж"=>"ZH", "Ц"=>"C", "Ч"=>"CH", "Ш"=>"SH", "Щ"=>"SHCH", "Ь"=>"", "ъ"=>"", "Ю"=>"JU", "Я"=>"JA",
			"ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
		));

		if ($lUrlMode) {
			$st = strtolower(strtr($st, array(
				"'" => "", '"' => '', ' ' => '-', '.' => '-', ',' => '-', '/' => '-'
			)));
			$st = str_replace(array('----', '---', '--'), '-', $st);
		}

		return $st;
	}

	public function transliterateArray ($txt)
	{
		  $r= array(mb_strtolower($txt,"utf-8"));
		  $r= $this->r2es($r,"/кс/i",array("x","ks","cs"));
		  $r= $this->r2es($r,"/ей/i",array("ey","ei"));
		  $r= $this->r2es($r,"/ов/i",array("ov","off"));
		  $r= $this->r2es($r,"/а/i",array("a"));
		  $r= $this->r2es($r,"/б/i",array("b"));
		  $r= $this->r2es($r,"/в/i",array("v","w"));
		  $r= $this->r2es($r,"/г/i",array("g"));
		  $r= $this->r2es($r,"/д/i",array("d"));
		  $r= $this->r2es($r,"/е/i",array("e"));
		  $r= $this->r2es($r,"/ё/i",array("yo","jo"));
		  $r= $this->r2es($r,"/ж/i",array("zh","z"));
		  $r= $this->r2es($r,"/з/i",array("z","s"));
		  $r= $this->r2es($r,"/и/i",array("i"));
		  $r= $this->r2es($r,"/й/i",array("j","y"));
		  $r= $this->r2es($r,"/к/i",array("k","c"));
		  $r= $this->r2es($r,"/л/i",array("l"));
		  $r= $this->r2es($r,"/м/i",array("m"));
		  $r= $this->r2es($r,"/о/i",array("o"));
		  $r= $this->r2es($r,"/н/i",array("n"));
		  $r= $this->r2es($r,"/п/i",array("p"));
		  $r= $this->r2es($r,"/р/i",array("r"));
		  $r= $this->r2es($r,"/с/i",array("s"));
		  $r= $this->r2es($r,"/т/i",array("t"));
		  $r= $this->r2es($r,"/у/i",array("u","oo"));
		  $r= $this->r2es($r,"/ф/i",array("f"));
		  $r= $this->r2es($r,"/х/i",array("h","kh"));
		  $r= $this->r2es($r,"/ц/i",array("c","ts"));
		  $r= $this->r2es($r,"/ч/i",array("ch"));
		  $r= $this->r2es($r,"/ш/i",array("sh"));
		  $r= $this->r2es($r,"/щ/i",array("shch","sch","sh"));
		  $r= $this->r2es($r,"/ъ/i",array(""));
		  $r= $this->r2es($r,"/ы/i",array("y"));
		  $r= $this->r2es($r,"/ь/i",array(""));
		  $r= $this->r2es($r,"/э/i",array("e"));
		  $r= $this->r2es($r,"/ю/i",array("u","yu","ju"));
		  $r= $this->r2es($r,"/я/i",array("ya","ja"));
		  return $r;
	}

	function r2es ($var, $pattern, $splits)
	{
		$sp=array(); $nsp=array();
		foreach ($var as $v) {
			if (preg_match($pattern,$v)) foreach ($splits as $split) $sp=array_merge($sp,array(preg_replace($pattern,$split,$v)));
			else $nsp=array_merge($nsp,array($v));
		}
		return array_merge($sp,$nsp);
	}

	function checkUnique($data, $fields) {
			if (!is_array($fields)) {
					$fields = array($fields);
			}
			foreach($fields as $key) {
					$tmp[$key] = $this->data[$this->name][$key];
			}
			if (isset($this->data[$this->name][$this->primaryKey])) {
					$tmp[$this->primaryKey] = "<>".$this->data[$this->name][$this->primaryKey];
			}
			return $this->isUnique($tmp, false);
	}


	function updateCounterCache($keys = array(), $created = false) {
		parent::updateCounterCache($keys, $created);
		$this->updateSumCache($keys, $created);
	}

	/**
	 * Updates the sumCache fields of belongsTo associations after a save or delete operation
	 * NB. This code has only been tested with a MySQL datasource. Due to the use of the SUM() function (ie. DBMS-specific SQL), it's reliability with other types of datasource is not guaranteed.
	 * @return void
	 * @access public
	 */
	function updateSumCache($keys = array(), $created = false) {
		if (empty($keys)) {
			$keys = $this->data[$this->alias];
		}
		foreach ($this->belongsTo as $parent => $assoc) {
			if (isset($assoc['sumCache'])) {
				if ($assoc['sumCache'] === true) {
					$assoc['sumCache'] = Inflector::underscore($this->alias) . '_sum';
				}
				if ($this->{$parent}->hasField($assoc['sumCache'])) {
					if (!isset($keys[$assoc['foreignKey']])) {
						break;
					}
					$conditions = array($this->escapeField($assoc['foreignKey']) => $keys[$assoc['foreignKey']]);
					if (isset($assoc['sumScope'])) {
						$conditions[] = $assoc['sumScope'];
					}

					if (!isset($assoc['sumField'])) {
						$assoc['sumField'] = 'amount'; // default name of field to sum
					}

					$fields = 'SUM('.$this->name.'.'.$assoc['sumField'].') AS '.$assoc['sumField'].'';
					$recursive = -1;
					list($edge) = array_values($this->find('first', compact('conditions', 'fields', 'recursive')));

					if (empty($edge[$assoc['sumField']])) {
						$sum = 0;
					} else {
						$sum = $edge[$assoc['sumField']];
					}

					$this->{$parent}->updateAll(
						array($assoc['sumCache'] => $sum),
						array($this->{$parent}->escapeField() => $keys[$assoc['foreignKey']])
					);
				}
			}
		}
	}

}
