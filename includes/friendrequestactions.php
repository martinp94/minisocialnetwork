<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();
if(!$user->isLoggedIn())
	Redirect::to('../index.php');

$errors = [];

if(Input::exists())
{
	if(Token::check(Input::get('token')))
	{
		if(Input::existsItem('user_requested_friendship'))
		{

			$user_requested_friendship = Input::get('user_requested_friendship');

			if(Input::existsItem('accept'))
			{
				if($user->acceptFriendship($user_requested_friendship))
					$errors[] = "Successfully accepted friendship";
				else
					$errors[] = "Problem with accepting friendship";
			}

			if(Input::existsItem('deny'))
			{
				if($user->deleteFriendship($user_requested_friendship))
					$errors[] = "Successfully denied friendship";
				else
					$errors[] = "Problem with denied friendship";
			}
		}

		if(Input::existsItem('remove_user_id'))
		{

			$remove_user_id = Input::get('remove_user_id');

			if(Input::existsItem('unfriend'))
			{
				if($user->unfriend($remove_user_id))
				{
					$errors[] = 'Successfully unfriended';
					Session::put('errors', $errors);
					Redirect::to('../index.php?friends');
				}
				else
				{
					$errors[] = 'Problem unfriending';
					Session::put('errors', $errors);
					Redirect::to('../index.php?friends&requests');
				}
			}
		}
	}
}

Session::put('errors', $errors);
Redirect::to('../index.php?friends&requests');
