<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();
if($user->isLoggedIn())
	Redirect::to('../index.php');

$db = Database::getInstance();

if(Input::exists()) 
{
	if(Token::check(Input::get('token'))) 
	{
		if(Input::get('username') && Input::get('password'))
		{
			$validate = new Validate();
			$validation = $validate->check($_POST, array(

				'username' => array(
					'required' => true,
					'min' => 6,
					'max' => 20
				),
				'password' => array(
					'required' => true,
					'min' => 8,
					'max' => 46
				)

			));

			if($validation->passed()) 
			{
				$remember = (Input::get('remember') === 'on') ? true : false;
				$user->login(Input::get('username'), Input::get('password'), $remember);
			}
		}
	}
}

Redirect::to('../index.php');