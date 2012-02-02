<!-- This page contains the content of the course (video section) -->
<script type="text/javascript">	
	// find position of an object (video's in our case)(used for scrolling)
	function findPos(obj) {
		var curtop = 0;
		if (obj.offsetParent) {
			do {
				curtop += obj.offsetTop;
			} while (obj = obj.offsetParent);
			return [curtop];
		}
	}

	// function to navigate through the video's
	function slideVideo(id, way, folder)
	{
		var curVideo = document.getElementById(folder+"container"+id);
		var curVideoDiv = document.getElementById(folder+"video"+id);
		if (way == "back")
			var sum = id - 1;
		else
			var sum = id + 1;
	
		var futVideo = document.getElementById(folder+"container"+sum);
		var futVideoDiv = document.getElementById(folder+"video"+sum);
		
		// Hide and pause current video, show and play future video
		if (futVideoDiv){
			futVideoDiv.style.display = "block";
			curVideoDiv.style.display = "none";
			jwplayer(curVideo).pause(true);
			jwplayer(futVideo).play(true);			
			//scroll to 150 pixels above future video
			window.scroll(0,findPos(futVideoDiv) - 150);
		}
	}
	
	//Done/not Done checkbox (AJAX)
	function progress(id, folderid, done)
	{
		var xmlhttp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("checkbox").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/progress.php?id="+id+"&fid="+folderid+"&done="+done,true);
		xmlhttp.send();
	}
</script>

<?php
if (isset($_GET['folder'])) {
	$folderid = $_GET['folder'];
	$folderid = mysql_real_escape_string($folderid);
	if (is_numeric($folderid)) {
		$numericFolder = true;
	}
} else {
	$folderid = '';
}
?>

<div id="top">
	<?php
	// if folder ID is numeric (prevents sql injection):
	if (isset($numericFolder)) {
		// logged in? show done/not done checkbox for progress
		if (isset($uvanetid)) {
			$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = $uvanetid");
			$userid = mysql_fetch_array($useridresult);
			$id = $userid['id'];
			$foldersResult = mysql_query("SELECT * FROM progress WHERE id = $id AND folder_id = $folderid");
			$numRowsDone = mysql_num_rows($foldersResult);
			if ($numRowsDone == 1) {
				$checkbox = "<input type='checkbox' checked='yes' name='done' onclick=\"progress('$id', '$folderid', 'notdone')\" />";
			} else {
				$checkbox = "<input type='checkbox' name='done' onclick=\"progress('$id', '$folderid', 'done')\" />";
			}
			?>
			<div id="checkbox">Gedaan:  <?php echo $checkbox; ?></div>
			<?php
		}
		?>
		<!-- show current episode title and previous and next episode links -->
		<table><tr><td width="25%">
					<?php
					$sql = "SELECT title, parent FROM " . DB_COURSE_FOLDERS . " WHERE folder_id=" . $folderid . " ORDER BY weight";
					$result = mysql_query($sql) or die(mysql_error());
					$currentEpisode = mysql_fetch_array($result);

					$resultEpisodes = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $currentEpisode['parent']);
					while ($episode = mysql_fetch_array($resultEpisodes)) {
						if (isset($current)) {
							$nextid = $episode['folder_id'];
							$nexttitle = $episode['title'];
							echo '<a href="' . BASE . 'content/' . $nextid . '/">' . $nexttitle . '</a>';
							break;
						}
						if ($episode['folder_id'] == $folderid) {
							$current = true;
							if (isset($backid))
								echo '<a href="' . BASE . 'content/' . $backid . '/">' . $backtitle . '</a>&nbsp;&nbsp;&nbsp;&nbsp;';
							echo '</td><td>';
							echo '<h2>' . $currentEpisode['title'] . '</h2>';
							echo '</td><td width="25%">';
						} else {
							$backid = $episode['folder_id'];
							$backtitle = $episode['title'];
						}
					}
					?>
				</td></tr></table></div>
	<?php
	// show general info of the current episode
	echo '<div class=info><h3 class=black>Info</h3>';
	otherinfo($folderid);
	echo '</div>';
	items($folderid);
	contritems($folderid);
} else {
	echo "<div class=error>Folder niet gevonden!</div>";
}

// function to get and show all the tab menu items
function items($id) {
	echo '<ul id="tabs">';
	$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " 
	        ORDER BY (CASE title WHEN 'Videos' THEN 1 ELSE 0 END) DESC, weight ASC";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		echo '<li><a href="#' . $row['title'] . '">' . $row['title'] . '</a></li>';
	}

	echo '<li><a href="#Other">Other</a></li>';
	echo '</ul>';
}

