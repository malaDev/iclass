<?php
if (isset($_GET['folder'])) {
	$folderid = $_GET['folder'];
} else {
	$folderid = '';
}
?>

<div id="top">
<?php
	$sql = "SELECT title FROM " . DB_COURSE_FOLDERS . " WHERE folder_id=" . $folderid . " ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	$row = mysql_fetch_array($result);
	echo '<h2>' . $row['title'] . '</h2>';
	echo '<br />';
	echo '</div>';

items($folderid);
contritems($folderid);

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
		videofiles($row['attr']);
	}
}

function otherfiles($id) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	if (mysql_fetch_array($result)) {
		$result2 = mysql_query($sql) or die(mysql_error());
		while ($row = mysql_fetch_array($result2)) {
			echo '<p><a href="' . $row['attr'] . '" target="_blank">' . $row['innerhtml'] . '</a></p>';
			videofiles($row['attr']);
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


function videofiles($video) {
	$split = explode(".", $video);
	$laatste = count($split) - 1;
	$ext = $split[$laatste];
	if ($ext == "mp4") {
		if ($split[0] == "http://cs50") {
			$split2 = explode("tv", $video);
			$video = "http://cdn.cs50.net" . $split2[1];
		}
		?>
			<br />
			<video class="video-js vjs-default-skin" controls
				preload="auto" width="640" height="364"	data-setup="{}">
				<source src="<?php echo $video; ?>" type='video/mp4'>
			</video>
			<br />
		<?php
	}
}

echo '<br />';
echo '<br />';
include 'comments.php';
?>