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

class Campaign extends FactoryObject{
	
	# Constants
	// Initialization Types
	const INIT_EMPTY = -1;
	const INIT_DEFAULT = 0;
	
	
	# Instance Variables
	private $code; // string
	private $localeID; // string
	private $logoURL; // timestamp
	
	
	public function __construct($itemID = Remix::INIT_EMPTY) {
		$dataArrays = static::gatherData((int)$itemID);
		$this->load($dataArrays[0]);
	}
	
	# FactoryObject Methods
	protected static function gatherData($objectString, $start=FactoryObject::LIMIT_BEGINNING, $length=FactoryObject::LIMIT_ALL) {
		$dataArrays = array();
		
		// Load an empty object
		if($objectString === Remix::INIT_EMPTY) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['code'] = "";
			$dataArray['localeID'] = 0;
			$dataArray['logoURL'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === Remix::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['code'] = "";
			$dataArray['logoURL'] = 0;
			$dataArray['localeID'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT campaigns.id AS itemID,
							   campaigns.code AS code,
							   campaigns.logo_url AS logoURL,
							   campaigns.locale_id AS localeID,
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
			$dataArray['logoURL'] = $resultArray['logoURL'];
			$dataArray['localeID'] = $resultArray['localeID'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		$this->itemID = isset($dataArray["itemID"])?$dataArray["itemID"]:0;
		$this->code = isset($dataArray["code"])?$dataArray["code"]:"";
		$this->logoURL = isset($dataArray["logoURL"])?$dataArray["logoURL"]:"";
		$this->localeID = isset($dataArray["localeID"])?$dataArray["localeID"]:"";
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
			$queryString = "UPDATE campaigns
							   SET campaigns.code = ".DBConn::clean($this->getCode()).",
								   campaigns.logo_url = ".DBConn::clean($this->getLogoURL()).",
								   campaigns.locale_id = ".DBConn::clean($this->getLocaleID())."
							 WHERE campaigns.id = ".DBConn::clean($this->getItemID());
			
			$mysqli->query($queryString);
		} else {
			// Create a new record
			$queryString = "INSERT INTO campaigns
								   (campaigns.id,
									campaigns.code,
									campaigns.logo_url,
									campaigns.locale_id,
									campaigns.date_created)
							VALUES (0,
									".DBConn::clean($this->getCode()).",
									".DBConn::clean($this->getLogoURL()).",
									".DBConn::clean($this->getLocaleID()).",
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
	
	public function getLogoURL() { return $this->logoURL; }
	
	public function getLocaleID() { return $this->localeID; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	# Setters
	public function setCode($str) { $this->code = $str; }
	
	public function setLogoURL($str) { $this->logoURL = $str; }
	
	public function setLocaleID($int) { $this->localeID = $int; }
	
	
	# Static Methods
	public static function getObjectByCode($code) {
		$query_string = "SELECT campaigns.id as itemID 
						   FROM campaigns
						  WHERE campaigns.code = ".DBConn::clean($code);
		return array_pop(Campaign::getObjects($query_string));
	}
}
?>