<?php
/* Get and show the replies linked to the comment_id */

// Set time names to dutch
mysql_query("SET lc_time_names=nl_NL");

// Select the comments linked to the folder_id (episode)
$query = "SELECT id, title, user_id, file, DATE_FORMAT(timestamp, '%H:%i, %W %d %M %Y') AS timestamp FROM comments WHERE folder_id = $folderid order by latest_update desc";
$result = mysql_query($query);
while ($comment = mysql_fetch_array($result)) {
	$id = $comment['id'];
	
	$title = $comment['title'];
	$title = wordwrap($title,40,"<br />\n",TRUE);
	
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
		$del = "<a class='delete_comment' onclick='delComment($id, $uvanetid, $folderid);'></a>";
	} else {
		$del = "";
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
		$attachement = "<span class='small'>Bijlage: <a href='".BASE."uploads/$file' target='_blank'>$fileOrgineel</a></span>";
	} else {
		$attachement = null;
	}
	?>
	<!-- Div to show the comment -->
	<div class="comments">
		<table width="600px">
			<tr>
				<td class="title" width="400px"><?php echo $title; ?> </td>
				<td width="200px" style="text-align:right"><?php echo $del; ?>Auteur: <?php echo "<a href='" . BASE . "index.php?p=profiel&user=" . $uvanetid_poster . "'><b>" . $name_poster . "</b></a>"; ?><span class="small">[<?php echo $date; ?>]</span>
					<?php echo $attachement; ?></td>
			</tr>
		</table>
		<!-- if u expand comment for first time, content of comment is get with AJAX -->
		<div id="<?php echo $id; ?>" style="display:block">
		<a class="button" onclick="expandComment('<?php echo $id; ?>', '<?php echo $uvanetid; ?>');">Klap comment uit</a>
		</div>
		<!-- Already expanded comment before? use javascript to show/hide comment -->
		<div id="expand<?php echo $id; ?>" style="display:none">
		<a class="button" onclick="showHideComment('<?php echo $id; ?>');">Klap comment uit</a>
		</div>

	</div>
	<br />
	<?php
}
?>