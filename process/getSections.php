<?php
if (DB_COURSE_FOLDERS != false && DB_COURSE_ITEMS != false) {
	$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
	$userid = mysql_fetch_array($useridresult);
	$id = $userid['id'];
	?>
	<br />Hier vind je een overzicht van alle onderdelen van <?php echo TITLE; ?><br />
	<?php
//selects all the main menu items (episodes).
	$resultSubject = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");

	while ($subject = mysql_fetch_array($resultSubject)) {
		$folderid = $subject['folder_id'];
		$weight = $subject['weight'];
		//if user is logged in, shows which episodes are cleared. Else shows episodes without progress.
		if (isset($uvanetid)) {
			$episodesResult = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $folderid);
			$done = true;
			while ($episode = mysql_fetch_array($episodesResult)) {
				$episode_folderid = $episode['folder_id'];
				$progressResult = mysql_query("SELECT * FROM progress WHERE id = $id AND folder_id = $episode_folderid");
				$numRowsDone = mysql_num_rows($progressResult);
				if ($numRowsDone != 1) {
					$done = false;
				}
			}
		}
		if (!isset($done)) {
			echo '<div onclick="location.href=\'index.php?p=overview&folder=' . $subject['folder_id'] . '\'" class="standout clickableStandout"><a href="index.php?p=overview&folder=' . $subject['folder_id'] . '">' . $subject['title'] . '</a>';
		} else if ($done == true) {
			echo '<div onclick="location.href=\'index.php?p=overview&folder=' . $subject['folder_id'] . '\'" class="success clickableSucces"><a href="index.php?p=overview&folder=' . $subject['folder_id'] . '">' . $subject['title'] . '</a>';
		} else {
			echo '<div onclick="location.href=\'index.php?p=overview&folder=' . $subject['folder_id'] . '\'" class="warning clickableWarning"><a href="index.php?p=overview&folder=' . $subject['folder_id'] . '">' . $subject['title'] . '</a>';
		}
		echo '</div>';
		foreach ($admin_users as $admin_user) {
			if ($uvanetid == $admin_user) {
				$resultSections = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
				unset($current);
				unset($next_id);
				unset($prev_id);
				while ($section = mysql_fetch_array($resultSections)) {
					if (isset($current)) {
						$next_id = $section['folder_id'];
						?><a class="button" onclick="swapSections('<?php echo $folderid; ?>', '<?php echo $next_id; ?>');">Move Down</a><?php
						break;
					}
					if ($section['folder_id'] == $folderid) {
						$current = true;
						if (isset($prev_id)) {
							?><a class="button" onclick="swapSections('<?php echo $folderid; ?>', '<?php echo $prev_id; ?>');">Move Up</a><?php
						}
					} else {
						$prev_id = $section['folder_id'];
					}
				}
				?>
				<a class="button" onclick="deleteSection('<?php echo $folderid; ?>');">Delete</a>


				<?php
				break;
			}
		}
	}
	if (isset($weight))
		$weight = $weight + 1;
	else
		$weight = 1;
	?>
	<br /><br /><br /><input class="inputfield" type="text" name="section" id="section" /><input type="submit" class="button" onclick="addSection(document.getElementById('section').value, '<?php echo $weight; ?>')" value="Nieuwe Sectie" />
	<?php
} else {
	echo '<div class="standout"><b>Er wordt op dit moment nog geen vak op deze site aangeboden.</b><br />Kom later terug.</div>';
}
?>
