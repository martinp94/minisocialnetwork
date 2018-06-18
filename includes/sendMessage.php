<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';


$user = new User();
if(!$user->isLoggedIn())
	Redirect::to('../index.php');

$db = Database::getInstance();
$errors = array();

$messenger = new Messenger($user->data()->id);

if(Input::exists()) 
{
	
	if(Token::check(Input::get('token')))
	{
		
		if(Input::existsItem('message_text'))
		{

			$validate = new Validate();
			$validation = $validate->check($_POST, array(

				'message_text' => array(
					'required' => true,
					'min' => 6
				),
				'to_username' => array(
					'required' => true
				)

			));

			if($validation->passed()) 
			{

				$to_username = Input::get('to_username');
				if($messenger->sendMessage(Input::get('message_text'), $to_username))
					$errors[] = 'Message sent!';
				else
					$errors[] = 'Error: User doesnt exists';
			}
			else
			{
				$errors = $validation->errors();
			}

		}
		else
		{
			$errors[] = 'invalid input';
		}
			
	}
	else
	{
		$errors[] = 'no token';
	}

}
else
{
	$errors[] = 'no input';
}

Session::put('errors', $errors);
Redirect::to('../index.php?messages');
