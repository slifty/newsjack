<?PHP

set_include_path("../");
include("models/DBConn.php");
include("models/Remix.php");

// This is really quickly written because we need to launch now and patch later.


if (array_key_exists('source', $_POST) && array_key_exists('r', $_POST)) {
	$remix = Remix::getObject($_POST['r']);
	
	if($remix == null)
		exit;
	
	$img = $_POST['source'];
	$data = substr(strstr($img, "base64,"), 7);
	$filename = "remix".$remix->getItemId().".png";
	$path = 'screenshots/'.$filename;
	file_put_contents('../'.$path, base64_decode($data));
	
	$remix->setImgURL($path);
	$remix->save();
	
	echo($path);
}

?>