<?php

/**
 * Class InvestVideo
 */
class InvestVideo extends AppModel {

	public $useTable = 'invest_video';

	public function addVideo($projectId, $data) {
		$data['InvestVideo']['project_id'] = (int) $projectId;
		$this->save($data);
		$this->clear();
	}
}