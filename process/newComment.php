<?php

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once("../include/config.php");
require("../include/functions.php");
require ('functions_comments.php');

$title = $_GET["t"];
$title = htmlspecialchars(stripslashes($title));
$title = mysql_real_escape_string($title); 
 
$file = $_GET['f'];
$comment = $_GET["b"];
$comment = htmlspecialchars(stripslashes($comment));
$comment = mysql_real_escape_string($comment); 
$comment = make_links($comment);
$comment = embed_youtube($comment);
$uvanetid = $_GET["u"];
$folderid= $_GET["fid"];

$sql = "SELECT * FROM users WHERE uvanetid = '{$uvanetid}'";
$result4 = mysql_query($sql);
$user = mysql_fetch_array($result4);
$user_id = $user['id'];
$query3 = "INSERT INTO comments (user_id, folder_id, title, file, body, latest_update) VALUES ('$user_id', '$folderid', '$title', '$file', '$comment', CURRENT_TIMESTAMP)";
$result3 = mysql_query($query3) or die();
require 'getComment.php';
?>