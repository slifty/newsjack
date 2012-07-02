<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once("conf.php");
require_once("models/DBConn.php");

// Get connection
$mysqli = DBConn::connect();
if(!$mysqli || $mysqli->connect_error) {
	echo("Could not connect to DB.  Did you follow the install instructions in README?\n");
	die();
}

// Look up installed version
$result = $mysqli->query("select appinfo.version as version
				  			from appinfo");

if(!$result || $result->num_rows == 0)
	$version = '0';
else {
	$resultArray = $result->fetch_assoc();
	$version = $resultArray['version'];
	$result->free();
}

echo("Current Version: ".$version."\n");
switch($version) {
	case '0': // Never installed before
		echo("Fresh Install...\n");
		echo("Creating appinfo table\n");
		$mysqli->query("CREATE TABLE appinfo (version varchar(8))") or print($mysqli->error);
		$mysqli->query("INSERT INTO appinfo (version) values('1');") or print($mysqli->error);
			
	case '1': // First update
		echo("Creating remixes table\n");
		$mysqli->query("CREATE TABLE remixes (id int auto_increment primary key,
											original_url text,
											original_dom text,
											remix_url text,
											remix_dom text,
											date_created datetime)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='2';") or print($mysqli->error);
		
	case '2':
		echo("Creating caches table\n");
		$mysqli->query("CREATE TABLE caches (id int auto_increment primary key,
											cached_html text,
											cached_url text,
											date_created datetime)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='3';") or print($mysqli->error);
		
	case '3':
		echo("Adding indexes to caches table\n");
		$mysqli->query("ALTER TABLE caches
							ADD INDEX cached_url (cached_url(255) ASC)") or print($mysqli->error);
		
		echo("Adding indexes to remixes table\n");
		$mysqli->query("ALTER TABLE remixes
							ADD INDEX original_url (original_url(255) ASC)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='4';") or print($mysqli->error);
		
	case '4':
		echo("Updating caches table\n");
		$mysqli->query("ALTER TABLE caches
							CHANGE COLUMN `cached_html` `cached_html` MEDIUMTEXT NULL DEFAULT NULL") or print($mysqli->error);

		echo("Adding indexes to remixes table\n");
		$mysqli->query("ALTER TABLE remixes
							CHANGE COLUMN `original_dom` `original_dom` MEDIUMTEXT NULL DEFAULT NULL,
							CHANGE COLUMN `remix_dom` `remix_dom` MEDIUMTEXT NULL DEFAULT NULL") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='5';") or print($mysqli->error);
		
	case '5':
		echo("Creating locales table\n");
		$mysqli->query("CREATE TABLE locales (id int auto_increment primary key,
											hud_overlay_html text,
											input_unload_blocked text,
											mix_master_too_big_to_change text,
											mix_master_too_big_to_remix_html text,
											dialog_common_ok text,
											dialog_common_nevermind text,
											dialog_common_close text,
											dialog_common_product_name text,
											mix_master_html_header text,
											mix_master_skeleton_header text,
											mix_master_rendering_header text,
											mix_master_basic_source_tab text,
											mix_master_advanced_source_tab text,
											mix_master_title text,
											uproot_dialog_header text,
											uproot_dialog_publishing text,
											uproot_dialog_error text,
											uproot_dialog_success text,
											introduction_headline text,
											introduction_explanation text,
											date_created datetime)") or print($mysqli->error);

		echo("Creating campaigns table\n");
		$mysqli->query("CREATE TABLE campaigns (id int auto_increment primary key,
											code varchar(8),
											locale_id int,
											logo_url varchar(255),
											date_created datetime)") or print($mysqli->error);
											
		echo("Updating remixes table\n");
		$mysqli->query("ALTER TABLE remixes
			              ADD COLUMN campaign_id varchar(8) AFTER id,
						  ADD INDEX campaign_id (campaign_id ASC)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("UPDATE appinfo set version ='6';") or print($mysqli->error);
	
	default:
		echo("Finished updating the schema\n");
}
?>