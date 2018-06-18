<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();
if(!$user->isLoggedIn())
	Redirect::to('../../index.php');

?>

<form method="post" action="includes/activation.php" class="form-horizontal regForm">
				
	<div class="form-group row">
		<label for="activation_code" class="control-label col-sm-5 col-form-label"> <strong> Unesite aktivacioni kod:  </strong> </label>
		<div class="col-sm-7">
			<input id="activation_code" type="text" name="activation_code" class="form-control text-center" maxlength="6">
		</div>
	</div>

	<div class="form-group row"> 

		<div class="col-sm-7">
			
		</div>
		<div class="col-sm-5">
			<button type="submit" class="form-control btn btn-success submitButton"> <strong>Potvrdi</strong> </button>
		</div>

		
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="hidden" name="uid" value="<?php echo $user->data()->id; ?>">
	</div>

</form>

<script>

$(function(){
	$("#activation_code").on('keyup', capitalize);


	function capitalize(event) {
		var start = event.target.selectionEnd;
		var end = event.target.selectionEnd;
		event.target.value = event.target.value.toUpperCase();
		event.target.setSelectionRange(start, end);
	}
});


</script>
		