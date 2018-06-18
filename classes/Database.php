<?php

class Database
{

	private static $_instance = null;

	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0;
	
	private function __construct()
	{
		try
		{
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} catch (PDOException $e) {
			die($e->getMessage());
		}
		
	}

	public static function getInstance() 
	{
		if(!isset(self::$_instance)){
			self::$_instance = new Database();
		}

		return self::$_instance;
	}	

	public function query($sql, $params = array())
	{
		$this->_error = false;

		if($this->_query = $this->_pdo->prepare($sql)) 
		{
			if(count($params))
			{
				$this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);

				$cnt = 1;
				foreach ($params as $value) 
				{
					$this->_query->bindValue($cnt, $value);
					$cnt++;
					//echo $value;
				}


			}

			if($this->_query->execute())
			{
    			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
    			$this->_count = $this->_query->rowCount();
    		} 
    		else 
    		{
				print_r($this->_query->errorInfo());
				$this->_error = true; 
    		}
		}

		return $this;
	}

	public function action($action, $table, $where = array(), $orderByColumns = null, $asc = true)
	{
		if(count($where) === 3) 
		{
			$operators = array('=', '!=', '>', '<', '>=', '<=');

			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];

			if(in_array($operator, $operators))
			{
				if($orderByColumns != null) 
                {

                    if($asc == false) 
                    {
                        $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$orderByColumns} DESC";
                    } 
                   
                } 
                else 
                {
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                }

    			

    			if(!$this->query($sql, array($value))->error())
    			{
    				return $this;
    			}
    		}
		}

		return false;
	}

	public function get($table, $where = array()) 
	{
		return $this->action('SELECT *', $table, $where);
	}

	public function results()
	{
		return $this->_results;
	}

	public function insert($table, $fields = array())
	{
		if(count($fields))
		{
			$keys = array_keys($fields);
			$values = '';

    		foreach ($fields as $field) {
    			$values .= '?,';
    		}

    		$values = substr_replace($values, '', strripos($values, substr($values, -1)));

    		$sql = "INSERT INTO {$table} (`". implode('`, `', $keys) ."`)  VALUES ({$values})";

    		if(!$this->query($sql, $fields)->error()){
    			return true;
    		};
		}

		return false;
	}

	public function update($table,$id,$fields) 
	{
    	$set = '';
    	
		foreach ($fields as $key => $value) 
		{
    		$set .= "{$key} = ?,";
    		
    	}

    	$set = substr_replace($set, '', strripos($set, substr($set, -1)));

    	$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
    	echo $sql;
    	if(!$this->query($sql, $fields)->error())
    	{
    		return true;
    	}

    	return false;

    }

    public function delete($table, $where) 
    {
    	return $this->action('DELETE',$table,$where);
    }

	public function first()
	{
		return $this->results()[0];
	}

	public function count() 
	{
		return $this->_count;
	}

	public function error() 
	{
		return $this->_error;
	}

	public function queryString()
	{
		return $this->_query;
	}
}
