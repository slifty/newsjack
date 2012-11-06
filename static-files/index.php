<?PHP
	include("includes/common.php");
	$campaign = Campaign::getObject(isset($_GET['c'])?$_GET['c']:0);
?>
<html>
	<head>
		<title>NewsJack</title>
		<?PHP include("includes/head.php"); ?>
		<link rel="stylesheet" href="styles/index.css" type="text/css" media="screen" title="no title" charset="utf-8">
	</head>
	<body>
		<?PHP include("includes/header.php"); ?>
		<div id="preform" class="content">
			<h2>Pick a site to Remix</h2>
			<ul id="paperlist">
				<li id="cnn" ><a href="remix.php?url=http://www.cnn.com<?=$campaign?"&c=".$campaign->getItemId():"" ?>"><img src="http://i.cdn.turner.com/cnn/.e/img/3.0/global/header/hdr-main.gif" alt="CNN" /></a></li>
				<li id="fox" ><a href="remix.php?url=http://www.foxnews.com<?=$campaign?"&c=".$campaign->getItemId():"" ?>"><img src="http://global.fncstatic.com/static/all/img/head/logo-foxnews-update.png" alt="Fox News" /></a></li>
			</ul>
		</div>
		<div id="freeform" class="content">
			<h2>Enter a site to Remix</h2>
			<div id="general_remix">
				<form action="remix.php" method="GET">
					<?PHP if($campaign) { ?>
						<input type="hidden" name="c" value="<?=$campaign->getItemId() ?>" />
					<?PHP }?>
					<label for="url">URL:</label><input type="text/css" id="url" name="url" />
					<input type="submit" value="Remix" />
				</form>
			</div>
		</div>
	</body>
</html>