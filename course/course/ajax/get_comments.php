<?php
/* Get and show the replies linked to the comment_id */

// Set time names to dutch
mysql_query("SET lc_time_names=nl_NL");

// Select the comments linked to the folder_id (episode)
$query = "SELECT *, DATE_FORMAT(timestamp, '%H:%i, %W %d %M %Y') AS timestamp FROM comments WHERE folder_id = $folderid order by latest_update desc";
$result = mysql_query($query);
$comments = null;
while ($comment = mysql_fetch_array($result)) {
	$id = $comment['id'];
	
	$body = $comment['body'];
	$body = wordwrap($body,60,"\n",TRUE);
	
 	$date = $comment['timestamp'];
	
	// Get info of the poster of the comment (name and type)
	$user_id = $comment['user_id'];
	$query2 = "SELECT * FROM users WHERE id = $user_id";
	$result2 = mysql_query($query2);
	$poster = mysql_fetch_array($result2);
	$name_poster = $poster['firstname'] . " " . $poster['lastname'];
	$uvanetid_poster = $poster['uvanetid'];
	$type_poster = $poster['type'];

	$query_type = mysql_query("SELECT type FROM users WHERE uvanetid = '$uvanetid'");
	$type_self = mysql_fetch_array($query_type);

	// If type of the replier equals "Docent" or if reply is of urself, show delete link
	if ($uvanetid_poster == $uvanetid || $type_self['type'] == "Docent") {
		$delete = true;
	} else {
		$delete = false;
	}
	// Show link to file if file was added to the comment
	if ($comment['file'] != "") {
		$file = $comment['file'];
		$filetmp = explode(".", $file);
		$laatste = count($filetmp) - 1;
		$ext = "$filetmp[$laatste]";
		$fileOrgineel = null;
		for ($i = 0; $i < $laatste; $i++){
		$fileOrgineel .= $filetmp[$i];
		}
		$filetmp = explode("-", $fileOrgineel);
		$laatste = count($filetmp) - 1;
		$fileOrgineel = null;
		for ($i = 0; $i < $laatste; $i++){
		$fileOrgineel .= $filetmp[$i];
		}
		$fileOrgineel .= ".".$ext;
		$attachement = array($file, $fileOrgineel);
	} else {
		$attachement = null;
	}
	require 'get_replies.php';
	$comments[$id] = array($body, $date, array($name_poster, $uvanetid_poster, $type_poster), $attachement, $delete, $replies);
}
?>