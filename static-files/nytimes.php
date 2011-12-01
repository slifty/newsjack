<?php
	$url = "http://www.nytimes.com";
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$data = curl_exec($ch);
	curl_close($ch);
	
	// Remove the obnoxious ad crap
	$data = str_replace(".swf","", $data);
	$data = str_replace(".js","", $data);

	// Add in the Page One Remix code
	$injection = '<script type="text/javascript" src="webxray.js" class="webxray"></script>';
	$data = str_replace("</body>",$injection."</body>", $data);
	
	echo $data;
?>