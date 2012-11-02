<?PHP
	include_once("includes/common.php");
	User::logout();
	header("Location: index.php");
?>