<?php
/* Page called with AJAX */

// No cache headers for AJAX
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require("../../include/config.php");
require("../../include/process.php");
?>
<?php

//Change from not done to done (Insert in database)
if ($_GET['done'] == 'notdone') {
	$id = $_GET['id'];
	$folderid = $_GET['fid'];
	$foldersResult = mysql_query("SELECT * FROM progress WHERE id = $id AND folder_id = $folderid");
	$numRowsDone = mysql_num_rows($foldersResult);
	if ($numRowsDone == 0) 
		mysql_query("INSERT INTO progress (id, folder_id) VALUES ($id, $folderid)");
	echo "<label onclick=\"change_progress($id, $folderid, 'done')\" class=\"checkbox\"><input type=\"checkbox\" checked> Changed to Done!</label>";
//Change from done to not done (Detele from database)
} else {
	$id = $_GET['id'];
	$folderid = $_GET['fid'];
	mysql_query("DELETE FROM progress WHERE id = $id AND folder_id = $folderid");
	echo "<label onclick=\"change_progress($id, $folderid, 'notdone')\" class=\"checkbox\"><input type=\"checkbox\"> Changed to  not Done</label>";
}
?>