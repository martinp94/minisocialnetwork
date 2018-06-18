<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}

$messenger = new Messenger($user->data()->id);

$to = isset($_GET['to']) ? $_GET['to'] : '';

$message = isset($_POST['message_text']) ? $_POST['message_text'] : '';

if($message) 
	Session::put('message', array(0 => $message, 1 => 2));


?>

<form method="post" action="includes/sendMessage.php">

	<?php

	if(count(Session::exists('errors')))
	{

		$errors = Session::get('errors');
		foreach($errors as $error)
		{
			echo "<strong style='color: darkred;'> " . $error .  " </strong>";
		}
	}

	?>

	<div class="form-group row">

		<div class="col-md-3">
			<label for="to_username"></label>
	   		<input type="text" class="form-control" name="to_username" placeholder="Unesite korisničko ime" value="<?php echo $to; ?>">
		</div>
	    
	</div>

	<div class="form-group row"> 
		<div class="col-md-6">
			<textarea class="form-control" style="padding-top: 10px;" name="message_text" id="message_text" rows="2" cols="84" placeholder="Poruka..."><?php 
				if(Session::exists('message'))
				{
					if(Session::get('message')[1] > 0) 
					{
						$message_text = Session::get('message')[0];
						echo $message_text;

						$cnt = Session::get('message')[1];
						$cnt--;
						Session::put('message', array(0 => $message_text, 1 => $cnt));
					}
					else
						Session::delete('message');
				}
			?></textarea>
		</div>
		
	</div>

	<div class="form-group row">
		<div class="col-md-4">
	
		</div>
		
		<div class="col-md-2">
			<button id="postsubmit" class="btn btn-primary form-control" type="submit">Pošalji</button>

		</div>
	</div>

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

</form>