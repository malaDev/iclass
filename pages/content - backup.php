<script type="text/javascript">	
	function slideVideo(id, way, folder, next, back)
	{
		var curVideo = document.getElementById(folder+"video"+id);
		if (way == "back")
			var sum = id - 1;
		else
			var sum = id + 1;
	
		var futVideo = document.getElementById(folder+"video"+sum);	
		if (futVideo){
			futVideo.style.display = "block";
			curVideo.style.display = "none";
		}else{
			if (way == "next" && next != "")
			window.location = ('index.php?p=content&folder='+next);
		if (way == "back" && back != "")
			window.location = ('index.php?p=content&folder='+back);
		}
	}
</script>
<?php
if (isset($_GET['folder'])) {
	$folderid = $_GET['folder'];
} else {
	$folderid = '';
}
?>

<div id="top">
	<?php
	$sql = "SELECT title, parent FROM " . DB_COURSE_FOLDERS . " WHERE folder_id=" . $folderid . " ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	$row = mysql_fetch_array($result);
	$parent = $row['parent'];
	echo '<h2>' . $row['title'] . '</h2>';
	echo '<br />';


	$result4 = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $parent);

	while ($row4 = mysql_fetch_array($result4)) {
		$sucess = $row4['done'];
		if (isset($current)) {
			$next = $row4['folder_id'];
			$current = null;
		}
		if ($row4['folder_id'] == $folderid) {
			$current = true;
			$backexist = true;
			if ($sucess)
				echo '<a style="color:green" href="index.php?p=content&folder=' . $row4['folder_id'] . '"><b><u>' . $row4['title'] . '</u></b></a> - ';
			else
				echo '<a style="color:black" href="index.php?p=content&folder=' . $row4['folder_id'] . '"><b><u>' . $row4['title'] . '</u></b></a> - ';
		} else {
			if (!isset($backexist))
				$back = $row4['folder_id'];
			if ($sucess)
				echo '<a style="color:green" href="index.php?p=content&folder=' . $row4['folder_id'] . '">' . $row4['title'] . '</a> - ';
			else
				echo '<a href="index.php?p=content&folder=' . $row4['folder_id'] . '">' . $row4['title'] . '</a> - ';
		}
	}
	echo "<br />";
	
	?>
	</br>

	<table>
		<?php
		if (!isset ($next))
			$next = "";
		if (!isset ($back))	
			$back = "";
		videofolders($folderid, $next, $back);
		?>
	</table>
</div>


<?php
items($folderid);
contritems($folderid);

function videofolders($id, $next, $back) {
	$sql = "SELECT folder_id FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " ORDER BY weight desc";
	$result = mysql_query($sql) or die(mysql_error());
	videofiles($id, $next, $back);
	while ($row = mysql_fetch_array($result)) {
		//videofiles($row['folder_id']);
		videofolders($row['folder_id'], $next, $back);
	}
}

function videofiles($id, $next, $back) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());
	$i = 0;
	while ($row = mysql_fetch_array($result)) {
		$video = $row['attr'];
		$naam = $row['innerhtml'];
		$folder = $row['folder'];
		$split = explode(".", $video);
		$laatste = count($split) - 1;
		$ext = $split[$laatste];
		if ($ext == "mp4") {
			if ($split[0] == "http://cs50") {
				$split2 = explode("tv", $video);
				$video = "http://cdn.cs50.net" . $split2[1];
			}
			if ($i == 0) {
				$display = "block";
			} else {
				$display = "none";
			}
			?>
			<tr id="<?php echo $folder; ?>video<?php echo $i; ?>" style="display:<?php echo $display; ?>">
				<td width=175><a id="previousbtn" onclick="slideVideo(<?php echo $i; ?>, 'back', '<?php echo $folder; ?>','', '<?php echo $back; ?>')" title="Previous"><span>Previous</span></a></td>
				<td>
					<?php echo "<b>" . $naam . "</b>"; ?>
					<video class="video-js vjs-default-skin" controls
						   preload="auto" width="640" height="364"	data-setup="{}">
						<source src="<?php echo $video; ?>" type='video/mp4'>
					</video>
				</td>
				<td width=175><a id="nextbtn" onclick="slideVideo(<?php echo $i; ?>, 'next', '<?php echo $folder; ?>', '<?php echo $next; ?>')" title="Next"><span>Next</span></a></td>
			</tr>
			<?php
			$i++;
		}
	}
}

function items($id) {
	echo '<ul id="tabs">';
	echo '<li><a href="#Info">Info</a></li>';
	$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		echo '<li><a href="#' . $row['title'] . '">' . $row['title'] . '</a></li>';
	}

	echo '<li><a href="#Other">Other</a></li>';
	echo '</ul>';
}

function contritems($id) {
	$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	echo '<div class="tabContent" id="Info">';
	echo '<div>';
	otherinfo($id);
	echo '</div>';
	echo '</div>';

	while ($row = mysql_fetch_array($result)) {
		echo '<div class="tabContent" id="' . $row['title'] . '">';
		echo '<div>';

		contrfolders($row['folder_id']);
		contrfiles($row['folder_id']);

		echo '</div>';
		echo '</div>';
	}

	echo '<div class="tabContent" id="Other">';
	echo '<div>';
	otherfiles($id);
	echo '</div>';
	echo '</div>';
}

function contrfolders($id) {
	$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		echo '<div class="inhoud">';
		echo '<p><h3>' . $row['title'] . '</h3></p>';

		contrfiles($row['folder_id']);
		contrfolders($row['folder_id']);

		echo '</div>';
	}
}

function contrfiles($id) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		echo '<p><a href="' . $row['attr'] . '" target="_blank">' . $row['innerhtml'] . '</a></p>';
	}
}

function otherfiles($id) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	if (mysql_fetch_array($result)) {
		$result2 = mysql_query($sql) or die(mysql_error());
		while ($row = mysql_fetch_array($result2)) {
			echo '<p><a href="' . $row['attr'] . '" target="_blank">' . $row['innerhtml'] . '</a></p>';
		}
	} else {
		echo '<p>Er zijn geen extra links beschikbaar</p>';
	}
}

function otherinfo($id) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=2 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);

	if (mysql_fetch_array($result)) {
		$result2 = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result2);

		while ($row = mysql_fetch_array($result2)) {
			echo '<p>' . $row['innerhtml'] . '</p>';
		}
	} else {
		echo '<p>Er is geen informatie beschikbaar</p>';
	}
}

echo '<br />';
echo '<br />';
include 'comments.php';
?>