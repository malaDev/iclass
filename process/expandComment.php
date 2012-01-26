<?php

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require("../include/config.php");
require("../include/functions.php");
$id = $_GET["q"];
$uvanetid = $_GET["u"];

$query = "SELECT body, user_id FROM comments WHERE id = $id";
$result = mysql_query($query);
$comment = mysql_fetch_array($result);
	$body = $comment['body'];
	$user_id = $comment['user_id'];
	$query2 = "SELECT type FROM users WHERE id = $user_id";
	$result2 = mysql_query($query2);
	$poster = mysql_fetch_array($result2);
	$type_poster = $poster['type'];
	if ($type_poster == "Docent"){
		$type_poster = "<b>".$type_poster."</b>";
	}
?>
<table width="600px" style="margin-bottom: -30px">
<thead>
	<tr>
		<td valign="top" width="400px" class="message"><?php echo $body; ?></td>
		<td width="200px" valign="top" style="text-align:right;"><?php echo $type_poster; ?><br /><img style="float:right" src="images/no-avatar.gif" width="100" heigth="100" alt="avatar" /></td>
	</tr>

	<tr>
		<td colspan="2" style="border-bottom:2px solid #b7ddf2"> </td>

	</tr>
</thead>
<tbody>
	<tr><td colspan="2"><img src="images/reply_arrow.png" alt="reply" /><b> Reacties:</b><br /><br /></td></tr>
	<tr><td colspan="2" width="100%" id="<?php echo '0' . $id; ?>">
			<?php
			include 'getReply.php';
			?>
		</td></tr>
</tbody>
<tfoot>
	<tr>
		<td colspan="2">
			<label> Reply
				<span class="small">Reply op deze comment</span>
			</label>
			<textarea style="margin-bottom:-1px" cols="43" rows="3" id="body_reply<?php echo $id; ?>"></textarea>
			<button onclick="getReply(<?php echo $id; ?>, document.getElementById('body_reply<?php echo $id; ?>').value.replace(/\n/g,'<br />'), <?php echo $uvanetid; ?>)" style="margin-top: 28px;" class="submit" name="submitReply">Reply</button>
			<label>Bijlage
				<span class="small">Voeg bestand aan comment toe</span>
			</label>
			<span  id="f1_upload_process<?php echo $id; ?>" style="display: none; margin-top:-20px">&nbsp; Laden...<br/><img src="images/loader.gif" /><br/></span>
			<span id="f1_upload_form<?php echo $id; ?>"><br/>
				<input class="input" name="myfile" id="upload<?php echo $id; ?>" type="file" />
			</span>                     
			<iframe id="upload_target" name="upload_target" src="#" style="display:none"></iframe>
			<br /><br />
		</td>
	</tr>
</tfoot>
</table>
<a class="button" onclick="showHideComment('<?php echo $id; ?>');">Klap comment in</a>