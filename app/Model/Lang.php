<?php
App::uses('AppModel', 'Model');
App::import('Core', 'l10n');

class Lang extends AppModel {
	public $useTable = false;
    public $default = 'eng';

	public function options() {
		$langs = array(
			'eng' => 'English',
			'rus' => 'Русский',

			'afr' => 'Afrikaans',
			'alb' => 'Shqiptar',
			'apd' => 'Sunda',
			'ara' => 'العربية',
			'arm' => 'հայերեն',
			'aze' => 'Azərbaycan',
			'baq' => 'Euskal',
			'bel' => 'Беларуская',
			'ben' => 'বাঙালি',
			'bos' => 'Bosanski',
			'bul' => 'Български',
			'bur' => 'မြန်မာ',
			'cat' => 'Català',
			//'ceb' => 'Sebuanskij',
			'cym' => 'Cymraeg',
			'cze' => 'Čeština',
			'dut' => 'Dansk',
			'epo' => 'Esperanta',
			'est' => 'Eesti',
			'fin' => 'Suomi',
			'fre' => 'Français',
			'geo' => 'ქართული',
			'ger' => 'Deutsch',
			'gle' => 'Irish',
			'glg' => 'Galego',
			'gre' => 'ελληνικά',
			'guj' => 'ગુજરાતી',
			'hat' => 'Kreyòl (Ayiti)',
			'hau' => 'House',
			'heb' => 'עברית',
			'hin' => 'हिंदी',
			'hmn' => 'Hmong',
			'hrv' => 'Hrvatski',
			'hun' => 'Magyar',
			'ibo' => 'Igbo',
			'ice' => 'Icelandic',
			'ind' => 'Bahasa Indonesia',
			'ita' => 'Italiano',
			'jav' => 'Jawa',
			'jpn' => '日本人',
			'kan' => 'ಕನ್ನಡ',
			'kaz' => 'Қазақ',
			'khm' => 'ខ្មែរ',
			'kor' => '한국의',
			'lao' => 'ລາວ',
			'lat' => 'Latine',
			'lav' => 'Latvijas',
			'lit' => 'Lietuvos',
			'mac' => 'Macedonike',
			'mal' => 'മലയാളം',
			'mao' => 'Maori',
			'mar' => 'मराठी',
			'may' => 'Melayu',
			'mlg' => 'Malagasy',
			'mlt' => 'Malti',
			'mon' => 'Монгол улсын',
			'nep' => 'नेपाली',
			'nld' => 'Nederlands',
			'nor' => 'Norsk',
			//'nya' => 'Chev',
			'pan' => 'ਪੰਜਾਬੀ ਦੇ',
			'per' => 'فارسی',
			'pol' => 'Polski',
			'por' => 'Português',
			'rum' => 'Român',
			'sin' => 'සිංහල',
			'slk' => 'Slovenčina',
			'slv' => 'Slovenščina',
			'som' => 'Somalia',
			//'sot' => '-sinhali',
			'spa' => 'Español',
			'srp' => 'Сербскии',
			'swa' => 'Kiswahili',
			'swe' => 'Svenska',
			'tai' => 'ไทย',
			'tam' => 'தமிழ்',
			'tel' => 'తెలుగు',
			'tgk' => 'Тоҷикистон',
			'tgl' => 'Tagalog',
			'tur' => 'Türk',
			'ukr' => 'Українська',
			'urd' => 'اردو',
			'uzb' => 'O\'zbekiston',
			'vie' => 'Tiếng Việt',
			'yid' => 'ייִדיש',
			'yor' => 'Yoruba',
			'lzh' => '中國（繁體)',
			'zho' => '中国（简体)',
			'zul' => 'Zulu',
		);
		return $langs;
	}

	public function detect() {
        $langs = $this->options();
        if(isset($_COOKIE['wLang']) && isset($langs[$_COOKIE['wLang']])) {
            return $_COOKIE['wLang'];
        }

		$lang = '';
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
			$negotiation = new ptlis\ConNeg\Negotiation();
	        $lang = $negotiation->languageBest(
	            $_SERVER['HTTP_ACCEPT_LANGUAGE'],
	            'application/json;q=1,application/xml;q=0.7,text/html;q=0.3'
	        );
		}

        if(!$lang) {
            return $this->default;
        }

        $l10 = new l10n;
        $map = $l10->catalog($lang);
        if($map && isset($map['localeFallback'])) {
            return $map['localeFallback'];
        }

        return $this->default;
	}


    public function setLang($lang) {
        $langs = $this->options();
        if(!isset($langs[$lang])) {
            $lang = $this->detect();
        }
        setcookie('wLang', $lang, time() + 30 * 24 * 60 * 60, '/');
        return $lang;
    }
}

/*
взято из RedMine - страница настроек аккаунта
<select id="user_language" name="user[language]"><option value="">(auto)</option>
<option value="ar">Arabic (عربي)</option>
<option value="bg">Bulgarian (Български)</option>
<option value="bs">Bosanski</option>
<option value="ca">Català</option>
<option value="cs">Čeština</option>
<option value="da">Danish (Dansk)</option>
<option value="de">Deutsch</option>
<option value="el">Ελληνικά</option>
<option value="en">English</option>
<option value="en-GB">English (British)</option>
<option value="es">Español</option>
<option value="et">Estonian (Eesti)</option>
<option value="eu">Euskara</option>
<option value="fa">Persian (پارسی)</option>
<option value="fi">Finnish (Suomi)</option>
<option value="fr">Français</option>
<option value="gl">Galego</option>
<option value="he">Hebrew (עברית)</option>
<option value="hr">Hrvatski</option>
<option value="hu">Magyar</option>
<option value="id">Indonesia</option>
<option value="it">Italiano</option>
<option value="ja">Japanese (日本語)</option>
<option value="ko">한국어(Korean)</option>
<option value="lt">Lithuanian (lietuvių)</option>
<option value="lv">Latvian (Latviešu)</option>
<option value="mk">Macedonian (Македонски)</option>
<option value="mn">Mongolian (Монгол)</option>
<option value="nl">Nederlands</option>
<option value="no">Norwegian (Norsk bokmål)</option>
<option value="pl">Polski</option>
<option value="pt">Português</option>
<option value="pt-BR">Português(Brasil)</option>
<option value="ro">Română</option>
<option value="ru" selected="selected">Russian (Русский)</option>
<option value="sk">Slovenčina</option>
<option value="sl">Slovenščina</option>
<option value="sq">Albanian (Shqip)</option>
<option value="sr">Српски</option>
<option value="sr-YU">Srpski</option>
<option value="sv">Svenska</option>
<option value="th">Thai (ไทย)</option>
<option value="tr">Türkçe</option>
<option value="uk">Ukrainian (Українська)</option>
<option value="vi">Tiếng Việt</option>
<option value="zh">Simplified Chinese (简体中文)</option>
<option value="zh-TW">Traditional Chinese (繁體中文)</option></select>
*/
