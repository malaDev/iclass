<?php

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require("../include/config.php");
require("../include/functions.php");
require ('functions_comments.php');
$id = $_GET["q"];
$reply = $_GET["b"];
$reply = htmlspecialchars(stripslashes($reply));
$reply = mysql_real_escape_string($reply); 
$reply = make_links($reply);
$reply = embed_youtube($reply);
$uvanetid = $_GET["u"];

$sql = "SELECT * FROM users WHERE uvanetid = '{$uvanetid}'";
$result = mysql_query($sql);
$user = mysql_fetch_array($result);
$user_id = $user['id'];
$query3 = "INSERT INTO replies (user_id, comment_id, body) VALUES ('$user_id', '$id', '$reply')";
$result3 = mysql_query($query3) or die();
$query4 = "UPDATE comments SET latest_update = CURRENT_TIMESTAMP WHERE id = $id";
$result4 = mysql_query($query4) or die();

require 'getReply.php';
?>