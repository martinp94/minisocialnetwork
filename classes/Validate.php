<?php

class Validate
{
	private $_passed = false,
			$_errors = array(),
			$db = null;

	public function __construct()
	{
		$this->_db = Database::getInstance();
	}

	public function check($source, $items = array(), $table_name = null)
	{

		foreach ($items as $item => $rules) 
		{
			foreach ($rules as $rule => $rule_value) 
			{

				$value = trim($source[$item]);
				$item = escape($item);

				if($rule === 'required' && empty($value))
				{
					$this->addError($item . " is " . $rule);
				} 
				else if (!empty($value)) 
				{

					switch ($rule) 
					{
						case 'min':
							if($rule_value > strlen($value))
							{
								$this->addError("{$item} must be minimum of {$rule_value} characters");
							}
							break;
						case 'max':
							if($rule_value < strlen($value))
							{
								$this->addError("{$item} must be maximum of {$rule_value} characters");
							}
							break;
						case 'matches':
							if($value != $source[$rule_value])
							{
								$this->addError("{$rule_value} must match $item");
							}
							break;
						case 'unique':
							$check = $this->_db->get($table_name, array($item, '=', $value));
							if($check->count()){
								$this->addError("{$item} already exists.");
							}
							break;
						case 'minAge':
							$check = date("Y");
							$dateEntered = date("Y", strtotime($value));
							if(($check - $dateEntered) < 7)
							{
								$this->addError('User must be at least 7 years old');
							}
							
							break;
						default:
							# code...
							break;
					}
				}
			}
		}

		if(empty($this->_errors))
		{
			$this->_passed = true;
			
		}
		else
		{
			Session::put('errors', $this->errors());
		}

		return $this;
	}

	private function addError($error)
	{
		$this->_errors[] = $error;
	}

	public function errors()
	{
		return $this->_errors;
	}

	public function passed()
	{
		return $this->_passed;
	}
}