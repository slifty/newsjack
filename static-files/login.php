<?PHP
	include_once("includes/common.php");
	
	if(User::isLoggedIn()) {
		Header("Location: index.php");
		exit();
	}
	
?>

<html>
	<head>
		<title>Log In</title>
		<?PHP include("includes/head.php"); ?>
	</head>
	<body>
		<?PHP include("includes/header.php"); ?>
		<h1>Log In</h1>
		<form method="post">
			<ul>
				<li><label for="username">Username</label><input type="text" id="username" name="username"/></li>
				<li><label for="password">Password</label><input type="password" id="password" name="password"/></li>
				<li><input type="submit" value="Log In" /></li>
			</ul>
		</form>
		
		<?PHP include("includes/footer.php"); ?>
	</body>
</html>