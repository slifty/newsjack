<?PHP
	include_once("includes/common.php");
	if(!User::isAdministrator()) {
		header("Location: login.php");
		exit();
	}
	
	
	$campaigns = Campaign::getObjects(0,10);
	
?>

<html>
	<head>
		<title>Campaigns</title>
		<?PHP include("includes/head.php"); ?>
	</head>
	<body>
		<?PHP include("includes/header.php"); ?>
		<h1>Campaigns</h1>
		<table>
			<tr>
				<th>Title</th>
				<th>URL</th>
				<th></th>
			</tr>
			<?PHP
				foreach($campaigns as $campaign) {
					?>
					<tr>
						<td><?=$campaign->getTitle();?></td>
						<td><a href="http://<?=$ROOT_URL."/index.php?c=".$campaign->getItemID();?>">http://<?=$ROOT_URL."/index.php?c=".$campaign->getItemID();?></a></td>
						<td>
							<div class="gallery"><a href="gallery/php">Gallery</a></div>
							<div class="edit"><a href="edit.php">Edit</a></div>
						</td>
					</tr>
					<?PHP
				}
			?>
		</table>
		<div>
			<div class="create"><a href="campaign.php">Create a new Campaign</a></div>
		</div>
		<?PHP include("includes/footer.php"); ?>
	</body>
</html>