<?php

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require("../include/config.php");
require("../include/functions.php");
$id = $_GET["q"];
$reply_id = $_GET["r"];
$uvanetid = $_GET["u"];
$sql = "DELETE FROM replies WHERE id = '{$reply_id}'";
$result = mysql_query($sql);

require 'getReply.php';
?>