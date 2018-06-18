<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';



$user = new User();

if(!$user->isLoggedIn())
{
	Redirect::to('../../index.php');
}

?>


<div class="container wall">
	

<?php

include 'statusform.html.php';

?>
</div>



<div class="container wall posts">


	
</div>

<script>
var token = '<?php echo Session::get(Config::get('session/token_name')); ?>';
var uname = '<?php echo $user->data()->username; ?>';
</script>

<script src="js/wall.js">
	
 </script>

