<?php
/* Get and show the replies linked to the comment_id */


//require_once("../include/lookforavatar.php");
// Set time names to dutch
mysql_query("SET lc_time_names=nl_NL");

// Select the replies linked to the comment_id
$query_selectreplies = "SELECT *, DATE_FORMAT(timestamp, '%H:%i, %W %d %M %Y') AS timestamp FROM replies WHERE comment_id = $id order by id";
$result_selectreplies = mysql_query($query_selectreplies);
$replies = null;
while ($reply = mysql_fetch_array($result_selectreplies)) {
	$body_reply = $reply['body'];
	$body_reply = wordwrap($body_reply, 60, "\n", TRUE);
	
	$id_reply = $reply['id'];
	
	$date_reply = $reply['timestamp'];
	
	// Get info of the replier (name and type and uvanetid)
	$user_id_reply = $reply['user_id'];
	$query_selectreplier = "SELECT * FROM users WHERE id = $user_id_reply";
	$result_selectreplier = mysql_query($query_selectreplier);
	$replier = mysql_fetch_array($result_selectreplier);
	$name_replier = $replier['firstname'] . " " . $replier['lastname'];
	$uvanetid_replier = $replier['uvanetid'];
	$type_replier = $replier['type'];

	$query_type_r = mysql_query("SELECT type FROM users WHERE uvanetid = '$uvanetid'");
	$type_self_r = mysql_fetch_array($query_type_r);

	// If type of the replier equals "Docent" or if reply is of urself, show delete link
	if ($uvanetid_replier == $uvanetid || $type_self_r['type'] == "Docent") {
		$delete_reply = true;
	} else {
		$delete_reply = false;
	}
	$replies[$id_reply] = array($body_reply, $date_reply, array($name_replier, $uvanetid_replier, $type_replier), $delete_reply);
}
?>