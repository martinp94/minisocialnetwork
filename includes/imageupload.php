<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$db = Database::getInstance();
$errors = array();
$user = new User();
$tableName = 'users';

if(!$user->isLoggedIn())
{
	Redirect::to('../index.php');
}


// set variables
$dir_dest = (Input::existsItem('dir') ? Input::get('dir') : '../images/uploads/');

if(isset($_GET['userimg'])){
	$dir_dest .= "profile_images/";
	$tableName = 'users';
}

$dir_pics = (Input::existsItem('pics') ? Input::get('pics') : $dir_dest);

$action = Input::existsItem('action') ? Input::get('action') : '';

if ($action == 'image') 
{

	$handle = new Upload($_FILES['imageToUpload']);

	if($handle->uploaded)
	{
		if($handle->file_is_image)
		{

			$handle->file_new_name_body = $user->data()->username . 'profileimg' . time() . strtolower(generate_random_string(9));
			$handle->file_max_size = '1048576';

			$handle->image_resize = true;
			$handle->image_ratio_x = true;
			$handle->image_y = 300;

			$handle->Process($dir_dest);

			if($handle->processed)
			{
				$errors[] = 'Image uploaded successfully';
				$db->update($tableName, Input::get('tableid'), array('profile_image' => $handle->file_dst_name));
			}
			else
			{
				$errors[] = $handle->error;
			}

		}
		else
		{
			$errors[] = 'File is not an image';
		}

		
	}
	else
	{
		$errors[] = $handle->error;
	}
}

Session::put('errors', $errors);
Redirect::to('../index.php?account=' . $user->data()->username);