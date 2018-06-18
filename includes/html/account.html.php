<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$errors = array();
$user = new User();
$isYourAccount = true;
$db = Database::getInstance();

if(!$user->isLoggedIn())
{
	Redirect::to('index.php');
}

if(!Input::existsItem('account') || Input::get('account') == '')
{
	Redirect::to('index.php');
}

if($user->data()->username != Input::get('account'))
{
	$query = $db->get('users', array('username', '=', Input::get('account')));
	$errors[] = $query->count();
	Session::put('errors', $errors);
	if(!$query->count())
		Redirect::to('index.php');

	$user = new User($query->first()->id);
	$isYourAccount = false;
}

?>



<div class="container-fluid account-page">

	<div class="row">
		<div class="col-md-6">
			<div class="imgcontainer">
				<div id="profile_image">
					<img class="img-responsive" src="images/uploads/profile_images/<?php echo $user->data()->profile_image; ?>">
					<?php
					if($isYourAccount)
					{
					?>
						<div class="top-left"><span style="cursor:pointer"> Promjeni </span></div>
					<?php
					}
					?>
					
				</div>
				
				
			</div>


			<?php
				
			if($isYourAccount)
			{	
				

				include 'imageupload.html.php';
			}
			
			?>

		</div>

		<div class="col-md-6">
			<?php include 'accountdetails.html.php'; ?>
			
		</div>

	</div>
</div>


<script src="js/account.js">
	
</script>