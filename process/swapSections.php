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

$cur_id = $_GET["curid"];
$prev_id = $_GET["previd"];

foreach ($admin_users as $admin_user) {
	if (isset($uvanetid) && $uvanetid == $admin_user) {
		// Add section
		$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE folder_id = $cur_id";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 1) {
			$cur_section = mysql_fetch_array($result);
			$sql2 = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE folder_id = $prev_id";
			$result2 = mysql_query($sql2);
			if (mysql_num_rows($result2) == 1) {
				$prev_section = mysql_fetch_array($result2);
				$prev_weight = $prev_section['weight'];
				$cur_weight = $cur_section['weight'];
				mysql_query("UPDATE " . DB_COURSE_FOLDERS . " SET weight = $prev_weight WHERE folder_id = $cur_id");
				if (mysql_affected_rows() != 1){
					echo "<div class='error'>Secties kunnen niet correct worden omgedraait!</div>";
				}
				mysql_query("UPDATE " . DB_COURSE_FOLDERS . " SET weight = $cur_weight WHERE folder_id = $prev_id");
				if (mysql_affected_rows() != 1){
					echo "<div class='error'>Secties kunnen niet correct worden omgedraait!</div>";
				}
			} else {
				echo "<div class='error'>één van de secties die omgedraait moet worden kan niet worden gevonden</div>";
			}
		} else {
			echo "<div class='error'>één van de secties die omgedraait moet worden kan niet worden gevonden</div>";
		}
		if (!mysql_affected_rows() > 0) {
			echo "<div class='error'>Secties kunnen niet worden omgedraait!!</div>";
		}
		// Show all the sections
		require 'getSections.php';
		break;
	}
}
?>
