<?php

class Comments
{
	public static function for_page($folderid)
	{
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
			$replies = replies_for_comment($id);
			$comments[$id] = array($body, $date, array($name_poster, $uvanetid_poster, $type_poster), $attachement, $delete, $replies);
		}
	}
	
	/* Get and show the replies linked to the comment_id */
	public static function replies_for_comment($id)
	{
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
		return $replies;
	}

}
