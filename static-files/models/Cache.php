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

class Cache extends FactoryObject{
	
	# Constants
	
	# Instance Variables
	private $cachedHTML; // string
	private $cachedURL; // string
	private $dateCreated; // timestamp
	
	
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
			$dataArray['cachedHTML'] = "";
			$dataArray['cachedURL'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['cachedHTML'] = "";
			$dataArray['cachedURL'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT caches.id AS itemID,
							   caches.cached_html AS cachedHTML,
							   caches.cached_url AS cachedURL,
							   unix_timestamp(caches.date_created) AS dateCreated
						  FROM caches
						 WHERE caches.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['cachedHTML'] = $resultArray['cachedHTML'];
			$dataArray['cachedURL'] = $resultArray['cachedURL'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		$this->itemID = isset($dataArray["itemID"])?$dataArray["itemID"]:0;
		$this->cachedHTML = isset($dataArray["cachedHTML"])?$dataArray["cachedHTML"]:"";
		$this->cachedURL = isset($dataArray["cachedURL"])?$dataArray["cachedURL"]:"";
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
			$queryString = "UPDATE caches
							   SET caches.cached_html = ".DBConn::clean($this->getCachedHTML()).",
								   caches.cached_url = ".DBConn::clean($this->getCachedURL())."
							 WHERE caches.id = ".DBConn::clean($this->getItemID());
			
			$mysqli->query($queryString);
		} else {
			// Create a new record
			$queryString = "INSERT INTO caches
								   (caches.id,
									caches.cached_html,
									caches.cached_url,
									caches.date_created)
							VALUES (0,
									".DBConn::clean($this->getCachedHTML()).",
									".DBConn::clean($this->getCachedURL()).",
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
		$queryString = "DELETE FROM caches
						 WHERE caches.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
		
	}
	
	
	# Getters
	public function getCachedHTML() { return $this->cachedHTML; }
	
	public function getCachedURL() { return $this->cachedURL; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	# Setters
	public function setCachedHTML($str) { $this->cachedHTML = $str; }
	
	public function setCachedURL($str) { $this->cachedURL = $str; }
	
	
	# Static Methods
	public static function getObjectByURL($url) {
		$query_string = "SELECT caches.id as itemID 
						   FROM caches
						  WHERE caches.cached_url = ".DBConn::clean($url)."
						ORDER BY caches.date_created desc";
		return array_pop(Cache::getObjects($query_string));
	}
	
	public static function clearCache($url) {
		$query_string = "SELECT caches.id as itemID 
						   FROM caches
						  WHERE caches.cached_url = ".DBConn::clean($url)."
						ORDER BY caches.date_created desc";
		
		foreach(Cache::getObjects($query_string) as $cache)
			$cache->delete();
	}
}
?>