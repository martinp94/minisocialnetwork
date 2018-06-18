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
		if(Input::get('password_current') && Input::get('password_new') && Input::get('password_new_again'))
		{
			$validate = new Validate();
			$validation = $validate->check($_POST, array(

				'password_current' => array(
					'required' => true,
					'min' => 8,
					'max' => 46
				),
				'password_new' => array(
					'required' => true,
					'min' => 8,
					'max' => 46
				),
				'password_new_again' => array(
					'required' => true,
					'min' => 8,
					'max' => 46,
					'matches' => 'password_new'
				)

			));

			if($validation->passed()) 
			{
				$current_password = Input::get('password_current');
				$new_password = Input::get('password_new');
				
				if($user->checkPassword($current_password))
				{
					
					try{
						
						$user->update(array('password' => Hash::make($new_password, $user->data()->salt)));
						$user->logout();	

					} catch(Exeption $e) {
					
					}

				}
				else
				{
					Session::put('errors', array('update_error' => 'passwords doesnt match'));
				}
			}
			
		}
		
	}
	
}




Redirect::to('../index.php');