<?
App::uses('AppHelper', 'View/Helper');
App::uses('TimeHelper', 'View/Helper');
class PHTimeHelper extends TimeHelper {
	function niceShort($dateString = null, $timezone = null) {
		if (!$dateString) {
			return '-';
		}
		$ret = parent::niceShort($dateString, $timezone);

		$aReplaceENG = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jul', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		$aReplace = array(__('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jul'), __('Jun'), __('Aug'), __('Sep'), __('Oct'), __('Nov'), __('Dec'));
		return $ret;
		return str_replace(array('st', 'nd', 'th'), '', str_replace($aReplaceENG, $aReplace, $ret));
	}
}