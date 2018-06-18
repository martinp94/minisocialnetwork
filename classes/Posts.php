<?php


class Posts
{
	
	private $_db,
			$_posts,
			$_comments;

	function __construct($limit = null)
	{
		$this->_db = Database::getInstance();
		$this->loadPosts($limit);
	}



	private function loadPosts($limit = null)
	{

		$params = array(0 => PHP_INT_MAX);
		

		// Fetchuj sve postove iz baze koji nisu komentari na statusa, tj. kojima je vrijednost kolone comment_to = NULL
		// Limitiraj postove 
		$sqlQuerry = "SELECT st.id, st.post_date, st.status_text, st.comment_to, st.likes, st.dislikes, us.username, us.fname, us.lname, us.profile_image
					  FROM status st INNER JOIN users us
					  ON st.user_id = us.id
					  WHERE comment_to IS NULL
					  ORDER BY st.post_date DESC
					  LIMIT 5 OFFSET ?";

		// Dodaj limit u niz $params za query (prepared statements)

		if($limit != null)
			$params = array(0 => $limit);

		
		$posts = $this->_db->query($sqlQuerry, $params);

		if($posts->count())
		{
			$this->_posts = $posts->results();
		}
		else
		{
			$this->_posts = array('errorMessage' => 'No posts');
		}

	}

	public function all()
	{
		return $this->_posts;
	}

	public static function loadComments($commentTo) 
	{
		return Database::getInstance()->query("SELECT st.id, st.post_date, st.status_text,st.likes, st.dislikes, st.comment_to, st.to_user, st.to_username, us.username, us.fname, 
						  us.lname, us.profile_image
						  FROM status st INNER JOIN users us
						  ON st.user_id = us.id
						  WHERE comment_to = ?
						  ORDER BY st.post_date DESC", array($commentTo))->results();
	}


	public static function loadVotes($vote, $commentTo) 
	{
		return Database::getInstance()->query("SELECT COUNT(id)
											   FROM votes
											   WHERE vote = ? AND
											   status_id = ?", array($vote, $commentTo))->results();
	}

	public static function vote($vote, $id, $username) 
	{

		$database = Database::getInstance();

		$userId = $database->get('users', array('username', '=', $username));
		if($userId->count()) 
		{
			$userId = $userId->first()->id;
		} 
		else
		{
			Session::put('errors', 'No username');
			die();
		}


		$voted = $database->query("SELECT vote FROM votes WHERE user_id = ? AND status_id = ?", array($userId, $id));

		if(!$voted->error())
		{
			
			if($voted->count()) 
			{
				$voted = $voted->first()->vote;
			} 
			else 
			{
				$fields = array(
					'vote' => $vote,
					'user_id' => $userId,
					'status_id' => $id
				);

				if($database->insert('votes', $fields)) 
				{
					
					$vType = $vote == 1 ? 'likes' : 'dislikes';

					$update = $database->query("UPDATE status SET {$vType} = {$vType} + 1 WHERE id = ?", array($id));

					if($update) 
					{
						
						$likes = $database->query("SELECT {$vType} FROM status WHERE id = ?", array($id))->first();
						echo $likes->$vType;
						
					} 
					else
					{
						
					}
				} 
				else
				{
					
				}
			}
		}

	}

	public static function getLastestPostFromUser($userId, $isStatus = true)
	{

		$comment_to = $isStatus === true ? "WHERE st.comment_to IS NULL" : "";

		$query = "SELECT st.id, st.post_date, st.status_text, st.comment_to, st.to_username, st.likes, st.dislikes, us.username, us.fname, us.lname, us.profile_image
					  FROM status st INNER JOIN users us
					  ON st.user_id = us.id AND st.id = (SELECT MAX(id) from status WHERE user_id = ?)
					   " . $comment_to;



		return Database::getInstance()->query($query, array($userId))->first();
	}

	public static function getPostsUsersByVote($vote, $postId)
	{
		$query = "SELECT vote.id, vote.status_id, vote.vote, vote.user_id, us.username, us.fname, us.lname, us.profile_image
				  FROM `votes` vote LEFT JOIN `users` us
				  ON vote.user_id = us.id
				  WHERE vote.vote = ? AND vote.status_id = ?";

		return Database::getInstance()->query($query, array($vote, $postId))->results();
	}


}