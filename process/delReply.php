<?php
/* Page called with AJAX */

// No cache headers for AJAX
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require("../include/config.php");
require("../include/preprocess.php");
require("../include/functions.php");

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
require 'getReply.php';
?>