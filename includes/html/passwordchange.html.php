<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();
if(!$user->isLoggedIn())
	Redirect::to('../../index.php');
?>

<form method="post" action="includes/passwordchange.php" class="loginForm">
				
	<div class="form-group row">
		<div class="col-sm-9">
			<input type="password" id="password_current" name="password_current" class="form-control" placeholder="Unesite trenutnu lozinku">
		</div>
		<div class="col-sm-3 form_error">
			<span id="password_current_error"></span>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-sm-9">
			<input style="margin-top: 20px; margin-bottom: 20px;" type="password" id="password_new" name="password_new" class="form-control" placeholder="Unesite novu lozinku">
		</div>
		<div class="col-sm-3 form_error">
			<span id="password_new_error"></span>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-sm-9">
			<input style="margin-top: 20px; margin-bottom: 20px;" type="password" id="password_new_again" name="password_new_again" class="form-control" placeholder="Ponovite novu lozinku">
		</div>
		<div class="col-sm-3 form_error">
			<span id="password_new_again_error"></span>
		</div>
	</div>

	
	<div class="form-group row">
		<div class="col-sm-9">
			<button type="submit" class="btn btn-success submitButton form-control"> <strong>Promjeni lozinku</strong>
			 </button>
		</div>
	</div>		
		
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	
</form>

<script src="js/passwordChangeValidator.js">
	
</script>