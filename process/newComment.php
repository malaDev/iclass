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
require ('functions_comments.php');

// title of the the comment
$title = $_GET["t"];
$title = htmlspecialchars(stripslashes($title));
$title = mysql_real_escape_string($title); 

// file added to the comment
$file = $_GET['f'];

// body of the comment
$comment = $_GET["b"];
$comment = htmlspecialchars(stripslashes($comment));
$comment = mysql_real_escape_string($comment);
$comment = str_replace('[enter]', '<br />', $comment);
$comment = make_links($comment);
$comment = embed_youtube($comment);

$uvanetid_commenter = $_GET["u"];

// folderid of the episode where the comment was posted
$folderid= $_GET["fid"];

$sql = "SELECT * FROM users WHERE uvanetid = '{$uvanetid_commenter}'";
$result4 = mysql_query($sql);
$user = mysql_fetch_array($result4);
$user_id = $user['id'];
// insert comment into database
$query_insertComment = "INSERT INTO comments (user_id, folder_id, title, file, body, latest_update) VALUES ('$user_id', '$folderid', '$title', '$file', '$comment', CURRENT_TIMESTAMP)";
$result_insertComment = mysql_query($query_insertComment);
if (!$result_insertComment){
	echo "<div class=error>Er ging iets fout met het plaatsen van de comment</div>";
}

// show all the comments of this episode including the new posted comment
require 'getComment.php';
?>