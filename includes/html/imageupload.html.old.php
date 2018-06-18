<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

?>

<div class="image_container">
	<img class="img-responsive" src="images/uploads/profile_images/<?php echo $user->data()->profile_image; ?>">
</div>




<form method="post" action="includes/imageupload.php?userimg" enctype="multipart/form-data">

	<input type="hidden" name="size" value="1000000">

	<div>
		<input type="file" name="imageToUpload" />
	</div>

	<div>
		<button type="submit" class="btn btn-success">Upload</button>
	</div>

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />

</form>