<?php

use AD7six\Dsn\Wrapper\CakePHP\V2\EmailDsn;

class EmailConfig {

	public $smtp = array(
		'transport' => 'Smtp',
		'from' => array('support@konstruktor.com' => 'Konstruktor.com'),
		'host' => 'ssl://smtp.yandex.ru',
		'port' => 465,
		//'timeout' => 30,
		'username' => 'support@konstruktor.com',
		'password' => 'qwertyuiop',
		'emailFormat' => 'html',
		'client' => null,
		'log' => false,
		'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);


/**
 * Define connections using environment variables
 *
 * @return void
 */
	public function __construct() {
		$this->default = EmailDsn::parse(env('EMAIL_URL'));
		$this->postmark = EmailDsn::parse(env('EMAIL_POSTMARK_URL'));
	}

}
