<?
App::uses('AppModel', 'Model');
class ArticleCategory extends AppModel {
	public $name = 'ArticleCategory';
	public $useTable = false;
	
	public function options() {
		return array('', __('Politics'), __('Economics'), __('Community'), __('Culture'), __('Sports'), __('Hi-tech'), __('Cars'), __('Fashion'), __('Games'), __('Health'), __('Other'), __('Travel'));
	}
	
}