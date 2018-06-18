<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}

$requests = $user->friends(0);

if(!$requests)
	echo "<section class='friends'> <h2 style='color:darkred;'> <strong> Nema novih zahtjeva za prijateljstvo </strong> </h2> </section>";
else
{
	foreach ($requests as $request) {
		if($request->user_accepted == $user->data()->id)
		{


?>

<section class="friends">

	<div class="row">
		<div class="col-md-6">
		<img class="img-responsive" width="128" height="128" src="images/uploads/profile_images/<?php echo $request->profile_image; ?>">
		</div>

		<div class="col-md-2">
			<img src="images/account details/name_surname.png" title="Ime i prezime" alt="Ime i prezime" width="64">
		</div>

		<div class="col-md-4">
			<p><?php echo $request->fname . ' ' . $request->lname; ?></p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
		
		</div>

		<div class="col-md-2">
			<img src="images/account details/add_friend.png" title="Dodat za prijatelja" alt="Dodat za prijatelja" width="64">
		</div>

		<div class="col-md-4">
			<p><?php echo $request->friendship_started; ?></p>
		</div>

	</div>
		
	<form class="form-horizontal row" method="POST" action="includes/friendrequestactions.php">
		
		<div class="col-md-8">
			
		</div>

		<div class="col-md-2">
			<input type="submit" name="accept" value="Prihvati" class="btn btn-success form-control">
		</div>

		<div class="col-md-2">
			<input type="submit" name="deny" value="Odbij" class="btn btn-danger form-control">
		</div>

		<input type="hidden" name="user_requested_friendship" value="<?php echo $request->us_id; ?>">
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		
	</form>
		


	
	
</section>

<?php
		}
	}
?>

<script>
	(() => {
		if(document.querySelectorAll("section").length === 0) {
			const section = document.createElement('section');
			section.classList.add('friends');
			section.innerHTML = "<h2 style='color:darkred;'> <strong> Nema novih zahtjeva za prijateljstvo </strong> </h2>";
			document.body.append(section);
		}
	})();
</script>


<?php
}
?>
