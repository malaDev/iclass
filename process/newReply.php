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
require ("functions_comments.php");

// comment id
$id = $_GET["q"];

// reply body
$reply = $_GET["b"];
$reply = htmlspecialchars(stripslashes($reply));
$reply = mysql_real_escape_string($reply); 
$reply = str_replace('[enter]', '<br />', $reply);
$reply = make_links($reply);
$reply = embed_youtube($reply);

$uvanetid_newreplier = $_GET["u"];

$sql = "SELECT * FROM users WHERE uvanetid = '{$uvanetid_newreplier}'";
$result = mysql_query($sql);
$user = mysql_fetch_array($result);
$user_id = $user['id'];
// insert reply into database
$query_insertReply = "INSERT INTO replies (user_id, comment_id, body) VALUES ('$user_id', '$id', '$reply')";
$result_insertReply = mysql_query($query_insertReply);
if (!$result_insertReply){
	echo "<div class=error>Er ging iets fout met het plaatsen van de reply</div>";
}
// update the latest_update field of the comment where the reply was posted with the current_timestamp
$query_updateComment = "UPDATE comments SET latest_update = CURRENT_TIMESTAMP WHERE id = $id";
$result_updateComment = mysql_query($query_updateComment);
if (!$result_updateComment){
	echo "<div class=error>Er ging iets fout met het updaten van de comment</div>";
}

// show all the replies of this comment including the new posted reply
require 'getReply.php';
?>