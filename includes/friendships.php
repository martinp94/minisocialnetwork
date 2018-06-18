<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';


$user = new User();
if(!$user->isLoggedIn())
	Redirect::to('../index.php');

$db = Database::getInstance();
$errors = array();

if(isset($_POST['data'])) 
{
	$username_to = $_POST['data'];

	$to_id = User::getIdFromUsername($username_to);

	$user->addFriend($to_id);

	
}