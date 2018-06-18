<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$size = 0;
$uploadOk = 0;
$target_file = null;
$target_dir = "../images/uploads/";
$db = Database::getInstance();
$errors = array();
$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}

if(isset($_GET['userimg'])){
	$target_dir .= "profile_images/";
	$uploadFor = 'user';
}

if(Input::get('token')) 
{
	if(Input::get('size'))
	{
		if(isset($_FILES['imageToUpload'])) 
		{
			$file_name = $_FILES['imageToUpload']['name'];
			
			if($_FILES['imageToUpload']['error'] !== UPLOAD_ERR_OK)
			{
				
				if($_FILES['imageToUpload']['error'] === UPLOAD_ERR_INI_SIZE)
				{
					$errors[] = 'File je prevelik (MAX_UPLOAD_FILE_SIZE)';
					Session::put('errors', $errors);
					Redirect::to('../index.php?account');
					
				}
				
			}

			$size = $_FILES['imageToUpload']['size'];

				if($size > Input::get('size'))
				{
					$uploadOk = 0;
					$errors[] = 'File je prevelik : ' . ($size / 1048576) . ' MB , dozvoljeno: 1MB';
				} 
				else 
				{
					$imageFileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

					$extensions = array('jpg', 'png', 'jpeg', 'gif');

					if(in_array($imageFileType, $extensions) === false) 
					{
	    				$uploadOk = 0;
	    				$errors[] = 'Dozvoljene ekstenzije: jpg, jpeg, png, gif';
	    			}
	    			else 
	    			{
	    				$file_name_to_save = '';
					
						if($uploadFor == 'user') 
						{
							$file_name_to_save = $user->data()->username . 'profileimg' . time() . strtolower(generate_random_string(9)) . '.' . $imageFileType;
						}

						$target_file = $target_dir . basename($file_name_to_save);

						$errors[] = $target_file;
						
						if(file_exists($target_file)) 
						{
							$uploadOk = 0;
							$errors[] = 'File postoji';
						} 
						else 
						{
							if(move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file)) 
							{

	        					$errors[] = "File ". basename($file_name_to_save). " je uploadovan na server.";

	        					if($uploadFor == 'user') 
	        					{
									$db->update('users', $user->data()->id, array(
										'profile_image' => $file_name_to_save
									));
	        					} 
							} 
							else
							{
								$errors[] = "Greška prilikom čuvanja slike.";
	        					
							}
						}
	    			}
	    		}
		}
		
	}
}
else
{
	$errors[] = 'Desio se problem prilikom uploadovanja file-a';
}


Session::put('errors', $errors);

Redirect::to('../index.php?account');

?>