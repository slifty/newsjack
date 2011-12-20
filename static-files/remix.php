<?php
	//
	ini_set("pcre.backtrack_limit", 100000000);
	
	// Cookie Jar
	$ckfile = tempnam ("/tmp", "CURLCOOKIE");
	
	// CURL
	$url = isset($_GET['url'])?$_GET['url']:"http://www.nytimes.com";
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$data = curl_exec($ch);
	curl_close($ch);
	
	
	// NYTimes fixes
	if(preg_match("/<meta http\-equiv=refresh content=\"15\;url\=\/\?(.*?)\"/" ,$data, $matches)) {
		$url = $url."/?".$matches[1];
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$data = curl_exec($ch);
		curl_close($ch);
	}
	
	// Remove all scripts (force a "noscript" environment)
	$data = preg_replace('/\<script.*?\>.*?\<\/script.*?\>/is',"", $data);
	$data = preg_replace('/<.*noscript>/i',"", $data);
	
	// Fox news fixes
	$data = preg_replace('/src=\".*\"(.*)dest_src=\"(.*)\"/i',"src=\"\\2\" \\1", $data);
	$data = preg_replace('/dest_src=\"(.*)\"(.*)src=\".*\"/i',"src=\"\\1\" \\2", $data);
	
	
	// General Image Fixes
	$data = preg_replace('/src=\"\/(.*?)\"/i', "src=\"".$url."/\\1\"", $data);
	$data = preg_replace('/src=\'\/(.*?)\'/i', "src=\'".$url."/\\1\'", $data);
	
	// Add in the Page One Remix code
	$injection = '<script type="text/javascript" src="webxray.js" class="webxray"></script>';
	$data = str_replace("</body>",$injection."</body>", $data);
	
	echo $data;
?>