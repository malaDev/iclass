<?php

class Comments
{
	public static function for_page($folderid, $uvanetid)
	{
		// Select the comments linked to the folder_id (episode)
		if(isAdmin($uvanetid))
			$result = mysql_query("SELECT *, comments.id as comment_id FROM comments INNER JOIN users ON comments.user_id = users.id WHERE folder_id = $folderid ORDER BY latest_update desc");
		else
			$result = mysql_query("SELECT *, comments.id as comment_id FROM comments INNER JOIN users ON comments.user_id = users.id WHERE folder_id = $folderid AND (public = 1 OR uvanetid = '$uvanetid') ORDER BY latest_update desc");

		$comments = array();
		while ($comment = mysql_fetch_array($result))
		{
			$id = $comment['comment_id'];
	
			$body = $comment['body'];
			$body = wordwrap($body,60,"\n",TRUE);
		 	$date = strtotime($comment['timestamp']);
			$public = $comment['public'];

			$name_poster = $comment['firstname'] . " " . $comment['lastname'];
			$uvanetid_poster = $comment['uvanetid'];
			$type_poster = $comment['type'];
			
			$query_type = mysql_query("SELECT type FROM users WHERE uvanetid = '$uvanetid'");
			$type_self = mysql_fetch_array($query_type);
	
			// If type of the replier equals "Docent" or if reply is of urself, show delete link
			if ($uvanetid_poster == $uvanetid || $comment['type'] == "Docent")
				$delete = true;
			else
				$delete = false;
			
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
			}
			else
			{
				$attachement = null;
			}
			
			$replies = Comments::replies_for_comment($id, $uvanetid);
			$comments[$id] = array($body, $date, array($name_poster, $uvanetid_poster, $type_poster), $attachement, $delete, $replies, $public);
		}
		
		return $comments;
	}
	
	/* Get and show the replies linked to the comment_id */
	public static function replies_for_comment($id, $uvanetid)
	{
		$query_selectreplies = "SELECT * FROM replies WHERE comment_id = $id order by id";
		$result_selectreplies = mysql_query($query_selectreplies);
		$replies = null;
		while ($reply = mysql_fetch_array($result_selectreplies)) {
			$body_reply = $reply['body'];
			$body_reply = wordwrap($body_reply, 60, "\n", TRUE);
			$id_reply = $reply['id'];
		 	$date_reply = strtotime($reply['timestamp']);
	
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
			if ($uvanetid_replier == $uvanetid || $type_self_r['type'] == "Docent")
				$delete_reply = true;
			else
				$delete_reply = false;
			$replies[$id_reply] = array($body_reply, $date_reply, array($name_replier, $uvanetid_replier, $type_replier), $delete_reply);
		}
		return $replies;
	}

}
