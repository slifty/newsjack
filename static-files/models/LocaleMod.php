<?php
###
# Info:
#  Last Updated 2012
#  Daniel Schultz
#
# Comments:
#  This class is the model for a locale modification object
###

include_once("FactoryObject.php");

class LocaleMod extends FactoryObject {
	
	# Constants
	
	
	# Instance Variables
	private $campaignID; // int
	private $language; // string
	private $modKey; // string
	private $modValue; // string
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
			$dataArray['campaignID'] = 0;
			$dataArray['language'] = "";
			$dataArray['mod_key'] = "";
			$dataArray['mod_value'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['campaignID'] = "";
			$dataArray['language'] = "";
			$dataArray['mod_key'] = "";
			$dataArray['mod_value'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT locale_mods.id AS itemID,
							   locale_mods.campaign_id AS campaignID,
							   locale_mods.language AS language,
							   locale_mods.mod_key AS mod_key,
							   locale_mods.mod_value AS mod_value,
							   unix_timestamp(locale_mods.date_created) AS dateCreated
						  FROM locale_mods
						 WHERE locale_mods.id IN (".$objectString.")";
		if($length != FactoryObject::LIMIT_ALL) {
			$query_string .= "
						 LIMIT ".DBConn::clean($start).",".DBConn::clean($length);
		}
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['itemID'] = $resultArray['itemID'];
			$dataArray['campaignID'] = $resultArray['campaignID'];
			$dataArray['language'] = $resultArray['language'];
			$dataArray['mod_key'] = $resultArray['mod_key'];
			$dataArray['mod_value'] = $resultArray['mod_value'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		$this->itemID = isset($dataArray["itemID"])?$dataArray["itemID"]:0;
		$this->campaignID = isset($dataArray["campaignID"])?$dataArray["campaignID"]:0;
		$this->language = isset($dataArray["language"])?$dataArray["language"]:"";
		$this->modKey = isset($dataArray["mod_key"])?$dataArray["mod_key"]:"";
		$this->modValue = isset($dataArray["mod_value"])?$dataArray["mod_value"]:"";
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
			$queryString = "UPDATE locale_mods
							   SET locale_mods.campaign_id = ".DBConn::clean($this->getCampaignID()).",
								   locale_mods.language = ".DBConn::clean($this->getLanguage()).",
								   locale_mods.mod_key = ".DBConn::clean($this->getModKey()).",
								   locale_mods.mod_value = ".DBConn::clean($this->getModValue())."
							 WHERE locale_mods.id = ".DBConn::clean($this->getItemID());
			
			$mysqli->query($queryString);
		} else {
			// Create a new record
			$queryString = "INSERT INTO locale_mods
								   (locale_mods.id,
									locale_mods.campaign_id,
									locale_mods.language,
									locale_mods.mod_key,
									locale_mods.mod_value,
									locale_mods.date_created)
							VALUES (0,
									".DBConn::clean($this->getCampaignID()).",
									".DBConn::clean($this->getLanguage()).",
									".DBConn::clean($this->getModKey()).",
									".DBConn::clean($this->getModValue()).",
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
		$queryString = "DELETE FROM locale_mods
						 WHERE locale_mods.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
		
	}
	
	
	# Getters
	public function getCampaignID() { return $this->campaignID; }
	
	public function getLanguage() { return $this->language; }
	
	public function getModKey() { return $this->modKey; }
	
	public function getModValue() { return $this->modValue; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	# Setters
	public function setCampaignID($id) { $this->campaignID = $id; }
	
	public function setLanguage($str) { $this->language = $str; }
	
	public function setModKey($str) { $this->modKey = $str; }
	
	public function setModValue($str) { $this->modValue = $str; }
	
	
	# Static Methods
	public static function getObjectsByCampaignID($campaignID) {
		$query_string = "SELECT locale_mods.id as itemID 
						   FROM locale_mods
						  WHERE locale_mods.campaign_id = ".DBConn::clean($campaignID)."
					   ORDER BY locale_mods.mod_key";
		return LocaleMod::getObjects($query_string);
	}
}
?>