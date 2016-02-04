<?php
$response = array(
	'User' => $user['User'],
	'UserMedia' => $user['UserMedia'],
	'ChatContact' => $user['ChatContact'],
	'aUsers' => $aUsers
);
echo json_encode($response);
