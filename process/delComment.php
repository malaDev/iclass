<?php

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once("../include/config.php");
require("../include/functions.php");
$cid = $_GET["q"];
$uvanetid = $_GET["u"];
$folderid = $_GET["f"];
$query = "SELECT file FROM comments WHERE id = $cid";
$result3 = mysql_query($query);
$file = mysql_fetch_array($result3);
$file = $file['file'];
if ($file != "") {
	unlink("../uploads/$file");
}
$sql = "DELETE FROM comments WHERE id = $cid";
$result = mysql_query($sql);
$sql2 = "DELETE FROM replies WHERE comment_id = $cid";
$result2 = mysql_query($sql2);

require 'getComment.php';
?>