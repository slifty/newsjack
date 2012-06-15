<?php
	set_include_path($_SERVER['DOCUMENT_ROOT']);
	include("models/DBConn.php");
	include("models/Remix.php");

// This is really quickly written because we need to launch now and patch later.


if (array_key_exists('url', $_GET) && array_key_exists('r', $_GET)) {
	$remix = new Remix($_GET['r']);
	
	if($remix->getRemixURL() == "") {
		// Make sure this hasn't already been saved (otherwise this would be super easy to just hack at this point)
		$remix->setRemixURL($_GET['url']);
		$remix->save();
	}
}

?>