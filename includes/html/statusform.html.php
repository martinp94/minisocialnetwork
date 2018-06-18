<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}


/*
include 'imageupload.html.php';
*/
?>




<section id="status">

	<form method="post" action="includes/statusform.php">
		
		<div class="form-group">
			<textarea class="form-control" style="padding-top: 10px;" name="status_text" id="status_text" rows="2" cols="84"></textarea>
			<span id="status_text_error"><strong><i>Status mora biti dugačak najmanje 6 karaktera</i></strong></span>
		</div>

		<div class="form-group row">
			<div class="col-md-2">
				
			</div>
			<div class="col-md-2">
				
			</div>
			<div class="col-md-3">
				
			</div>
			<div class="col-md-3">
				
			</div>
			<div class="col-md-2">
				<button id="postsubmit" class="btn btn-primary form-control" type="submit">Postuj</button>

			</div>
			 
		</div>
		
		<input type="hidden" name="to_user" value="null">
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

	</form>
</section>

<hr>

<script>

	$(function(){
		//$("#uploadimgform").show();

		$("#postsubmit").click(function(event){

			if($("#status_text").val().length < 6) {
				//$("#status_text").val("");
				$("#status_text_error").show();
				event.preventDefault();

			}
			
		});
		

	});



</script>