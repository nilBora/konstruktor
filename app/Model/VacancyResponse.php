<?
App::uses('AppModel', 'Model');
class VacancyResponse extends AppModel {
	public $name = 'VacancyResponse';
	public $useTable = 'vacancy_response';

	public function timelineEvents ($currUserID, $date, $date2, $view) {
		$this->loadModel('GroupVacancy');
		
		$createDateRange =  $this->dateRange('VacancyResponse.created', $date, $date2);
		$modifyDateRange =  $this->dateRange('VacancyResponse.modified', $date, $date2);
		$conditions = array(
			'OR' => array(
				$createDateRange, $modifyDateRange
			),
			'VacancyResponse.user_id' => $currUserID
		);
		$aResponses = $this->find('all', compact('conditions'));

		$aVacancies = $this->GroupVacancy->findAllById( Hash::extract($aResponses, '{n}.VacancyResponse.vacancy_id') );
		$aVacancies = Hash::combine($aVacancies, '{n}.GroupVacancy.id', '{n}');

		foreach($aResponses as &$response) {
				$response['VacancyResponse']['title'] = $aVacancies[$response['VacancyResponse']['vacancy_id']]['GroupVacancy']['title'];
				$response['VacancyResponse']['group_id'] = $aVacancies[$response['VacancyResponse']['vacancy_id']]['GroupVacancy']['group_id'];
		}
		return $aResponses;
	}
}