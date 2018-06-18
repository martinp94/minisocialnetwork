<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();
if($user->isLoggedIn())
	Redirect::to('../../index.php');

?>

<form method="post" action="includes/login.php" class="loginForm">
				
	<div class="form-group row">
		<div class="col-sm-12">
			<input type="text" name="username" class="form-control" placeholder="Korisničko ime">
		</div>
	</div>

	<div class="form-group row">
		<div class="col-sm-12">
			<input style="margin-top: 20px; margin-bottom: 20px;" type="password" name="password" class="form-control" placeholder="Lozinka">
		</div>
	</div>

	<div class="form-group row">
		<div class="col-sm-12">
			<label for="remember"> Zapamti me </label>
			<input type="checkbox" name="remember">
		</div>
	</div>
	
	<div class="form-group row">
		<div class="col-sm-12">
			<button type="submit" class="btn btn-success submitButton form-control"> <strong>Ulogujte se</strong>
			 </button>
		</div>
	</div>		
		
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	
</form>