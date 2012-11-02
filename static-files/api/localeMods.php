<?php
	set_include_path("../");
	include("models/Campaign.php");
	
	$json = "";
	
	// Returns a campaign JSON object (which includes locale mods and other information)
	if (array_key_exists('c', $_GET)) {
		$campaign = new Campaign($_GET['c']);
		$localeMods = $campaign->getLocaleMods();
		
		$languageArray = array();
		foreach($localeMods as $localeMod) {
			$languageArray[$localeMod->getModKey()] = $localeMod->getModValue();
			
		}
	
		$json = json_encode($languageArray);
	}
	
	if(array_key_exists('callback', $_GET))
		echo($_GET['callback']."(".$json.");");
	else
		echo($json);
?>
