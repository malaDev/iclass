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
$id = $_GET["q"];

// reply id
$reply_id = $_GET["r"];

// uvanetid current user
$uvanetid = $_GET["u"];

// Delete reply
$sql = "DELETE FROM replies WHERE id = '{$reply_id}'";
$result = mysql_query($sql);

// Show the replies of current comment (minus the deleted reply)
// Show all the comments linked to current folderid (episode)
echo 'Hier moet dan met behulp van ajax alle replies weer worden laten zien, maar weet nog niet hoe ik dat ga doen<br>';
echo 'misschien door hier een controller neer te zetten voor de replies, maar dat is ook lastig, want door AJAX heeft hij een verkeerde BASE (goedeBASE/course/ajax neemt hij nu als base)'
?>