<?
App::uses('AppModel', 'Model');
class Skill extends AppModel {
	public $name = 'Skill';
	
	public function autocompleteOptions($lang = 'eng') {
		try {
			
			$aSkill = $this->find('all');
			$aSkill = Hash::combine($aSkill, '{n}.Skill.id', '{n}.Skill.'.$lang);
			
			$return = array();
			foreach($aSkill as $data => $value ) {
				if($value != null)
				array_push($return, compact('data', 'value') );
			}
		
			return json_encode($return);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
	
}