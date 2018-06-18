<?php
	require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';
	$user = new User();

	if(!$user->isLoggedIn())
	{
		if(!Session::exists('chooseRegOrLogin'))
		{
			Session::put('chooseRegOrLogin', array('Imate nalog?', '?login'));
		}

		$chooseRegOrLogin = isset($_GET['login']) ? ['Registracija', '?registracija'] : ['Imate nalog?', '?login'];

		Session::put('chooseRegOrLogin', $chooseRegOrLogin);
	} 
	else
	{
		Session::delete('chooseRegOrLogin');
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Mini social network</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>


	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style/style.css">

</head>
<body>

	<div class="jumbotron row">
		<?php
		if(Session::exists('chooseRegOrLogin'))
		{
		?>
		<div class="col-md-2">
			<a href="<?php echo Session::get('chooseRegOrLogin')[1]; ?>"><?php echo Session::get('chooseRegOrLogin')[0]; ?></a>
		</div>
			
		<?php
		}


		if($user->isActivated() === '')
		{
		?>

		<div class="col-md-3">
			<a href="index.php"><img src="images/app/minisocialnetwork.png"></a>
		</div>
		
		<div class="col-md-2" style="margin-right: 20%;">
			<div class="">
				<input id="searchText" type="text" name="searchText"  style="border: 0px;" maxlength="30">
				<button id="searchButton"><img class="img-responsive" src="images/actions/search_people.png"></button>
			</div>
		</div>
		

		<div class="col-md-1">
			
			  <div class="imgcontainer">
			    
					<img width="100px;"; class="rounded-circle border" src="images/uploads/profile_images/<?php echo $user->data()->profile_image; ?>">
				
			  </div>

			 
			
		</div>

		<div class="col-md-1">
			<?php
			}

			if($user->isLoggedIn())
			{


			?>

				<div style="top: 5px;";">
					<a style="text-decoration: none;" href="?account=<?php echo $user->data()->username; ?>">
					<span><strong> <h3> <?php echo $user->data()->fname . ' <span style="color: red;">' . $user->isActivated() . '</span>'; ?></h3></strong> </span>
					</a>
				</div>

			<?php
			}
			if($user->isActivated() === '')
			{
			?>

		</div>
		 
		
		<div class="col-md-1">

			<div class="btn-group">
			  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <img src="images/actions/settings.png">
			  </button>
			  <div class="dropdown-menu">
			    <a class="dropdown-item" href="includes/logout.php">Izloguj se</a>
			    <a class="dropdown-item" href="?changepassword">Promjeni lozinku</a>
			    <a class="dropdown-item" href="?messages">Poruke</a>
			    <a class="dropdown-item" href="?friends">Prijatelji</a>
			  </div>
			</div>		
		</div>
		<?php
		}
		?>
		


	</div>

<?php 

if(!$user->exists())
{
	if(isset($_GET['login']))
		include 'includes/html/login.html.php'; 
	else
		include 'includes/html/registracija.html.php'; 
}
else
{
	if($user->isActivated() === '')
	{
		include 'includes/html/searchResults.html.php';
		
		if(isset($_GET['account']))
		{
			include 'includes/html/account.html.php';
		}
		else if(isset($_GET['changepassword']))
		{
			include 'includes/html/passwordchange.html.php';
		}
		else if(isset($_GET['messages']))
		{
			include 'includes/html/messages.html.php';
		}
		else if(isset($_GET['friends']))
		{
			include 'includes/html/friends.html.php';
		}
		else
		{

			include 'includes/html/wall.html.php';
		}
		
	}
	else
	{
		include 'includes/html/activation.html.php'; 
	}
}


print_r(Session::get('errors'));
Session::put('errors', array());

?>






<script src="js/plugins/textarea resizer/textareaResizer.js"></script>
<script src="js/search.js"></script>
</body>
</html>