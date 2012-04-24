<?PHP
	include("../conf.php");
	$url = isset($_GET['url'])?$_GET['url']:"";
	global $BITLY_API_USER, $BITLY_API_KEY;
	$connectURL = 'http://api.bit.ly/v3/shorten?login='.$BITLY_API_USER.'&apiKey='.$BITLY_API_KEY.'&uri='.urlencode($url);
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$connectURL);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
	$data = curl_exec($ch);
	curl_close($ch);
	echo($data);
?>
