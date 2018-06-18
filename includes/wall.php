<?php

require_once 'C:/xampp/htdocs/minisocialnetwork/core/init.php';

$user = new User();
if(!$user->isLoggedIn())
	Redirect::to('../index.php');
$db = Database::getInstance();



function getPosts($toFetch = null) 
{
	$posts = new Posts($toFetch);
	echo json_encode($posts->all());
}

function getComments($statusId)
{
	global $db;
	$comments = Posts::loadComments($statusId);
	echo json_encode($comments);

	
}

function getVotes($vote,$statusId)
{
	$votes = Posts::loadVotes($vote, $statusId);
	echo json_encode($votes);
}

function postVote($vote, $id, $username) 
{

	$result = Posts::vote($vote, $id, $username);
}

function getUsersThatLikedPost($postId, $vote)
{


	$postsUsers = Posts::getPostsUsersByVote($vote, $postId);
	$voteType = $vote === 1 ? 'lajkovao' : 'dislajkovao';
	
	if(empty($postsUsers))
		echo json_encode(array(0 => array("err" => "<strong style='color: darkred;'>Još uvijek niko nije {$voteType} post.</strong>")));
	else
		echo json_encode($postsUsers);
}

function submitSearch($searchStr)
{
	global $user;
	$searchResults = $user::searchUser($searchStr);

	echo json_encode($searchResults);
}


if(isset($_GET['getPosts']))
{
	$toGet = $_GET['getPosts'];
	getPosts($toGet);
}
if(isset($_GET['getComments']))
{
	$toGet = $_GET['getComments'];
	getComments($toGet);
}
if(isset($_GET['getLikes']))
{
	$toGet = $_GET['getLikes'];
	getVotes(1, $toGet);
}
if(isset($_GET['getDislikes']))
{
	$toGet = $_GET['getDislikes'];
	getVotes(0, $toGet);
}
if(isset($_POST['like']))
{
	$toPost = json_decode($_POST['like']);
	
	postVote(true, $toPost->post, $toPost->username);
}
if(isset($_POST['dislike']))
{
	$toPost = json_decode($_POST['dislike']);
	
	postVote(false, $toPost->post, $toPost->username);
}
if(isset($_GET['liked']))
{
	$postId = $_GET['liked'];
	getUsersThatLikedPost($postId, 1);
}
if(isset($_GET['disliked']))
{
	$postId = $_GET['disliked'];
	getUsersThatLikedPost($postId, 0);
}

if(isset($_POST['search'])){
	$searchStr = json_decode($_POST['search'], true);

	submitSearch($searchStr);
}



?>
