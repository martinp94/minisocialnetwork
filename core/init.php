<?php

session_start();
ob_start();

$GLOBALS['config'] = array(

	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'minisocialnetwork'
	),

	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),

	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)

);



spl_autoload_register(function($class){
	require_once 'C:/xampp/htdocs/minisocialnetwork/classes/' . $class . '.php';
});

if(!Session::exists('errors'))
	Session::put('errors', array());

require_once 'C:/xampp/htdocs/minisocialnetwork/functions/sanitize.php';
require_once 'C:/xampp/htdocs/minisocialnetwork/functions/generate_random_string.php';

if(Cookie::exists(Config::get('remember/cookie_name')))
{
	if(!Session::exists(Config::get('session/session_name')))
	{
		$db = Database::getInstance();
		$hash = Cookie::get(Config::get('remember/cookie_name'));

		$user_session = $db->get('users_sessions', array('hash', '=', $hash));
		if(count($user_session))
		{
			
			$user = new User($user_session->first()->user_id);
			$user->login();
		}
	}
}