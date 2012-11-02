<?PHP
	set_include_path("../");
	include("models/DBConn.php");
	include("models/Campaign.php");
	include("models/Remix.php");
	include("models/Cache.php");
	echo('<?xml version="1.0" encoding="ISO-8859-1" ?>');
?>
<rss version="2.0">
	<channel>
		<title>NewsJack</title>
		<link>http://www.newsjack.in</link>
		<description>All the News You'd Love to See</description>
		<?php
			if(isset($_GET['c']))
				$campaign = Campaign::getObjectByCode($_GET['c']);
			
			$remixes = Remix::getObjectsByCampaignID($campaign?$campaign->getItemID():Remix::CAMPAIGN_ALL, 10);
			
			foreach($remixes as $remix) {
				if($remix->getRemixURL() == "")
					continue;
				?>
				<item>
					<title>A New Remix</title>
					<link><?PHP echo($remix->getRemixURL()); ?></link>
					<description>A New Remix</description>
				</item>
				<?PHP
			}
		?>
	</channel>
</rss>