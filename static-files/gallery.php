<?PHP
	include("includes/common.php");
	$campaign = Campaign::getObject($_GET['c']);
	
	if($campaign == null || $campaign->getItemId() == 0) {
		header("Location: index.php");
		exit();
	}
	
	$remixes = Remix::getObjectsByCampaignId($campaign->getItemId());

?>
<html>
	<head>
		<?PHP include("includes/head.php"); ?>
		<link rel="stylesheet" href="styles/gallery.css" type="text/css" media="screen" title="no title" charset="utf-8">
	</head>
	<body>
		<?PHP include("includes/header.php"); ?>
		<div class="content">
			<h2>Remixes</h2>
			<ul>
				<?PHP
					foreach($remixes as $remix) {
						?>
						<li class="remix">
							<div class="image">
								<?PHP if($remix->getImgURL() != "") { ?>
									<a href="<?= $remix->getRemixURL(); ?>"><img src="<?=$remix->getImgURL(); ?>"/></a>
								<?PHP } ?>
							</div>
							<div class="link"><a href="<?= $remix->getRemixURL(); ?>"><?=$remix->getRemixURL(); ?></a></div>
							<div class="tools">
								<?PHP
									if(User::isAdministrator()) {
										?>
										<div class="delete"><form action="remix_entry.php?r=<?=$remix->getItemId(); ?>" method="post"><input type="submit" name="delete" value="delete" /></form></div>
										<?PHP if($remix->getIsFeatured()) { ?>
											<div class="unfeature"><form action="remix_entry.php?r=<?=$remix->getItemId(); ?>" method="post"><input type="submit" name="unfeature" value="unfeature" /></form></div>
										<?PHP } else { ?>
											<div class="unfeature"><form action="remix_entry.php?r=<?=$remix->getItemId(); ?>" method="post"><input type="submit" name="feature" value="feature" /></form></div>
										<?PHP } ?>
										<?PHP
									}
								?>
							</div>
						</li>
						<?PHP
					}
				?>
			</ul>
		</div>
	</body>
</html>