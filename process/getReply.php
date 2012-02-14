<?php
/* Get and show the replies linked to the comment_id */


require_once("../include/lookforavatar.php");
// Set time names to dutch
mysql_query("SET lc_time_names=nl_NL");

// Select the replies linked to the comment_id
$query_selectreplies = "SELECT *, DATE_FORMAT(timestamp, '%H:%i, %W %d %M %Y') AS timestamp FROM replies WHERE comment_id = $id order by id";
$result_selectreplies = mysql_query($query_selectreplies);
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
	if ($type_replier == "Docent") {
		$type_replier = "<b>" . $type_replier . "</b>";
	}
	$query_type_r = mysql_query("SELECT type FROM users WHERE uvanetid = '$uvanetid'");
	$type_self_r = mysql_fetch_array($query_type_r);

	// If type of the replier equals "Docent" or if reply is of urself, show delete link
	if ($uvanetid_replier == $uvanetid || $type_self_r['type'] == "Docent") {
		$del = "<a class='delete_reply' onclick='delReply($id, $id_reply, $uvanetid);'></a>";
	} else {
		$del = "";
	}
	?>
	<!-- Table to show the reply -->
	<table width="100%" >
		<tr>
			<td valign="top" width="400px" style="padding-left: 10px"><?php echo $body_reply; ?></td>
			<td style="text-align:right;" valign="top">
				<?php echo $del; ?><?php echo "<a href='" . BASE . "index.php?p=profiel&user=" . $uvanetid_replier . "'><b>" . $name_replier . "</b></a>"; ?><span class="small">[<?php echo $date_reply; ?>]</span>
				<span class="smallfont">Voortgang: </span><?php progress($uvanetid_replier, false); ?><br />
				<?php echo $type_replier; ?> &nbsp;&nbsp;<img style="float:right" src='<?php echo lookforavatar($uvanetid_replier, "../"); ?>' width="50" height="50" alt="avatar" /></td>
		</tr>
		<tr>
			<td colspan="2" style="border-bottom:2px solid #b7ddf2"></td>
		</tr>
	</table>
	<?php
}
?>