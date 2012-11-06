<html>
	<head>
		<title>Campaign</title>
		<?PHP include("includes/head.php"); ?>
		<script src="scripts/html2canvas.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(function() {
				var html2obj = html2canvas($('body'), {
					logging:true,
					onrendered: function(canvas) {
						window.a = canvas;
						var img = canvas.toDataURL();
						$.ajax({
							url: "api/saveimg.php",
							type: "POST",
							data: {
								'r': 597,
								'source':img
							},
							success: function(data) {
							}
						});
					}
				});
			});
		</script>
	</head>
	<body>
		<p>TESTING THINGEY</p>
		<div><img src="http://upload.wikimedia.org/wikipedia/en/thumb/e/ed/Nyan_cat_250px_frame.PNG/220px-Nyan_cat_250px_frame.PNG" /></div>
	</body>
</html>