<?php
	$url = "http://www.nytimes.com";
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$data = curl_exec($ch);
	curl_close($ch);
	
	// Remove all scripts (force a "noscript" environment)
	$data = preg_replace('/\<script.*?\>.*?\<\/script.*?\>/is',"", $data);
	$data = preg_replace('/<.*noscript>/i',"", $data);
	
	// Add in the Page One Remix code
	$injection = '<script type="text/javascript" src="webxray.js" class="webxray"></script>';
	$data = str_replace("</body>",$injection."</body>", $data);
	
	echo $data;
?>