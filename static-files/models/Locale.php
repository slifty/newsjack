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

class Locale extends FactoryObject{
	
	# Constants
	// Initialization Types
	const INIT_EMPTY = -1;
	const INIT_DEFAULT = 0;
	
	
	# Instance Variables
	private $code; // string
	private $hudOverlayHTML; // string
	private $inputUnloadBlocked; // string
	private $mixMasterTooBigToChange; // string
	private $mixMasterTooBigToRemix; // string
	private $dialogCommonOK; // string
	private $dialogCommonNevermind; // string
	private $dialogCommonClose; // string
	private $dialogCommonProductName; // string
	private $mixMasterHTMLHeader; // string
	private $mixMasterSkeletonHeader; // string
	private $mixMasterRenderingHeader; // string
	private $mixMasterBasicSourceTab; // string
	private $mixMasterAdvancedSourceTab; // string
	private $mixMasterTitle; // string
	private $mixMasterUprootDialogHeader; // string
	private $mixMasterUprootDialogPublishing; // string
	private $mixMasterUprootDialogSuccess; // string
	private $introductionHeadline; // string
	private $introductionExplanation; // string
	private $dateCreated; // timestamp
	
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
			$dataArray['hudOverlayHTML'] = "";
			$dataArray['inputUnloadBlocked'] = "";
			$dataArray['mixMasterTooBigToChange'] = "";
			$dataArray['mixMasterTooBigToRemix'] = "";
			$dataArray['dialogCommonOK'] = "";
			$dataArray['dialogCommonNevermind'] = "";
			$dataArray['dialogCommonClose'] = "";
			$dataArray['dialogCommonProductName'] = "";
			$dataArray['mixMasterHTMLHeader'] = "";
			$dataArray['mixMasterSkeletonHeader'] = "";
			$dataArray['mixMasterRenderingHeader'] = "";
			$dataArray['mixMasterBasicSourceTab'] = "";
			$dataArray['mixMasterAdvancedSourceTab'] = "";
			$dataArray['mixMasterTitle'] = "";
			$dataArray['mixMasterUprootDialogHeader'] = "";
			$dataArray['mixMasterUprootDialogPublishing'] = "";
			$dataArray['mixMasterUprootDialogSuccess'] = "";
			$dataArray['introductionHeadline'] = "";
			$dataArray['introductionExplanation'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Load a default object
		if($objectString === Remix::INIT_DEFAULT) {
			$dataArray = array();
			$dataArray['itemID'] = 0;
			$dataArray['code'] = "";
			$dataArray['hudOverlayHTML'] = "";
			$dataArray['inputUnloadBlocked'] = "";
			$dataArray['mixMasterTooBigToChange'] = "";
			$dataArray['mixMasterTooBigToRemix'] = "";
			$dataArray['dialogCommonOK'] = "";
			$dataArray['dialogCommonNevermind'] = "";
			$dataArray['dialogCommonClose'] = "";
			$dataArray['dialogCommonProductName'] = "";
			$dataArray['mixMasterHTMLHeader'] = "";
			$dataArray['mixMasterSkeletonHeader'] = "";
			$dataArray['mixMasterRenderingHeader'] = "";
			$dataArray['mixMasterBasicSourceTab'] = "";
			$dataArray['mixMasterAdvancedSourceTab'] = "";
			$dataArray['mixMasterTitle'] = "";
			$dataArray['mixMasterUprootDialogHeader'] = "";
			$dataArray['mixMasterUprootDialogPublishing'] = "";
			$dataArray['mixMasterUprootDialogSuccess'] = "";
			$dataArray['introductionHeadline'] = "";
			$dataArray['introductionExplanation'] = "";
			$dataArray['dateCreated'] = 0;
			$dataArrays[] = $dataArray;
			return $dataArrays;
		}
		
		// Set up for lookup
		$mysqli = DBConn::connect();
		
		// Load the object data
		$queryString = "SELECT locales.id AS itemID,
							   locales.code AS code,
							   locales.hud_overlay_html AS hudOverLayHTML,
							   locales.input_unload_blocked AS inputUnloadBlocked,
							   locales.mix_master_too_big_to_change AS mixMasterTooBigToChange,
							   locales.mix_master_too_big_to_remix AS mixMasterTooBigToRemix,
							   locales.dialog_common_ok AS dialogCommonOK,
							   locales.dialog_common_nevermind AS dialogCommonNevermind,
							   locales.dialog_common_close AS dialogCommonClose,
							   locales.dialog_common_product_name AS dialogCommonProductName,
							   locales.mix_master_html_header AS mixMasterHTMLHeader,
							   locales.mix_master_skeleton_header AS mixMasterSkeletonHeader,
							   locales.mix_master_rendering_header AS mixMasterRenderingHeader,
							   locales.mix_master_basic_source_tab AS mixMasterBasicSourceTab,
							   locales.mix_master_advanced_source_tab AS mixMasterAdvancedSourceTab,
							   locales.mix_master_title AS mixMasterTitle,
							   locales.mix_master_uproot_dialog_header AS mixMasterUprootDialogHeader,
							   locales.mix_master_uproot_dialog_publishing AS mixMasterUprootDialogPublishing,
							   locales.mix_master_uproot_dialog_success AS mixMasterUprootDialogSuccess,
							   locales.introduction_headline AS introductionHeadline,
							   locales.introduction_explanation AS introductionExplanation,
							   unix_timestamp(locales.date_created) AS dateCreated
						  FROM locales
						 WHERE locales.id IN (".$objectString.")";
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
			$dataArray['hudOverlayHTML'] = $resultArray['hudOverlayHTML'];
			$dataArray['inputUnloadBlocked'] = $resultArray['inputUnloadBlocked'];
			$dataArray['mixMasterTooBigToChange'] = $resultArray['mixMasterTooBigToChange'];
			$dataArray['mixMasterTooBigToRemix'] = $resultArray['mixMasterTooBigToRemix'];
			$dataArray['dialogCommonOK'] = $resultArray['dialogCommonOK'];
			$dataArray['dialogCommonNevermind'] = $resultArray['dialogCommonNevermind'];
			$dataArray['dialogCommonClose'] = $resultArray['dialogCommonClose'];
			$dataArray['dialogCommonProductName'] = $resultArray['dialogCommonProductName'];
			$dataArray['mixMasterHTMLHeader'] = $resultArray['mixMasterHTMLHeader'];
			$dataArray['mixMasterSkeletonHeader'] = $resultArray['mixMasterSkeletonHeader'];
			$dataArray['mixMasterRenderingHeader'] = $resultArray['mixMasterRenderingHeader'];
			$dataArray['mixMasterBasicSourceTab'] = $resultArray['mixMasterBasicSourceTab'];
			$dataArray['mixMasterAdvancedSourceTab'] = $resultArray['mixMasterAdvancedSourceTab'];
			$dataArray['mixMasterTitle'] = $resultArray['mixMasterTitle'];
			$dataArray['mixMasterUprootDialogHeader'] = $resultArray['mixMasterUprootDialogHeader'];
			$dataArray['mixMasterUprootDialogPublishing'] = $resultArray['mixMasterUprootDialogPublishing'];
			$dataArray['mixMasterUprootDialogSuccess'] = $resultArray['mixMasterUprootDialogSuccess'];
			$dataArray['introductionHeadline'] = $resultArray['introductionHeadline'];
			$dataArray['introductionExplanation'] = $resultArray['introductionExplanation'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		$result->free();
		return $dataArrays;
	}
	
	public function load($dataArray) {
		$this->itemID = isset($dataArray["itemID"])?$dataArray["itemID"]:0;
		$this->code = isset($dataArray["code"])?$dataArray["code"]:"";
		$this->hudOverlayHTML = isset($dataArray["hudOverlayHTML"])?$dataArray["hudOverlayHTML"]:"";
		$this->inputUnloadBlocked = isset($dataArray["inputUnloadBlocked"])?$dataArray["inputUnloadBlocked"]:"";
		$this->mixMasterTooBigToChange = isset($dataArray["mixMasterTooBigToChange"])?$dataArray["mixMasterTooBigToChange"]:"";
		$this->mixMasterTooBigToRemix = isset($dataArray["mixMasterTooBigToRemix"])?$dataArray["mixMasterTooBigToRemix"]:"";
		$this->dialogCommonOK = isset($dataArray["dialogCommonOK"])?$dataArray["dialogCommonOK"]:"";
		$this->dialogCommonNevermind = isset($dataArray["dialogCommonNevermind"])?$dataArray["dialogCommonNevermind"]:"";
		$this->dialogCommonClose = isset($dataArray["dialogCommonClose"])?$dataArray["dialogCommonClose"]:"";
		$this->dialogCommonProductName = isset($dataArray["dialogCommonProductName"])?$dataArray["dialogCommonProductName"]:"";
		$this->mixMasterHTMLHeader = isset($dataArray["mixMasterHTMLHeader"])?$dataArray["mixMasterHTMLHeader"]:"";
		$this->mixMasterSkeletonHeader = isset($dataArray["mixMasterSkeletonHeader"])?$dataArray["mixMasterSkeletonHeader"]:"";
		$this->mixMasterRenderingHeader = isset($dataArray["mixMasterRenderingHeader"])?$dataArray["mixMasterRenderingHeader"]:"";
		$this->mixMasterBasicSourceTab = isset($dataArray["mixMasterBasicSourceTab"])?$dataArray["mixMasterBasicSourceTab"]:"";
		$this->mixMasterAdvancedSourceTab = isset($dataArray["mixMasterAdvancedSourceTab"])?$dataArray["mixMasterAdvancedSourceTab"]:"";
		$this->mixMasterTitle = isset($dataArray["mixMasterTitle"])?$dataArray["mixMasterTitle"]:"";
		$this->mixMasterUprootDialogHeader = isset($dataArray["mixMasterUprootDialogHeader"])?$dataArray["mixMasterUprootDialogHeader"]:"";
		$this->mixMasterUprootDialogPublishing = isset($dataArray["mixMasterUprootDialogPublishing"])?$dataArray["mixMasterUprootDialogPublishing"]:"";
		$this->mixMasterUprootDialogSuccess = isset($dataArray["mixMasterUprootDialogSuccess"])?$dataArray["mixMasterUprootDialogSuccess"]:"";
		$this->introductionHeadline = isset($dataArray["introductionHeadline"])?$dataArray["introductionHeadline"]:"";
		$this->introductionExplanation = isset($dataArray["introductionExplanation"])?$dataArray["introductionExplanation"]:"";
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
			$queryString = "UPDATE locales
							   SET locales.code = ".DBConn::clean($this->getCode()).",
								   locales.hud_overlay_html = ".DBConn::clean($this->getHudOverlayHTML()).",
								   locales.input_unload_blocked = ".DBConn::clean($this->getInputUnloadBlocked()).",
								   locales.mix_master_too_big_to_change = ".DBConn::clean($this->getMixMasterTooBigToChange()).",
								   locales.mix_master_too_big_to_remix = ".DBConn::clean($this->getMixMasterTooBigToRemix()).",
								   locales.dialog_common_ok = ".DBConn::clean($this->getDialogCommonOK()).",
								   locales.dialog_common_nevermind = ".DBConn::clean($this->getDialogCommonNevermind()).",
								   locales.dialog_common_close = ".DBConn::clean($this->getDialogCommonClose()).",
								   locales.dialog_common_product_name = ".DBConn::clean($this->getDialogCommonProductName()).",
								   locales.mix_master_html_header = ".DBConn::clean($this->getMixMasterHTMLHeader()).",
								   locales.mix_master_skeleton_header = ".DBConn::clean($this->getMixMasterSkeletonHeader()).",
								   locales.mix_master_rendering_header = ".DBConn::clean($this->getMixMasterRenderingHeader()).",
								   locales.mix_master_basic_source_tab = ".DBConn::clean($this->getMixMasterBasicSourceTab()).",
								   locales.mix_master_advanced_source_tab = ".DBConn::clean($this->getMixMasterAdvancedSourceTab()).",
								   locales.mix_master_title = ".DBConn::clean($this->getMixMasterTitle()).",
								   locales.mix_master_uproot_dialog_header = ".DBConn::clean($this->getMixMasterUprootDialogHeader()).",
								   locales.mix_master_uproot_dialog_publishing = ".DBConn::clean($this->getMixMasterUprootDialogPublishing()).",
								   locales.mix_master_uproot_dialog_success = ".DBConn::clean($this->getMixMasterUprootDialogSuccess()).",
								   locales.introduction_headline = ".DBConn::clean($this->getIntroductionHeadline()).",
								   locales.introduction_explanation = ".DBConn::clean($this->getIntroductionExplanation())."
							 WHERE locales.id = ".DBConn::clean($this->getItemID());
			
			$mysqli->query($queryString);
		} else {
			// Create a new record
			$queryString = "INSERT INTO locales
								   (locales.id,
									locales.code,
									locales.hud_overlay_html,
									locales.input_unload_blocked,
									locales.mix_master_too_big_to_change,
									locales.mix_master_too_big_to_remix,
									locales.dialog_common_ok,
									locales.dialog_common_nevermind,
									locales.dialog_common_close,
									locales.dialog_common_product_name,
									locales.mix_master_html_header,
									locales.mix_master_skeleton_header,
									locales.mix_master_rendering_header,
									locales.mix_master_basic_source_tab,
									locales.mix_master_advanced_source_tab,
									locales.mix_master_title,
									locales.mix_master_uproot_dialog_header,
									locales.mix_master_uproot_dialog_publishing,
									locales.mix_master_uproot_dialog_success,
									locales.introduction_headline,
									locales.introduction_explanation,
									locales.date_created)
							VALUES (0,
									".DBConn::clean($this->getCode()).",
									".DBConn::clean($this->getHudOverlayHTML()).",
									".DBConn::clean($this->getInputUnloadBlocked()).",
									".DBConn::clean($this->getMixMasterTooBigToChange()).",
									".DBConn::clean($this->getMixMasterTooBigToRemix()).",
									".DBConn::clean($this->getDialogCommonOK()).",
									".DBConn::clean($this->getDialogCommonNevermind()).",
									".DBConn::clean($this->getDialogCommonClose()).",
									".DBConn::clean($this->getDialogCommonProductName()).",
									".DBConn::clean($this->getMixMasterHTMLHeader()).",
									".DBConn::clean($this->getMixMasterSkeletonHeader()).",
									".DBConn::clean($this->getMixMasterRenderingHeader()).",
									".DBConn::clean($this->getMixMasterBasicSourceTab()).",
									".DBConn::clean($this->getMixMasterAdvancedSourceTab()).",
									".DBConn::clean($this->getMixMasterTitle()).",
									".DBConn::clean($this->getMixMasterUprootDialogHeader()).",
									".DBConn::clean($this->getMixMasterUprootDialogPublishing()).",
									".DBConn::clean($this->getMixMasterUprootDialogSuccess()).",
									".DBConn::clean($this->getIntroductionHeadline()).",
									".DBConn::clean($this->getIntroductionExplanation()).",
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
		$queryString = "DELETE FROM locales
						 WHERE locales.id = ".DBConn::clean($this->getItemID());
		$mysqli->query($queryString);
		
	}
	
	
	# Getters
	public function getCode() { return $this->code; }
	
	public function getHudOverlayHTML() { return $this->hudOverlayHTML; }
	
	public function getInputUnloadBlocked() { return $this->inputUnloadBlocked; }
	
	public function getMixMasterTooBigToChange() { return $this->mixMasterTooBigToChange; }
	
	public function getMixMasterTooBigToRemix() { return $this->mixMasterTooBigToRemix; }
	
	public function getDialogCommonOK() { return $this->dialogCommonOK; }
	
	public function getDialogCommonNevermind() { return $this->dialogCommonNevermind; }
	
	public function getDialogCommonClose() { return $this->dialogCommonClose; }
	
	public function getDialogCommonProductName() { return $this->dialogCommonProductName; }
	
	public function getMixMasterHTMLHeader() { return $this->mixMasterHTMLHeader; }
	
	public function getMixMasterSkeletonHeader() { return $this->mixMasterSkeletonHeader; }
	
	public function getMixMasterRenderingHeader() { return $this->mixMasterRenderingHeader; }
	
	public function getMixMasterBasicSourceTab() { return $this->mixMasterBasicSourceTab; }
	
	public function getMixMasterAdvancedSourceTab() { return $this->mixMasterAdvancedSourceTab; }
	
	public function getMixMasterTitle() { return $this->mixMasterTitle; }
	
	public function getMixMasterUprootDialogHeader() { return $this->mixMasterUprootDialogHeader; }
	
	public function getMixMasterUprootDialogPublishing() { return $this->mixMasterUprootDialogPublishing; }
	
	public function getMixMasterUprootDialogSuccess() { return $this->mixMasterUprootDialogSuccess; }
	
	public function getIntroductionHeadline() { return $this->introductionHeadline; }
	
	public function getIntroductionExplanation() { return $this->introductionExplanation; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	# Setters
	public function setCode($str) { return $this->code; }
	
	public function setHudOverlayHTML($str) { return $this->hudOverlayHTML; }
	
	public function setInputUnloadBlocked($str) { $this->inputUnloadBlocked = $str; }
	
	public function setMixMasterTooBigToChange($str) { $this->mixMasterTooBigToChange = $str; }
	
	public function setMixMasterTooBigToRemix($str) { $this->mixMasterTooBigToRemix = $str; }
	
	public function setDialogCommonOK($str) { $this->dialogCommonOK = $str; }
	
	public function setDialogCommonNevermind($str) { $this->dialogCommonNevermind = $str; }
	
	public function setDialogCommonClose($str) { $this->dialogCommonClose = $str; }
	
	public function setDialogCommonProductName($str) { $this->dialogCommonProductName = $str; }
	
	public function setMixMasterHTMLHeader($str) { $this->mixMasterHTMLHeader = $str; }
	
	public function setMixMasterSkeletonHeader($str) { $this->mixMasterSkeletonHeader = $str; }
	
	public function setMixMasterRenderingHeader($str) { $this->mixMasterRenderingHeader = $str; }
	
	public function setMixMasterBasicSourceTab($str) { $this->mixMasterBasicSourceTab = $str; }
	
	public function setMixMasterAdvancedSourceTab($str) { $this->mixMasterAdvancedSourceTab = $str; }
	
	public function setMixMasterTitle($str) { $this->mixMasterTitle = $str; }
	
	public function setMixMasterUprootDialogHeader($str) { $this->mixMasterUprootDialogHeader = $str; }
	
	public function setMixMasterUprootDialogPublishing($str) { $this->mixMasterUprootDialogPublishing = $str; }
	
	public function setMixMasterUprootDialogSuccess($str) { $this->mixMasterUprootDialogSuccess = $str; }
	
	public function setIntroductionHeadline($str) { $this->introductionHeadline = $str; }
	
	public function setIntroductionExplanation($str) { $this->introductionExplanation = $str; }
	
}
?>