// function to get and show the content of the tab menu items
function contritems($id) {
	global $first, $i;
	$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());
	// div which hides the content while javascript is loading (otherwise all the content is displayed for a few seconds)
	echo '<div id="hideWhileLoading">';
	while ($row = mysql_fetch_array($result)) {
		$i = 0;
		echo '<div class="tabContent" id="' . $row['title'] . '">';
		echo '<div>';
		contrfolders($row['folder_id']);
		contrfiles($row['folder_id']);
		$first = false;
		echo '</div>';
		echo '</div>';
	}

	// Other content which is not part of a tab menu item
	echo '<div class="tabContent" id="Other">';
	echo '<div>';
	otherfiles($id);
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

// function to get and show the titles of the content of the tab menu items
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

// function to get and show the content of the tab menu items
function contrfiles($id) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		echo '<p> &nbsp; <a href="' . $row['attr'] . '" target="_blank">' . $row['innerhtml'] . '</a></p>';
		videofiles($row['attr'], $row['folder'], $row['innerhtml']);
	}
}

// Other content which is not part of a tab menu item
function otherfiles($id) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			echo '<p> &nbsp; <a href="' . $row['attr'] . '" target="_blank">' . $row['innerhtml'] . '</a></p>';
			videofiles($row['attr'], $row['folder'], $row['innerhtml']);
		}
	} else {
		echo '<p>Er zijn geen extra links beschikbaar</p>';
	}
}

// general info about current episode
function otherinfo($id) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=2 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			echo '<p>' . $row['innerhtml'] . '</p>';
		}
	} else {
		echo '<p>Er is geen informatie beschikbaar</p>';
	}
}

// Automatic find mp4 files and play them with JW-player
function videofiles($video, $folderID, $title) {
	global $first, $i;
	$split = explode(".", $video);
	$laatste = count($split) - 1;
	$ext = $split[$laatste];
	if ($ext == "mp4") {
		// only show first video
		if ($first != true) {
			$display = "block";
			$first = true;
		} else {
			$display = "none";
		}
		// in case of cs50 we needed to alter the url of the mp4 file to be able to play the video
		if ($split[0] == "http://cs50") {
			$split2 = explode("tv", $video);
			$video = "http://cdn.cs50.net" . $split2[1];
		}
		?>
		<!-- show video with JW-player in case of mp4 file -->
		<div id="<?php echo $folderID; ?>video<?php echo $i; ?>" style="display: <?php echo $display; ?>">
			<center><h2><?php echo $title; ?></h2></center>
			<table width="100%"><tr>
					<!-- previous video button -->
					<td width="150px"><a id="previousbtn" onclick="slideVideo(<?php echo $i; ?>, 'back', <?php echo $folderID; ?>)" title="Previous"><span>Previous</span></a></td>
					<td>

						<div id="<?php echo $folderID; ?>container<?php echo $i; ?>">Loading the player ...</div>
						<script type="text/javascript">
							// jwplayer configuration
							jwplayer("<?php echo $folderID; ?>container<?php echo $i; ?>").setup({ 
								file: "<?php echo $video; ?>", 
								height: 364, 
								width: 640,
								//first html5, then fallback to flash, then fallback to a download link
								modes: [     
									{ type: "html5" },
									{ type: "flash", src: "<?php echo BASE; ?>js/player.swf" },
									{ type: "download" }   
								]
							}); 
						</script>
					</td><td width="150px">
						<!-- next video button -->
						<a id="nextbtn" onclick="slideVideo(<?php echo $i; ?>, 'next', <?php echo $folderID; ?>)" title="Next"><span>Next</span></a>
					</td></tr></table>
		</div>
		<?php
		// the variable $i is used to make an unique id for every video (for navigation through videos)
		$i++;
	}
}

echo '<br /><br />';
if (isset($numericFolder)) {
	//logged in? show comment section
	if (isset($uvanetid)) {
		include 'comments.php';
	} else {
		echo "<div class='warning'><b>Niet ingelogd</b></div>
		  <div class='info'>Om de <b>comment sectie</b> te zien met vragen/opmerkingen, antwoorden en materiaal van andere studenten/docenten of om zelf berichten en materiaal te plaatsten moet je ingelogd zijn.</div>
		  <div class='info'>Om je <b>voortgang</b> bij te houden moet je ook ingelogd zijn.<br /><br />Log rechtsboven in met je UvAnetID.</div>";
	}
}
?>
<!-- Javascript function from js/tabs.js is called for the tabs menu -->
<script type="text/javascript">init_tabs();</script>