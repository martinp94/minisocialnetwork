<?php

class User
{
	private $_db,
			$_isLoggedIn = false,
			$_data,
			$_sessionName,
			$_cookieName,
			$_username = 'Guest';


	public function __construct($user = null)
	{
		$this->_db = Database::getInstance();

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if(!$user)
		{
			if(Session::exists($this->_sessionName))
			{
				$user = Session::get($this->_sessionName);

				if($this->find($user))
				{
					$this->_isLoggedIn = true;
				}
			}
		} 
		else 
		{
			$this->find($user);
		}

		

	}

	private function find($user = null) 
	{
		if($user) 
		{
			$field = is_numeric($user) ? 'id' : 'username';

			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count())
			{
				$this->_data = $data->first();
				$this->_username = $this->data()->username;
				return true;
			}
		}

		return false;
	}

	public function update($fields = array(), $id = null)
	{
		
		if(!$id && $this->isLoggedIn()) 
		{
			$id = $this->data()->id;
		}
 
		if(!$this->_db->update('users', $id, $fields)) 
		{
			throw new Exception("Problem sa apdejtovanjem");
			
		}
	}

	public function checkPassword($password, $id = null)
	{
		if(!$id && $this->isLoggedIn())
		{
			$id = $this->data()->id;
		}

		if($this->data()->password === Hash::make($password, $this->data()->salt))
		{
			return true;
		}

		return false;
	}

	
	public function login($username = null, $password = null, $remember = null) 
	{
		if(!$username && !$password && $this->exists())
		{
			Session::put($this->_sessionName, $this->data()->id);
		}
		else
		{
			$user = $this->find($username);
			if($user)
			{
				if($this->data()->password === Hash::make($password, $this->data()->salt))
				{
					Session::put($this->_sessionName, $this->data()->id);
					$this->_username = $this->data()->username;

					if($remember) {

						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_sessions', array('user_id', '=', $this->data()->id));

						if(!$hashCheck->count()) {
							$this->_db->insert('users_sessions', array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
							
						} else {
							$hash = $hashCheck->first()->hash;

						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				}
			}
		}

		return false;
	}

	public function logout()
	{
		if($this->isLoggedIn())
		{
			$this->_db->delete('users_sessions', array('user_id', '=', $this->data()->id));

			Session::delete($this->_sessionName);
			Cookie::delete($this->_cookieName);
		}
	}

	public function register($fields)
	{
		if(empty($fields))
		{
			return false;
		}

		if($this->_db->insert('users', $fields))
		{
			return true;
		}

		return false;
	}

	public static function getIdFromUsername($username)
	{
		$usId = Database::getInstance()->get('users', array('username', '=', $username));
		if($usId->count())
			return $usId->first()->id;
		return false;
	}

	public function addFriend($to_user_id)
	{
		$fields = array(
		'user_requested' => $this->data()->id,
		'user_accepted' => $to_user_id,
		'accepted' => 0,
		'friendship_started' => date('Y-m-d H:i:s')
		);

		if($this->_db->insert('friendships', $fields))
			echo 'Zahtjev za prijateljstvo poslat
		<img src="images/account details/request_sent.gif" title="Poslali ste zahtjev za prijateljstvo ovoj osobi" alt="Datum rođenja" width="32">';
		else
			echo '<strong style="color: darkred;"> Greška pri slanju zahtjeva </strong>';
	}


	public function exists()
	{
		return (!empty($this->_data)) ? true : false;
	}

	public function username() 
	{
		return $this->_username;
	}

	public function data()
	{
		return $this->_data;
	}

	public static function searchUser($searchStr) 
	{

		$first = $searchStr['s0'] . '%';

		$str = "'" . implode("', ' ", $searchStr) . "'";
		
		$query = "SELECT fname, lname, profile_image, username
				   FROM `users` 
				   WHERE (fname IN (?)
				   OR lname IN (?))
				   OR (fname LIKE ?
				   OR lname LIKE ?)";


		$results = Database::getInstance()->query($query, array($str, $str, $first, $first))->results();
		
		return $results;

	}

	public function friends($accepted = 1)
	{
		$friends = $this->_db->query("SELECT fr.id as 'fr_id', fr.user_requested, fr.user_accepted, fr.friendship_started, 
											 us.id as 'us_id', us.username, us.fname, us.lname, us.profile_image
											 FROM friendships fr LEFT JOIN users us 
											 ON fr.user_requested = us.id OR fr.user_accepted = us.id
											 WHERE fr.accepted = {$accepted}
											 AND (fr.user_requested = ? OR fr.user_accepted = ?)
											 AND us.id <> ?"
											 , array($this->data()->id, $this->data()->id, $this->data()->id));
		if($friends->count())
			return $friends->results();
		return false;
	}

	public function isFriendWith($anotherUserId)
	{
		$friendship = $this->_db->query("SELECT user_requested, user_accepted, accepted
										 FROM friendships
										 WHERE (user_requested = ? AND user_accepted = ?) OR
										 		(user_requested = ? AND user_accepted = ?)", array($this->data()->id, $anotherUserId, $anotherUserId, $this->data()->id));

		if($friendship->count())
		{
			$friendship = $friendship->first();

			if($friendship->accepted == true)
				return array(0 => $friendship->accepted);
			else
				return array(0 => $friendship->user_requested, 1 => $friendship->user_accepted);
		}
		else
		{
			return false;
		}

		return false;
	}

	public function acceptFriendship($anotherUserId)
	{
		$query = "UPDATE friendships 
		 					SET accepted = 1
		 					WHERE user_requested = ? AND user_accepted = ?";

		if($this->_db->query($query, array($anotherUserId, $this->data()->id)))
			return true;
		return false;
	}

	public function unfriend($anotherUserId)
	{
		$query = "DELETE FROM friendships WHERE (user_requested = ? AND user_accepted = ?) 
										  OR (user_requested = ? AND user_accepted= ?)";

		if($this->_db->query($query, array($anotherUserId, $this->data()->id, $this->data()->id, $anotherUserId)))
			return true;
		return false;
	}

	public function deleteFriendship($anotherUserId)
	{
		$query = "DELETE FROM friendships WHERE user_requested = ? AND user_accepted = ?";
		if($this->_db->query($query, array($anotherUserId, $this->data()->id)))
			return true;
		return false;
	}

	public function isLoggedIn()
	{
		return $this->_isLoggedIn;
	}

	public function isActivated()
	{
		if($this->data() === null)
			return null;
		else
			return $this->data()->activated == 0 ? '(Inactive)' : '';
			
	}
}