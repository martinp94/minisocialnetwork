<?php


class Messenger 
{


	private $_db,
			$_userReceived,
			$_userSent,
			$_error,
			$_user;


	public function __construct($userId)
	{
		$this->_db = Database::getInstance();
		$this->_user = new User($userId);
		$this->loadReceived($this->_user->data()->id);
		$this->loadSent($this->_user->data()->id);
	}

	private function loadReceived($userId) 
	{
		
		$messages = $this->_db->query("SELECT * FROM messages WHERE to_user = ? ORDER BY message_datetime DESC", array($this->_user->data()->id))->results();
		$this->_userReceived = $messages;
	}

	private function loadSent($userId)
	{
		$messages = $this->_db->query("SELECT * FROM messages WHERE from_user = ? ORDER BY message_datetime DESC", array($this->_user->data()->id))->results();
		$this->_userSent = $messages;
	}

	public function receivedMessages()
	{
		return $this->_userReceived;
	}

	public function sentMessages()
	{
		return $this->_userSent;
	}

	public function sendMessage($messageText, $toUsername)
	{

		$toUser = User::getIdFromUsername($toUsername);

		if($toUser)
		{
			$fields = array(
			'message_text' => $messageText,
			'from_user' => $this->_user->data()->id,
			'to_user' => $toUser,
			'message_datetime' => date('Y-m-d H:i:s'),
			'from_username' => $this->_user->data()->username,
			'to_username' => $toUsername
			);

			if($this->_db->insert('messages', $fields))
			{
				$this->_error = 'status uploaded successfully';
				return true;
			}
			else
			{
				$this->_error = 'problem with saving message in the database';
			}
		}
		else
		{
			$this->_error = "user doesn't exists";
		}

		return false;
	}

	public function getUser()
	{
		return $this->_user;
	}

	public function error()
	{
		return $this->_error;
	}

}


?>