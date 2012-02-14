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

$id = $_GET["subfolder"];
foreach ($admin_users as $admin_user) {
	if (isset($uvanetid) && $uvanetid == $admin_user) {
		// Delete section
		$sql6 = "DELETE FROM " . DB_COURSE_ITEMS . " WHERE folder = $id";
		$result6 = mysql_query($sql6);
		$sql = "DELETE FROM " . DB_COURSE_FOLDERS . " WHERE folder_id = $id";
		$result = mysql_query($sql);
		if (mysql_affected_rows() > 0) {
			// Delete all the episodes linked to the section (recursive)
			function deleteFolders($id) {
				$sql2 = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent = $id";
				$result2 = mysql_query($sql2);
				while ($episode = mysql_fetch_array($result2)) {
					$sql3 = "DELETE FROM " . DB_COURSE_FOLDERS . " WHERE folder_id = ".$episode['folder_id'];
					$result3 = mysql_query($sql3);
					deleteItems($episode['folder_id']);
					deleteFolders($episode['folder_id']);
				}
			}
			function deleteItems($id) {
					$sql5 = "DELETE FROM " . DB_COURSE_ITEMS . " WHERE folder = $id";
					$result5 = mysql_query($sql5);
			}
			deleteFolders($id);
		} else {
			echo "<div class='error'>Kan de aflevering die verwijderd moet worden niet vinden</div>";
		}
// Show all the sections
		require 'getEpisodes.php';
		break;
	}
}
?>
