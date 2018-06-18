<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}

$token = Token::generate();

$friends = array();

?>

<div class="row" style="margin-left: 100px;">
	<div class="btn-group btn-group-toggle" data-toggle="buttons">
	  <label class="btn btn-primary active">
	    <input type="radio" name="options" id="flist" autocomplete="off"> Lista prijatelja
	  </label>
	  <label class="btn btn-primary">
	    <input type="radio" name="options" id="frequests" autocomplete="off"> Zahtjevi za prijateljstvo
	  </label>
	</div>

</div>

<?php

if(isset($_GET['requests']))
{
	include 'friendrequests.html.php';
}
else
{


if(!$user->friends())
	echo "<section class='friends'> <h2 style='color:darkred;'> <strong> Nemaš ni jednog prijatelja </strong> </h2> </section>";
else
{

$friends = $user->friends();

foreach($user->friends() as $friend)
{
?>

<section class="friends">

	<div class="row">
		<div class="col-md-6">
		<img class="img-responsive" width="128" height="128" src="images/uploads/profile_images/<?php echo $friend->profile_image; ?>">
		</div>

		<div class="col-md-2">
			<img src="images/account details/name_surname.png" title="Ime i prezime" alt="Ime i prezime" width="64">
		</div>

		<div class="col-md-4">
			<a href="index.php?account=<?php echo $friend->username; ?>"> <p><?php echo $friend->fname . ' ' . $friend->lname; ?></p> </a>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
		
		</div>

		<div class="col-md-2">
			<img src="images/account details/add_friend.png" title="Dodat za prijatelja" alt="Dodat za prijatelja" width="64">
		</div>

		<div class="col-md-4">
			<p><?php echo $friend->friendship_started; ?></p>
		</div>

	</div>

	<form class="form-horizontal row" method="POST" action="includes/friendrequestactions.php">
		
		<div class="col-md-8">
			
		</div>

		<div class="col-md-2">
			<input type="submit" name="unfriend" value="Ukloni iz prijatelja" class="btn btn-danger form-control">
		</div>

		<input type="hidden" name="remove_user_id" value="<?php echo $friend->us_id; ?>">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
	</form>
	
	
</section>


<?php
}
}
}
?>


<script>

	(() => {

		const labels = document.querySelectorAll(".btn-group > label");

		if(location.href.includes('friends')) {
			if(location.href.includes('requests')) {
				labels[0].classList.remove('active');
				labels[1].classList.add('active');
			} else {
				labels[1].classList.remove('active');
				labels[0].classList.add('active');
			}
		}

		[].map.call(labels, (elem) => {
			elem.addEventListener('click', eventHandler , false);
		});

		function eventHandler(e) {
			const radioInput = document.querySelectorAll("input[type='radio']");

			console.log(e.currentTarget.children[0].checked = true);

			if(radioInput[0].checked)
				location.href = 'index.php?friends';
			else if(radioInput[1].checked)
				location.href = 'index.php?friends&requests';
		}

	})();

</script>
