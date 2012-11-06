<?PHP
	include_once("includes/common.php");
	if(!User::isAdministrator()) {
		header("Location: login.php");
		exit();
	}
	
	$campaign = new Campaign();
	$mods = array(
		"introduction:headline" => null,
		"introduction:explanation" => null,
		"mix-master-dialog:title" => null,
		"mix-master-dialog:html-header" => null,
		"dropdown:headline" => null,
		"dropdown:explanation" => null,
	);
	
	// Are we loading an existing campaign?
	if($_GET['c']) {
		$campaign = Campaign::getObject($_GET['c']);
		if($campaign == null) {
			header("Location: campaign.php");
			exit();
		}
	}
	
	foreach($campaign->getLocaleMods() as $mod) {
		$mods[$mod->getModKey()] = $mod;
	}
	
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$campaign->setTitle(isset($_POST['title'])?$_POST['title']:"");
		$campaign->setDescription(isset($_POST['description'])?$_POST['description']:"");
		$campaign->setCode(isset($_POST['code'])?$_POST['code']:"");
		$campaign->save();

		foreach($mods as $key=>$mod) {
			if(!isset($_POST[$key]))
				continue;
			
			$mods[$key] = ($mods[$key] == null)?new LocaleMod():$mods[$key];
			$mods[$key]->setModKey($key);
			$mods[$key]->setModValue($_POST[$key]);
			$mods[$key]->setCampaignID($campaign->getItemId());
			$mods[$key]->save();
			
			if($_POST[$key] == "")
				$mods[$key]->delete();
		}
		
		if(isset($_POST['delete'])) {
			$campaign->delete();
		}
		
		header("Location: campaign.php?c=".$campaign->getItemId());
		exit();
	}
?>

<html>
	<head>
		<title>Campaign</title>
		<?PHP include("includes/head.php"); ?>
	</head>
	<body>
		<?PHP include("includes/header.php"); ?>
		<h1>Campaign</h1>
		<form method="POST">
			<h2>Basic Information</h2>
			<p>Provide information about the campaign</p>
			<ul>
				<li><label for="title">Title</label><input type="text" name="title" id="title" value="<?= $campaign->getTitle(); ?>"/><div class="note"></div></li>
				<li><label for="description">Description</label><textarea name="description" id="description" /><?= $campaign->getDescription(); ?></textarea><div class="note"></div></li>
				<li><label for="code">Custom URL</label>http://www.example.com/<input type="text" name="code" id="code" value="<?= $campaign->getCode(); ?>" /><div class="note"></div></li>
			</ul>
			<h2>Suggested Pages</h2>
			<p>Give your users a few suggestions about what pages they should remix.</p>
			<ul>
				<li>
					<ul>
						<li><label for="site_title">Title</label><input type="" name="site_title" id="site_title" /></li>
						<li><label for="site_url">URL</label>http://<input type="" name="site_url" id="site_url" /></li>
					</ul>
				</li>
			</ul>
			
			<h2>Custom Instructions</h2>
			<p>Change the words that will be used to describe the campaign (Leave blank to keep defaults)</p>
			<ul>
				<li><label for="introduction:headline">Instruction Title:</label><input type="text" name="introduction:headline" id="introduction:headline" value="<?= $mods["introduction:headline"]?$mods["introduction:headline"]->getModValue():""; ?>" /><div class="note"></div></li>
				<li><label for="introduction:explanation">Instruction Description:</label><input type="text" name="introduction:explanation" id="introduction:explanation" value="<?= $mods["introduction:explanation"]?$mods["introduction:explanation"]->getModValue():""; ?>" /><div class="note"></div></li>
				<li><label for="mix-master-dialog:title">Remix Title:</label><input type="text" name="mix-master-dialog:title" id="mix-master-dialog:title" value="<?= $mods["mix-master-dialog:title"]?$mods["mix-master-dialog:title"]->getModValue():""; ?>" /><div class="note"></div></li>
				<li><label for="mix-master-dialog:html-header">Remix Subtitle:</label><input type="text" name="mix-master-dialog:html-header" id="mix-master-dialog:html-header" value="<?= $mods["mix-master-dialog:html-header"]?$mods["mix-master-dialog:html-header"]->getModValue():""; ?>" /><div class="note"></div></li>
				<li><label for="dropdown:headline">Dropdown Headline:</label><input type="text" name="dropdown:headline" id="dropdown:headline" value="<?= $mods["dropdown:headline"]?$mods["dropdown:headline"]->getModValue():""; ?>" /><div class="note"></div></li>
				<li><label for="dropdown:explanation">Dropdown Description:</label><input type="text" name="dropdown:explanation" id="dropdown:explanation" value="<?= $mods["dropdown:explanation"]?$mods["dropdown:explanation"]->getModValue():""; ?>" /><div class="note"></div></li>
			</ul>
			
			<h2>Limit Tags</h2>
			<p>Restrict the type of content that users can remix</p>
			<ul>
				<li><input type="checkbox" name="" id="" value="" /><label for="">Paragraphs</label></li>
				<li><input type="checkbox" name="" id="" value="" /><label for="">Headings</label></li>
				<li><input type="checkbox" name="" id="" value="" /><label for="">Links</label></li>
				<li><input type="checkbox" name="" id="" value="" /><label for="">Images</label></li>
				<li><input type="checkbox" name="" id="" value="" /><label for="">Lists</label></li>
			</ul>
			<input type="submit" name="save" value="save"/>
			<?PHP if($campaign->getItemID() != 0) { ?>
			<input type="submit" name="delete" value="delete"/>
			<?PHP } ?>
		</form>
		<?PHP include("includes/footer.php"); ?>
	</body>
</html>