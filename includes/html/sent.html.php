<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}

$messenger = new Messenger($user->data()->id);

?>

<h3>Poslato</h3>
<hr>


<?php 

$messages = $messenger->sentMessages();

foreach ($messages as $message) 
{
?>
	<section>
		<div class="row">
			<div class="col-md-7">
				Za: <a href="index.php?account=<?php echo $message->to_username; ?>"><?php echo $message->to_username; ?></a>
			</div>

			<div class="col-md-3">
				<strong><?php echo $message->message_datetime; ?></strong> 
			</div>

			<div class="col-md-2 forward">
				<a href="#">Proslijedi</a>
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

<script>

document.addEventListener('DOMContentLoaded', () => {


	let messageForward = document.querySelectorAll("section .forward a");
	for(let message of messageForward) {
		message.addEventListener('click', forwardMessage);
		//console.log(message.parentNode.parentNode.parentNode.childNodes[5].childNodes[1].childNodes[1].innerText);
	}

	function forwardMessage(e) {
		
		let messageText = e.target.parentNode.parentNode.parentNode.childNodes[5].childNodes[1].childNodes[1].innerText;
		sendRequest(messageText);
	}

	function sendRequest(message) {
		let xhr = new XMLHttpRequest();
		xhr.open("POST", 'index.php?messages&new', true);
		let params = 'message_text=' + encodeURIComponent(message);

		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() {
			if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
		       location.href = 'index.php?messages&new';
		    }
		}

		console.log(message);

		xhr.send(params);
	}
	

});

</script>
