<?php
	
	set_include_path($_SERVER['DOCUMENT_ROOT']);
	include("models/DBConn.php");
	include("models/Remix.php");
	$remixes = Remix::getObjects();
	
?>