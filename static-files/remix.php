<?php
	function get_final_url( $url, $timeout = 5 ) {
	    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

	    $cookie = tempnam ("/tmp", "CURLCOOKIE");
	    $ch = curl_init();
	    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
	    curl_setopt( $ch, CURLOPT_URL, $url );
	    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
	    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	    curl_setopt( $ch, CURLOPT_ENCODING, "" );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
	    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
	    $content = curl_exec( $ch );
	    $response = curl_getinfo( $ch );
	    curl_close ( $ch );

	    if ($response['http_code'] == 301 || $response['http_code'] == 302)
	    {
	        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
	        $headers = get_headers($response['url']);

	        $location = "";
	        foreach( $headers as $value )
	        {
	            if ( substr( strtolower($value), 0, 9 ) == "location:" )
	                return get_final_url( trim( substr( $value, 9, strlen($value) ) ) );
	        }
	    }

	    if (    preg_match("/window\.location\.replace\('(.*)'\)/i", $content, $value) ||
	            preg_match("/window\.location\=\"(.*)\"/i", $content, $value)
	    )
	    {
	        return get_final_url ( $value[1] );
	    }
	    else
	    {
	        return $response['url'];
	    }
	}
	set_include_path($_SERVER['DOCUMENT_ROOT']);
	include("models/DBConn.php");
	include("models/Remix.php");
	
	// Make horribly inefficient regular expressions work
	ini_set("pcre.backtrack_limit", 100000000);
	
	// Cookie Jar
	$ckfile = tempnam ("/tmp", "CURLCOOKIE");
	
	// Clean the URL
	$url = isset($_GET['url'])?$_GET['url']:"";
	$url = substr($url,0,7) == "http://"?$url:"http://".$url;
	$url = get_final_url($url);
	
	// CURL
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile); 
	curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$data = curl_exec($ch);
	curl_close($ch);
	
	
	// NYTimes fixes
	if(preg_match("/<meta http\-equiv=refresh content=\"15\;url\=\/\?(.*?)\"/" ,$data, $matches)) {
		$url = $url."/?".$matches[1];
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile); 
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
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
	
	// Store the original version
	$remix = new Remix();
	$remix->setOriginalDOM($data);
	$remix->setOriginalURL($url);
	$remix->save();
	
	
	
	// Add in the NewsJack code
	$injection = '<script type="text/javascript" src="webxray.js" class="webxray"></script><script type="text/javascript">var remix_id = '.$remix->getItemID().';</script>';
	$data = str_replace("</body>",$injection."</body>", $data);
	
	echo $data;
?>