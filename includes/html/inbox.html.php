<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}

$messenger = new Messenger($user->data()->id);

?>

<h3>Prijem</h3>
<hr>


<?php 

$messages = $messenger->receivedMessages();

foreach ($messages as $message) 
{
?>
	<section>
		<div class="row">
			<div class="col-md-7">
				Od: <a href="index.php?account=<?php echo $message->from_username; ?>"><?php echo $message->from_username; ?></a>
			</div>

			<div class="col-md-3">
				<strong><?php echo $message->message_datetime; ?></strong> 
			</div>

			<div class="col-md-2">
				<a href="index.php?messages&new&to=<?php echo $message->from_username; ?>">Odgovori</a>
			</div>
		</div>

		<hr>

		<div class="row">
			<div class="col-md-12">
				<p><?php echo $message->message_text; ?></p>
			</div>
		</div>

	</section>

<?php
}
?>
