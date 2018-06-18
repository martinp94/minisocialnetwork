<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

?>

	
<form method="post" action="includes/registracija.php" class="form-horizontal regForm">

	<div class="form-group row">
		<label for="fname" class="control-label col-sm-4 col-form-label"> <strong>Ime</strong> </label>
		<div class="col-sm-5">
		<input id="fname" type="text" name="fname" class="form-control">
		</div>
		<div class="col-sm-3 form_error">
			<span id="fname_error"></span>
		</div>
	</div>

	<div class="form-group row">
		<label for="lname" class="control-label col-sm-4 col-form-label"> <strong>Prezime</strong> </label>
		<div class="col-sm-5">
		<input id="lname" type="text" name="lname" class="form-control">
		</div>
		<div class="col-sm-3 form_error">
			<span id="lname_error"></span>
		</div>
	</div>

	<div class="form-group row">
		<label for="email" class="control-label col-sm-4 col-form-label"> <strong>Email adresa</strong> </label>
		<div class="col-sm-5">
		<input id="email" type="text" name="email" class="form-control">
		</div>
		<div class="col-sm-3 form_error">
			<span id="email_error"></span>
		</div>
	</div>

	<div class="form-group row">
		<label for="password" class="control-label col-sm-4 col-form-label"> <strong>Lozinka</strong> </label>
		<div class="col-sm-5">
		<input id="password" type="password" name="password" class="form-control">
		</div>
		<div class="col-sm-3 form_error">
			<span id="password_error"></span>
		</div>
	</div>

	<div class="form-group row">
		<label for="passwordAgain" class="control-label col-sm-4 col-form-label"> <strong>Ponovi lozinku</strong> </label>
		<div class="col-sm-5">
		<input id="passwordAgain" type="password" name="passwordAgain" class="form-control">
		</div>
		<div class="col-sm-3 form_error">
			<span id="passwordAgain_error"></span>
		</div>
	</div>

	<div class="form-group row">
		<label for="username" class="control-label col-sm-4 col-form-label"> <strong> Korisničko ime </strong> </label>
		<div class="col-sm-5">
			<input id="username" type="text" name="username" class="form-control">
		</div>
		<div class="col-sm-3 form_error">
			<span id="username_error"></span>
		</div>
	</div>


	<div class="form-group row">
		<label for="birth_date" class="control-label col-sm-4 col-form-label"> <strong> Datum rođenja </strong> </label>
		<div class="col-sm-5">
			<input id="birth_date" type="date" name="birth_date" class="form-control">
		</div>
		<div class="col-sm-3 form_error">
			<span id="birth_date_error"></span>
		</div>
	</div>

	<div class="form-group row">

		<div class="col-sm-4">
			
		</div>
		<div class="col-sm-5">
			<button type="submit" class="form-control btn btn-success submitButton"> <strong>Registruj se</strong> </button>
		</div>

		
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	</div>

</form>



<script src="js/registrationValidator.js">
	
	

</script>
		
