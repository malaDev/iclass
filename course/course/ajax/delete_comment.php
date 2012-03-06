<?php
/* Page called with AJAX */

// No cache headers for AJAX
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once("../../include/config.php");
require("../../include/process.php");
require('../../util.php');
require("../../include/functions.php");

// comment id
$cid = $_GET["q"];

// uvanetid current user
$uvanetid = $_GET["u"];

// folder id (episode)
$folderid = $_GET["f"];

// Delete file if file was added to comment
$query = "SELECT file FROM comments WHERE id = $cid";
$result3 = mysql_query($query);
$file = mysql_fetch_array($result3);
$file = $file['file'];
if ($file != "") {
	unlink("../uploads/$file");
}

// Delete comment
$sql = "DELETE FROM comments WHERE id = $cid";
$result = mysql_query($sql);
// Delete all the replies linked to the comment
$sql2 = "DELETE FROM replies WHERE comment_id = $cid";
$result2 = mysql_query($sql2);

// Show all the comments linked to current folderid (episode)
echo 'Hier moet dan met behulp van ajax alle comments weer worden laten zien, maar weet nog niet hoe ik dat ga doen<br>';
echo 'misschien door hier een controller neer te zetten voor de comments, maar dat is ook lastig, want door AJAX heeft hij een verkeerde BASE (goedeBASE/course/ajax neemt hij nu als base)'

?>