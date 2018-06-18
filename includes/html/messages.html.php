<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';



$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}

?>

<div class="container">
	<div class="row messages">
		<div class="col-md-2">
			<div class="row">
				<a id="newMessage" href="index.php?messages&new">Nova poruka</a>
			</div>

			<div class="row">
				<a id="inbox" href="index.php?messages&inbox">Prijem</a>
			</div>

			<div class="row">
				<a id="outbox" href="index.php?messages&sent">Poslato</a>
			</div>
		</div>

		<div id="msgContainer" class="col-md-10">

			<?php

			if(isset($_GET['new']))
			{
				include 'newMessageForm.html.php';
			}
			else if(isset($_GET['inbox']))
			{
				include 'inbox.html.php';
			}
			else if(isset($_GET['sent']))
			{
				include 'sent.html.php';
			}


			?>
			
		</div>
	</div>
</div>

<script src="js/messenger.js">

</script>