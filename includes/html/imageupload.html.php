<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}

?>



	<form id="uploadimgform" method="post" action="includes/imageupload.php?userimg" enctype="multipart/form-data">

		<input type="file" name="imageToUpload" />
		<!-- <br>
		<hr> -->
		<button type="submit" class="btn btn-success">Upload</button>

		<input type="hidden" name="size" value="1000000">
		<input type="hidden" name="action" value="image" />
		<input type="hidden" name="tableid" value="<?php echo $user->data()->id; ?>">
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />

	</form>



