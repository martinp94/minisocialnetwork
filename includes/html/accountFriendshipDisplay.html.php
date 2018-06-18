<?php 
if(!$isYourAccount)
{
	$friendship = $yourAccount->isFriendWith($user->data()->id);

	if($friendship)
	{
		if(count($friendship) === 1)
		{
?>
		
		<img src="images/account details/friends.png" title="Vi i oba osoba ste prijatelji" alt="Datum rođenja" width="64">

<?php
		}
		else if(count($friendship) === 2)
		{
			if($friendship[0] == $yourAccount->data()->id)
			{

?>		
		Zahtjev za prijateljstvo poslat
		<img src="images/account details/request_sent.gif" title="Poslali ste zahtjev za prijateljstvo ovoj osobi" alt="Datum rođenja" width="32">

<?php
			}
			else if($friendship[1] == $yourAccount->data()->id)
			{
?>
		<p>Ova osoba ti je poslala zahtjev za prijateljstvo
		<a id="add_friend" href="index.php?friends&requests">
			<img src="images/account details/add_friend.png" title="Ova osoba ti je poslala zahtjev za prijateljstvo" alt="Zahtjev" width="20">
		</a>
		</p>
<?php
			}
		}
		else
		{
?>
		<a id="add_friend" href="#">
			<input type="hidden" name="username_to" value="<?php echo $user->data()->username; ?>">
			<img src="images/account details/add_friend.png" title="Dodaj za prijatelja" alt="Datum rođenja" width="64">
		</a>

<?php
		}

	}
	else
	{
?>
	<a id="add_friend" href="#">
		<input type="hidden" name="username_to" value="<?php echo $user->data()->username; ?>">
		<img src="images/account details/add_friend.png" title="Dodaj za prijatelja" alt="Datum rođenja" width="64">
	</a>
<?php
	}
}
?>