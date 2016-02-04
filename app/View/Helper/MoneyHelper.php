<?php
App::uses('AppHelper', 'View/Helper');

class MoneyHelper extends AppHelper
{
	private $_symbols = array(
		'USD' => '$',
		'EUR' => '&#8364;',
		'RUB' => 'P',
	);

	public function symbols()
	{
		return $this->_symbols;
	}

	public function symbolFor($code)
	{
		return array_key_exists($code, $this->_symbols) ? $this->_symbols[$code] : null;
	}

	public function format($number) {
		if ($this->viewVar('currUser.User.lang') == 'rus') {
			return number_format($number, 2, ',', ' ');
		} else {
			return number_format($number, 2, '.', ' ');
		}
	}
}
