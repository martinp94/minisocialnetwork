<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();
if($user->exists())
	$user->logout();
Redirect::to('../index.php');