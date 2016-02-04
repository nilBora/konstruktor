<?php
//REST-like response for ajax calls
//Use it on your own risk yet.

if ($contentBlock = $this->fetch('content')):
	$contentBlock = json_decode($contentBlock, true);
elseif(isset($data)&&is_array($data)):
	$contentBlock = $data;
else:
	$contentBlock = $this->viewVars;
endif;

$user = $this->Session->read('Auth.User');
$_meta = array(
	'api' => array(
		'version' => '1.0.0',
		'deprecated' => false
	),
	'request' => array(
		'request_method'  => $_SERVER['REQUEST_METHOD'],
		'request_uri'     => $_SERVER['REQUEST_URI'],
		'server_protocol' => $_SERVER['SERVER_PROTOCOL'],
		'server_addr'     => $_SERVER['SERVER_ADDR'],
		'remote_addr'     => $_SERVER['REMOTE_ADDR'],
		'server_port'     => $_SERVER['SERVER_PORT'],
		'http_host'       => $_SERVER['HTTP_HOST'],
		'http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
		'request_time'    => $_SERVER['REQUEST_TIME'],
	),
	'credentials' => array(
		'username' => (isset($user['username']) ? $user['username'] : 'Guest'),
		'role' => (isset($user['Role']['title']) ? $user['Role']['title'] : 'Guest')
	)
);

if (isset($meta)&&is_array($meta)){
	$meta = Hash::merge($_meta, $meta);
} else {
	$meta = $_meta;
}

$response = array(
	'code' => 200,
	'url' => $this->request->here,
	'name' => (isset($title_for_layout) ? $title_for_layout : ''),
	'meta' => $meta,
	'data' => $contentBlock
);
if(Configure::read('debug') > 0){
	$response['debug']['request'] = $this->request;
	//$response['info'] = $_SERVER;
}
echo trim(json_encode($response));
