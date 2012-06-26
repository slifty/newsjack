<html>
	<head>
		<link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="styles/index.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="styles/springMessage.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<script src="scripts/jquery-1.7.2.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="scripts/springMessage.js" type="text/javascript" charset="utf-8"></script>

		<script type="text/javascript">
			$(function() {
				$("#subscribe").springMessage({
					heightOffset: 150,
					minTopOffset: 300
				});
			});
		</script>
		<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-31145789-1']);
		_gaq.push(['_trackPageview']);

		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		</script>
	</head>
	<body>
		<?PHP include("partials/topbar.php"); ?>
		<?PHP include("partials/subscribe.php"); ?>
		<div id="preform" class="content">
			<h2>Pick a site to Remix</h2>
			<ul id="paperlist">
				<li id="cnn" ><a href="remix.php?url=http://www.cnn.com"><img src="http://i.cdn.turner.com/cnn/.e/img/3.0/global/header/hdr-main.gif" alt="CNN" /></a></li>
				<li id="fox" ><a href="remix.php?url=http://www.foxnews.com"><img src="http://global.fncstatic.com/static/all/img/head/logo-foxnews-update.png" alt="Fox News" /></a></li>
			</ul>
		</div>
		<div id="freeform" class="content">
			<h2>Enter a site to Remix</h2>
			<div id="general_remix">
				<form action="remix.php" method="GET">
					<label for="url">URL:</label><input type="text/css" id="url" name="url"/>
					<input type="submit" value="Remix" />
				</form>
			</div>
		</div>
	</body>
</html>