<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();
if(!$user->isLoggedIn())
	Redirect::to('../index.php');

$db = Database::getInstance();

if(Input::exists()) 
{
	if(Token::check(Input::get('token'))) 
	{
		if(Input::get('activation_code') && Input::get('uid'))
		{
			$validate = new Validate();
			$validation = $validate->check($_POST, array(

				'activation_code' => array(
					'required' => true,
					'min' => 6,
					'max' => 6
				)
			));

			if($validation->passed()) 
			{

				if($user->data()->activation_code != Input::get('activation_code'))
				{
					Redirect::to('../index.php');
					die();
				}
				
				$fields = array(
					'activated' => true
				);

				$db->update('users', Input::get('uid'), $fields);

			}
		}
	}
}

Redirect::to('../index.php');
