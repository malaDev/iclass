<?php

/* Page called with AJAX */

// No cache headers for AJAX
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once("../include/config.php");
require("../include/preprocess.php");
require("../include/functions.php");

$title = $_GET["title"];
$weight = $_GET["weight"];
foreach ($admin_users as $admin_user) {
	if (isset($uvanetid) && $uvanetid == $admin_user) {
		// Add section
		$sql = "INSERT INTO " . DB_COURSE_FOLDERS . " (weight, parent, title) VALUES ('$weight', '1', '$title')";
		$result = mysql_query($sql);
		if (!mysql_affected_rows() > 0) {
			echo "<div class='error'>Sectie kan niet worden toegevoegd!</div>";
		}
		// Show all the sections
		require 'getSections.php';
		break;
	}
}
?>
