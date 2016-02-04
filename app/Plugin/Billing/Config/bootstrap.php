<?php
Braintree_Configuration::environment(env('BRAINTREE_ENVIRONMENT'));
Braintree_Configuration::merchantId(env('BRAINTREE_MERCHANT_ID'));
Braintree_Configuration::publicKey(env('BRAINTREE_PUBLIC_KEY'));
Braintree_Configuration::privateKey(env('BRAINTREE_PRIVATE_KEY'));

Configure::write('Billing.units', array(
	'bytes' => array(
		'unit' => 'bytes',
		'name' => __d('billing', 'Bytes'),
		'model' => 'StorageLimit',
		'field' => 'storage_limit',
		'scope' => 'user_id',
	),
	'members' => array(
		'unit' => 'members',
		'name' => __d('billing', 'Members'),
		'model' => 'GroupLimit',
		'field' => 'members_limit',
		'scope' => 'owner_id',
	),
	'proposals' => array(
		'unit' => 'proposals',
		'name' => __d('billing', 'Proposals'),
		'model' => 'UserEventRequestLimit',
		'field' => 'requests_limit',
		'scope' => 'user_id',
	),
	//'adverts' => array(
	//	'name' => __d('billing', 'Adverts')
	//),
));
