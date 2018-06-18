<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';


$user = new User();
if(!$user->isLoggedIn())
	Redirect::to('../index.php');

$db = Database::getInstance();
$errors = array();

if(Input::exists()) 
{
	
	if(Token::checkAjax(Input::get('token')))
	{
		
		if(Input::existsItem('status_text'))
		{

			$validate = new Validate();
			$validation = $validate->check($_POST, array(

				'status_text' => array(
					'required' => true,
					'min' => 6
				)

			));

			if($validation->passed()) 
			{


				$comment_to = Input::existsItem('to') === true ? Input::get('to') : null;

				$to_user = Input::get('to_user') === 'null' ? null : $db->get('users', array('username', '=', Input::get('to_user')))->first()->id;

				$to_username = Input::get('to_user') === 'null' ? null : Input::get('to_user');

				$errors[] = $to_user;



				$errors[] = $comment_to;

				$fields = array(
					'status_text' => Input::get('status_text'),
					'user_id' => $user->data()->id,
					'post_date' => date('Y-m-d H:i:s'),
					'comment_to' => $comment_to,
					'to_user' => $to_user,
					'to_username' => $to_username
				);

				if($db->insert('status', $fields))
				{
					$errors[] = 'status uploaded successfully';

					$isComment = $to_user === null ? true : false;

					
					echo json_encode(Posts::getLastestPostFromUser($user->data()->id, $isComment));
					
				}
				else
				{
					$errors[] = 'problem with saving status in the database';
				}
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
		//echo json_encode('toka' => Session::get(Config::get('session/token_name')));
	}

}
else
{
	$errors[] = 'no input';
}

Session::put('errors', Session::get('token'));
//Redirect::to('includes/html/wall.html.php');
