<?php
mysql_query("SET lc_time_names=nl_NL");
$query6 = "SELECT *, DATE_FORMAT(timestamp, '%H:%i, %W %d %M %Y') AS timestamp FROM replies WHERE comment_id = $id order by id";

$result6 = mysql_query($query6);
while ($reply = mysql_fetch_array($result6)) {
	$body_reply = $reply['body'];
	$id_reply = $reply['id'];
	$date_reply = $reply['timestamp'];
	$user_id_reply = $reply['user_id'];
	$query5 = "SELECT * FROM users WHERE id = $user_id_reply";
	$result5 = mysql_query($query5);
	$replier = mysql_fetch_array($result5);
	$name_replier = $replier['firstname'] . " " . $replier['lastname'];
	$uvanetid_replier = $replier['uvanetid'];
	$type_replier = $replier['type'];
	if ($type_replier == "Docent"){
		$type_replier = "<b>".$type_replier."</b>";
	}
	
	$query_type_r = mysql_query("SELECT type FROM users WHERE uvanetid = $uvanetid");
	$type_self_r = mysql_fetch_array($query_type_r);
	
	if ($uvanetid_replier == $uvanetid || $type_self_r['type'] == "Docent"){
		$del = "<a class='delete_reply' onclick='delReply($id, $id_reply, $uvanetid);'></a>";
	} else {
		$del = "";
	}
	?>
	<table width="100%" >
		<tr>
			<td valign="top" width="400px" style="padding-left: 10px"><?php echo $body_reply; ?></td>
			<td style="text-align:right;" valign="top">
				<?php echo $del; ?><?php echo "<a href='" . BASE . "profiel&user=" . $uvanetid_replier . "'><b>" . $name_replier . "</b></a>"; ?><span class="small">[<?php echo $date_reply; ?>]</span>
				<?php echo $type_replier; ?> &nbsp;&nbsp;<img style="float:right" src="images/no-avatar.gif" width="50" height="50" alt="avatar" /></td>
		</tr>
		<tr>
			<td colspan="2" style="border-bottom:2px solid #b7ddf2"></td>
		</tr>
	</table>
	<?php
}
?>