<?php
###
# Info:
#  Last Updated 2012
#  Daniel Schultz
#
# Comments:
#  This class is the model for a remix object
###

include_once("FactoryObject.php");

class User extends FactoryObject{
	
	# Constants
	const TYPE_ADMIN = "administrator";
	
	
	# Instance Variables
	private $username;
	private $password;
	
	# Caches
	
	public function __construct($itemID = FactoryObject::INIT_EMPTY) {
		$dataArrays = static::gatherData((int)$itemID);
		$this->load($dataArrays[0]);
	}
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$dataArrays = array();
		
		// Load an empty object
		if($objectString === FactoryObject::INIT_EMPTY) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['username'] = "";
			$dataArray['password'] = "";
			$dataArray['type'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['username'] = "";
			$dataArray['password'] = "";
			$dataArray['type'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT users.id AS itemID,
							   users.username AS username,
							   users.password AS password,
							   users.type AS type,
							   unix_timestamp(users.date_created) AS dateCreated
						  FROM users
						 WHERE users.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['username'] = $resultArray['username'];
			$dataArray['password'] = $resultArray['password'];
			$dataArray['type'] = $resultArray['type'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		$this->itemID = isset($dataArray["itemID"])?$dataArray["itemID"]:0;
		$this->username = isset($dataArray["username"])?$dataArray["username"]:"";
		$this->password = isset($dataArray["password"])?$dataArray["password"]:"";
		$this->type = isset($dataArray["type"])?$dataArray["type"]:"";
		$this->dateCreated = isset($dataArray["dateCreated"])?$dataArray["dateCreated"]:0;
	}
	
	
	# Data Methods
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate()) return;
		
		$mysqli = DBConn::connect();
		
		if($this->isUpdate()) {
			// Update an existing record
			$queryString = "UPDATE users
							   SET users.username = ".DBConn::clean($this->getUsername()).",
								   users.password = ".DBConn::clean($this->getPassword())."
								   users.type = ".DBConn::clean($this->getType())."
							 WHERE users.id = ".DBConn::clean($this->getItemID());
			
			$mysqli->query($queryString);
		} else {
			// Create a new record
			$queryString = "INSERT INTO users
								   (users.id,
									users.username,
									users.password,
									users.type,
									users.date_created)
							VALUES (0,
									".DBConn::clean($this->getUsername()).",
									".DBConn::clean($this->getPassword()).",
									".DBConn::clean($this->getType()).",
									NOW())";
			
			$mysqli->query($queryString);
			$this->setItemID($mysqli->insert_id);
		}
		
		// Parent Operations
		return true;
	}
	
	public function delete() {
		parent::delete();
		$mysqli = DBConn::connect();
		
		// Delete this record
		$queryString = "DELETE FROM users
						 WHERE users.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
		
	}
	
	
	# Getters
	public function getUsername() { return $this->username; }
	
	public function getPassword() { return $this->password; }
	
	public function getType() { return $this->type; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	# Setters
	public function setUsername($str) { $this->username = $str; }
	
	public function setPassword($str) {
		// Generate the salt
		$salt = md5(uniqid(rand(), true));
		
		// Salt and set the password
		$this->password = $salt.md5($salt.$str);
	}
	
	public function setType($str) { $this->type = $str; }
	
	
	# Static Methods
	public static $currentUser = null;
	
	public static function login($username, $password) {
		$queryString = "select users.id as itemID
						  from users
						 where users.username = ".DBConn::clean($username)."
						   and (users.password = CONCAT(SUBSTR(users.password,1,32), MD5(CONCAT(SUBSTR(users.password,1,32),".DBConn::clean($password).") ))
						     or users.password = ".DBConn::clean($password).")";
		
		User::$currentUser = array_pop(User::getObjects($queryString));
				
		if(User::isLoggedIn()) {
			$_SESSION["username"] = $username;
			$_SESSION["password"] = User::$currentUser->getPassword();
			return true;
		}
		return false;
	}
	
	public static function logout() {
		User::$currentUser = null;
		$_SESSION["username"] = "";
		$_SESSION["password"] = "";
	}
	
	public static function isLoggedIn() {
		return User::$currentUser != null;
	}
	
	public static function isAdministrator() {
		return User::isLoggedIn()&&User::$currentUser->getType() == User::TYPE_ADMIN;
	}
}
?>