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
include_once("LocaleMod.php");

class Campaign extends FactoryObject{
	
	# Constants
	
	# Instance Variables
	private $code; // string
	private $cssURL; // string
	private $title; // string
	private $description; // string
	
	# Caches
	private $localeMods; // array
	
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
			$dataArray['code'] = "";
			$dataArray['cssURL'] = "";
			$dataArray['title'] = "";
			$dataArray['description'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['code'] = "";
			$dataArray['cssURL'] = "";
			$dataArray['title'] = "";
			$dataArray['description'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT campaigns.id AS itemID,
							   campaigns.code AS code,
							   campaigns.css_url AS cssURL,
							   campaigns.title AS title,
							   campaigns.description AS description,
							   unix_timestamp(campaigns.date_created) AS dateCreated
						  FROM campaigns
						 WHERE campaigns.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['code'] = $resultArray['code'];
			$dataArray['cssURL'] = $resultArray['cssURL'];
			$dataArray['title'] = $resultArray['title'];
			$dataArray['description'] = $resultArray['description'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		$this->itemID = isset($dataArray["itemID"])?$dataArray["itemID"]:0;
		$this->code = isset($dataArray["code"])?$dataArray["code"]:"";
		$this->cssURL = isset($dataArray["cssURL"])?$dataArray["cssURL"]:"";
		$this->title = isset($dataArray["title"])?$dataArray["title"]:"";
		$this->description = isset($dataArray["description"])?$dataArray["description"]:"";
		$this->dateCreated = isset($dataArray["dateCreated"])?$dataArray["dateCreated"]:0;
		
		// Caches
		$this->localeMods = isset($dataArray["localeMods"])?$dataArray["localeMods"]:null;
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
			$queryString = "UPDATE campaigns
							   SET campaigns.code = ".DBConn::clean($this->getCode()).",
								   campaigns.css_url = ".DBConn::clean($this->getCssURL()).",
								   campaigns.title = ".DBConn::clean($this->getTitle()).",
								   campaigns.description = ".DBConn::clean($this->getDescription())."
							 WHERE campaigns.id = ".DBConn::clean($this->getItemID());
			
			$mysqli->query($queryString);
		} else {
			// Create a new record
			$queryString = "INSERT INTO campaigns
								   (campaigns.id,
									campaigns.code,
									campaigns.css_url,
									campaigns.title,
									campaigns.description,
									campaigns.date_created)
							VALUES (0,
									".DBConn::clean($this->getCode()).",
									".DBConn::clean($this->getCssURL()).",
									".DBConn::clean($this->getTitle()).",
									".DBConn::clean($this->getDescription()).",
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
		$queryString = "DELETE FROM campaigns
						 WHERE campaigns.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
		
	}
	
	
	# Getters
	public function getCode() { return $this->code; }
	
	public function getCssURL() { return $this->cssURL; }
	
	public function getTitle() { return $this->title; }
	
	public function getDescription() { return $this->description; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	public function getLocaleMods() {
		// Check cache first
		if(!is_null($this->localeMods)) return $this->localeMods;
		
		$this->localeMods = LocaleMod::getObjectsByCampaignID($this->getItemID());
		return $this->localeMods;
	}
	
	
	# Setters
	public function setCode($str) { $this->code = $str; }
	
	public function setCssURL($str) { $this->cssURL = $str; }
	
	public function setTitle($str) { $this->title = $str; }
	
	public function setDescription($str) { $this->description = $str; }
	
	
	# Static Methods
	public static function getObjectByCode($code) {
		$query_string = "SELECT campaigns.id as itemID 
						   FROM campaigns
						  WHERE campaigns.code = ".DBConn::clean($code);
		return array_pop(Campaign::getObjects($query_string));
	}
	
	public static function getObjects($start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		return parent::getObjects("select id from campaigns",$start, $length);
	}
}
?>