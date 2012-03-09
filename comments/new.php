<?php

// No cache headers for AJAX
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require ('functions.php');

// file added to the comment
$file = $_POST['myfile'];

// body of the comment
$comment = $_POST["new-comment"];
$comment = htmlspecialchars(stripslashes($comment));
$comment = mysql_real_escape_string($comment);
$comment = make_links($comment);
$comment = embed_youtube($comment);

// folderid of the episode where the comment was posted
$folderid = $_POST["page_id"];

$sql = "SELECT * FROM users WHERE uvanetid = '$uvanetid'";
$result4 = mysql_query($sql);
$user = mysql_fetch_array($result4);
$user_id = $user['id'];

// insert comment into database
$query_insertComment = "INSERT INTO comments (user_id, folder_id, file, body, latest_update) VALUES ('$user_id', '$folderid', '$file', '$comment', CURRENT_TIMESTAMP)";
$result_insertComment = mysql_query($query_insertComment);
if (!$result_insertComment)
{
	echo "<div class='alert alert-error'>Er ging iets fout met het plaatsen van de comment</div>";
}

$url = explode('/course/ajax', $url);
// show all the comments of this episode including the new posted comment
//echo file_get_contents($url[0].'/comments/'.$folderid);

echo "huh";