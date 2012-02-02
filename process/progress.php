<?php
/* Page called with AJAX */

// No cache headers for AJAX
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require("../include/config.php");
require("../include/preprocess.php");
?>
<?php

//Change from not done to done (Insert in database)
if ($_GET['done'] == "done") {
	$id = $_GET['id'];
	$folderid = $_GET['fid'];
	mysql_query("INSERT INTO progress (id, folder_id) VALUES ($id, $folderid)");
	echo "Veranderd naar gedaan<input type='checkbox' checked='yes' name='done' onclick=\"progress('$id', '$folderid', 'notdone')\" />";
//Change from done to not done (Detele from database)
} else {
	$id = $_GET['id'];
	$folderid = $_GET['fid'];
	mysql_query("DELETE FROM progress WHERE id = $id AND folder_id = $folderid");
	echo "Veranderd naar niet gedaan<input type='checkbox' name='done' onclick=\"progress('$id', '$folderid', 'done')\" />";
}
?>