<?php
#  Models classes
include_once ("conf.php");
include_once ("models/DBConn.php");
include_once ("models/User.php");
include_once ("models/Campaign.php");
include_once ("models/Remix.php");

session_start();

// Log in as appropriate
if (isset($_POST["username"]) && isset($_POST["password"])) {
	if(!User::logIn($_POST["username"], $_POST["password"])) {
		header("Location: login.php?e=1");
		exit();
	}
} elseif (isset($_SESSION["username"]) && isset($_SESSION["password"])) {
	User::logIn($_SESSION["username"], $_SESSION["password"]);
}
?>