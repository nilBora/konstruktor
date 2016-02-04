<?
App::uses('AppModel', 'Model');
class Country extends AppModel {

	public function options() {
		$fields = array('country_code', 'country_name');
		$order = 'country_name';
		$aRows = $this->find('list', compact('fields', 'order'));
		//список стран сократили - глючит в сафари - костыль
		/*$aRows = array(
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BR' => 'Brazil',
			'BG' => 'Bulgaria',
			'CA' => 'Canada',
			'CL' => 'Chile',
			'CN' => 'China',
			'CO' => 'Colombia',
			'HR' => 'Croatia',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'EG' => 'Egypt',
			'EE' => 'Estonia',
			'FI' => 'Finland',
			'FR' => 'France',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GR' => 'Greece',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'IE' => 'Ireland',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JP' => 'Japan',
			'KZ' => 'Kazakhstan',
			'LV' => 'Latvia',
			'LT' => 'Lithuania',
			'MX' => 'Mexico',
			'ME' => 'Montenegro',
			'NL' => 'Netherlands',
			'NZ' => 'New Zealand',
			'NO' => 'Norway',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'RO' => 'Romania',
			'RU' => 'Russia',
			'RS' => 'Serbia',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'ZA' => 'South Africa',
			'KR' => 'South Korea',
			'ES' => 'Spain',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'TR' => 'Turkey',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'UK' => 'United Kingdom',
			'US' => 'United States');*/
		foreach($aRows as $code => &$title) {
			$title = __d('geo', $title);
			// file_put_contents('geo.po', 'msgid "'.$title.'"'."\r\n".'msgstr "rus:'.$title.'!"'."\r\n\r\n", FILE_APPEND);
		}
		return $aRows;
	}

	public function getMainCountries(){
		$fields = array('country_code', 'country_name');
		$conditions = array('show_main'=>1);
		$order = 'country_name';
		$aRows = $this->find('list', compact('fields','conditions', 'order'));
		foreach($aRows as $code => &$title) {
			$title = __d('geo', $title);
		}
		return $aRows;
	}

}
