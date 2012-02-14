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
require_once("../include/lookforavatar.php");

// Comment id
$id = $_GET["q"];
// uvanetid current user
$uvanetid = $_GET["u"];

// Select info which need to be shown when comment is expanded
$query = "SELECT body, user_id FROM comments WHERE id = $id";
$result = mysql_query($query);
$comment = mysql_fetch_array($result);
	$body = $comment['body'];
	$body = wordwrap($body,60,"\n",TRUE);
	
	// Get info of the poster (type and uvanetid)
	$user_id = $comment['user_id'];
	$query2 = "SELECT type, uvanetid FROM users WHERE id = $user_id";
	$result2 = mysql_query($query2);
	$poster = mysql_fetch_array($result2);
	$type_poster = $poster['type'];
	$uvanetid_poster = $poster['uvanetid'];
	if ($type_poster == "Docent"){
		$type_poster = "<b>".$type_poster."</b>";
	}

?>
<!-- Table to show expanded comment -->
<table width="600px" style="margin-bottom: -30px">
<thead>
	<tr>
		<td valign="top" width="400px" class="message"><?php echo $body; ?></td>
		<td width="200px" valign="top" style="text-align:right;">
			<?php progress($uvanetid_poster, true); ?><br />
			<?php echo $type_poster; ?><br /><img style="float:right" src='<?php echo lookforavatar($uvanetid_poster, "../"); ?>' width="100" height="100" alt="avatar" />
		</td>
	</tr>

	<tr>
		<td colspan="2" style="border-bottom:2px solid #b7ddf2"> </td>

	</tr>
</thead>
<tbody>
	<tr><td colspan="2"><img src="<?php echo BASE; ?>images/reply_arrow.png" alt="reply" /><b> Reacties:</b><br /><br /></td></tr>
	<tr><td colspan="2" width="100%" id="<?php echo '0' . $id; ?>">
			<?php
			/* Get all the replies for this comment */
			include 'getReply.php';
			?>
		</td></tr>
</tbody>
<tfoot>
	<tr>
		<!-- Post a new reply -->
		<td colspan="2">
			<label> Reply
				<span class="small">Reply op deze comment</span>
			</label>
			<textarea style="margin-bottom:-1px" cols="43" rows="3" id="body_reply<?php echo $id; ?>"></textarea>
			<button onclick="getReply(<?php echo $id; ?>, document.getElementById('body_reply<?php echo $id; ?>').value.replace(/\n/g,'[enter]'), <?php echo $uvanetid; ?>)" style="margin-top: 28px;" class="submit" name="submitReply">Reply</button>
			<br /><br />
		</td>
	</tr>
</tfoot>
</table>
<!-- Expand comment button -->
<a class="button" onclick="showHideComment('<?php echo $id; ?>');">Klap comment in</a>