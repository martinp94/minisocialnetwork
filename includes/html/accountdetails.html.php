
<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$errors = array();
$user = new User();
$isYourAccount = true;
$db = Database::getInstance();
$birth_date_display;
$joined_display;
$yourAccount;

if(!$user->isLoggedIn())
{
	Redirect::to('index.php');
}

if(!Input::existsItem('account') || Input::get('account') == '')
{
	Redirect::to('index.php');
}

if($user->data()->username != Input::get('account'))
{
	$query = $db->get('users', array('username', '=', Input::get('account')));
	$errors[] = $query->count();
	Session::put('errors', $errors);
	if(!$query->count())
		Redirect::to('index.php');

	$yourAccount = $user;

	$user = new User($query->first()->id);
	$isYourAccount = false;
}

$birth_date_display = date_create($user->data()->birth_date);
$birth_date_display = date_format($birth_date_display, 'd-m-Y');
$joined_display = date_create($user->data()->joined);
$joined_display = date_format($joined_display, 'd-m-Y');

?>

<div class="container">
	<div class="accountinfo">
		<div class="row">
			<div class="col-md-2">
				<img src="images/account details/name_surname.png" title="Ime i prezime" alt="Ime i prezime" width="64">
			</div>

			<div class="col-md-4">
				<p><?php echo $user->data()->fname . ' ' . $user->data()->lname; ?></p>
			</div>

			<div id="friendship" class="col-md-6">
				<?php include 'accountFriendshipDisplay.html.php'; ?>
			</div>

		</div>

		<div class="row">
			<div class="col-md-2">
				<img src="images/account details/birthday.png" title="Datum rođenja" alt="Datum rođenja" width="64">
			</div>

			<div class="col-md-6">
				<p><?php echo $birth_date_display; ?></p>
			</div>

			<!-- <div class="col-md-2">
				Posalji poruku....
			</div> -->
		</div>

		<div class="row">
			<div class="col-md-2">
				<img src="images/account details/joined.png" title="Datum pridruživanja" alt="Datum pridruživanja" width="64">
			</div>

			<div class="col-md-6">
				<p><?php echo $joined_display; ?></p>
			</div>
		</div>		
		
	</div>
</div>

<script src="js/addfriend.js">

</script>


