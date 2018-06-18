<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';


if(Input::exists()) 
{
	if(Token::check(Input::get('token'))) 
	{
		if(Input::get('username') && Input::get('email') && Input::get('password') && Input::get('passwordAgain') && Input::get('fname') && Input::get('lname') && Input::get('birth_date'))
		{

			$validate = new Validate();
			$validation = $validate->check($_POST, array(

				'username' => array(
					'required' => true,
					'unique' => true,
					'min' => 6,
					'max' => 20
				),
				'password' => array(
					'required' => true,
					'min' => 8,
					'max' => 46
				),
				'passwordAgain' => array(
					'required' => true,
					'min' => 8,
					'max' => 46,
					'matches' => 'password'
				),
				'email' => array(
					'required' => true,
					'unique' => true,
					'min' => 10,
					'max' => 46
				),
				'fname' => array(
					'required' => true,
					'min' => 3,
					'max' => 20
				),
				'lname' => array(
					'required' => true,
					'min' => 3,
					'max' => 20
				),
				'birth_date' => array(
					'required' => true,
					'minAge' => 3
				)

			), 'users');

			if($validation->passed() && empty(Session::get('errors'))) 
			{
				$salt = Hash::salt(32);
				$actCode = generate_random_string();

				$fields = array(
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'email' => Input::get('email'),
					'activated' => false,
					'salt' => $salt,
					'fname' => Input::get('fname'),
					'lname' => Input::get('lname'),
					'joined' => date('Y-m-d H:i:s'),
					'birth_date' => Input::get('birth_date'),
					'activation_code' => $actCode,
					'profile_image' => 'profile_default.png'
				);



				$user = new User();

				$mailSender = new MailSender();
				$mailSender->send_mail(Input::get('email'), 'Aktivacioni kod: ' . $actCode);

				if($user->register($fields)) 
				{
					$user->login(Input::get('username'), Input::get('password'));
				}

			}
		}
	}
}

Redirect::to('../index.php');
