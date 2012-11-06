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

class Remix extends FactoryObject{
	
	# Constants
	const CAMPAIGN_ALL = 0;
	
	
	# Instance Variables
	private $campaignID; // int
	private $originalDOM; // string
	private $originalURL; // string
	private $remixDOM; // string
	private $remixURL; // string
	private $imgURL; // string
	private $isFeatured; // bool
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
			$dataArray['originalDOM'] = "";
			$dataArray['originalURL'] = "";
			$dataArray['remixDOM'] = "";
			$dataArray['remixURL'] = "";
			$dataArray['imgURL'] = "";
			$dataArray['isFeatured'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === FactoryObject::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['campaignID'] = 0;
			$dataArray['originalDOM'] = "";
			$dataArray['originalURL'] = "";
			$dataArray['remixDOM'] = "";
			$dataArray['remixURL'] = "";
			$dataArray['imgURL'] = "";
			$dataArray['isFeatured'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT remixes.id AS itemID,
							   remixes.campaign_id AS campaignID,
							   remixes.original_dom AS originalDOM,
							   remixes.original_url AS originalURL,
							   remixes.remix_dom AS remixDOM,
							   remixes.remix_url AS remixURL,
							   remixes.img_url AS imgURL,
							   remixes.is_featured AS isFeatured,
							   unix_timestamp(remixes.date_created) AS dateCreated
						  FROM remixes
						 WHERE remixes.id IN (".$objectString.")";
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
			$dataArray['originalDOM'] = $resultArray['originalDOM'];
			$dataArray['originalURL'] = $resultArray['originalURL'];
			$dataArray['remixDOM'] = $resultArray['remixDOM'];
			$dataArray['remixURL'] = $resultArray['remixURL'];
			$dataArray['imgURL'] = $resultArray['imgURL'];
			$dataArray['isFeatured'] = $resultArray['isFeatured'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		$this->itemID = isset($dataArray["itemID"])?$dataArray["itemID"]:0;
		$this->campaignID = isset($dataArray["campaignID"])?$dataArray["campaignID"]:"";
		$this->originalDOM = isset($dataArray["originalDOM"])?$dataArray["originalDOM"]:"";
		$this->originalURL = isset($dataArray["originalURL"])?$dataArray["originalURL"]:"";
		$this->remixDOM = isset($dataArray["remixDOM"])?$dataArray["remixDOM"]:"";
		$this->remixURL = isset($dataArray["remixURL"])?$dataArray["remixURL"]:"";
		$this->imgURL = isset($dataArray["imgURL"])?$dataArray["imgURL"]:"";
		$this->isFeatured = isset($dataArray["isFeatured"])?$dataArray["isFeatured"]:"";
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
			$queryString = "UPDATE remixes
							   SET remixes.campaign_id = ".DBConn::clean($this->getCampaignID()).",
								   remixes.original_dom = ".DBConn::clean($this->getOriginalDOM()).",
								   remixes.original_url = ".DBConn::clean($this->getOriginalURL()).",
								   remixes.remix_dom = ".DBConn::clean($this->getRemixDOM()).",
								   remixes.remix_url = ".DBConn::clean($this->getRemixURL()).",
								   remixes.img_url = ".DBConn::clean($this->getImgURL()).",
								   remixes.is_featured = ".DBConn::clean($this->getIsFeatured())."
							 WHERE remixes.id = ".DBConn::clean($this->getItemID());
			
			$mysqli->query($queryString);
		} else {
			// Create a new record
			$queryString = "INSERT INTO remixes
								   (remixes.id,
									remixes.campaign_id,
									remixes.original_dom,
									remixes.original_url,
									remixes.remix_dom,
									remixes.remix_url,
									remixes.img_url,
									remixes.is_featured,
									remixes.date_created)
							VALUES (0,
									".DBConn::clean($this->getCampaignID()).",
									".DBConn::clean($this->getOriginalDOM()).",
									".DBConn::clean($this->getOriginalURL()).",
									".DBConn::clean($this->getRemixDOM()).",
									".DBConn::clean($this->getRemixURL()).",
									".DBConn::clean($this->getImgURL()).",
									".DBConn::clean($this->getIsFeatured()).",
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
		$queryString = "DELETE FROM remixes
						 WHERE remixes.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
		
	}
	
	
	# Getters
	public function getCampaignID() { return $this->campaignID; }
	
	public function getOriginalDOM() { return $this->originalDOM; }
	
	public function getOriginalURL() { return $this->originalURL; }
	
	public function getRemixDOM() { return $this->remixDOM; }
	
	public function getRemixURL() { return $this->remixURL; }
	
	public function getImgURL() { return $this->imgURL; }
	
	public function getIsFeatured() { return $this->isFeatured; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	# Setters
	public function setCampaignID($int) { $this->campaignID = $int; }
	
	public function setOriginalDOM($str) { $this->originalDOM = $str; }
	
	public function setOriginalURL($str) { $this->originalURL = $str; }
	
	public function setRemixDOM($str) { $this->remixDOM = $str; }
	
	public function setRemixURL($str) { $this->remixURL = $str; }
	
	public function setImgURL($str) { $this->imgURL = $str; }
	
	public function setIsFeatured($bool) { $this->isFeatured = $bool; }
	
	
	# Static Methods
	public static function getObjectsByCampaignID($campaignID, $start=FactoryObject::LIMIT_BEGINNING, $quantity = FactoryObject::LIMIT_ALL) {
		$query_string = "SELECT remixes.id as itemID 
						   FROM remixes
						  WHERE (remixes.campaign_id = ".DBConn::clean($campaignID)." OR ".DBConn::clean($campaignID)."=".DBConn::clean(Remix::CAMPAIGN_ALL).")
						    AND (remixes.remix_url != '')
					   ORDER BY remixes.id desc";
		
		return Remix::getObjects($query_string, $start, $quantity);
	}
	
	public static function getAllObjects() {
		$query_string = "SELECT remixes.id as itemID 
						   FROM remixes
					   ORDER BY remixes.id";
		return Remix::getObjects($query_string);
	}
}
?>