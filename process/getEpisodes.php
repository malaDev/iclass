<?php
if (isset($_GET['folder'])) {
	$folderid = $_GET['folder'];
} else {
	$folderid = '';
}
//stores the id from the user currently logged in.
if (isset($uvanetid)) {
	$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
	$userid = mysql_fetch_array($useridresult);
	$id = $userid['id'];
}
//Selects all the main menu items (episodes).
$episodesResult = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $folderid);
if ($episodesResult) {
	while ($episode = mysql_fetch_array($episodesResult)) {
		$episode_folderid = $episode['folder_id'];
		//if user is logged in, shows which episodes are cleared. Else shows episodes without progress.
		if (isset($uvanetid)) {
			$progressResult = mysql_query("SELECT * FROM progress WHERE id = $id AND folder_id = $episode_folderid");
			$numRowsDone = mysql_num_rows($progressResult);
			// logged in and episode done: show success div
			if ($numRowsDone == 1) {
				echo '<div onclick="location.href=\'' . BASE . 'index.php?p=content&folder=' . $episode['folder_id'] . '\'" class="success clickableSucces"><a href="' . BASE . 'index.php?p=content&folder=' . $episode['folder_id'] . '">' . $episode['title'] . '</a></div>';
				// logged in and episode not done: show warning div		
			} else {
				echo '<div onclick="location.href=\'' . BASE . 'index.php?p=content&folder=' . $episode['folder_id'] . '\'" class="warning clickableWarning"><a href="' . BASE . 'index.php?p=content&folder=' . $episode['folder_id'] . '">' . $episode['title'] . '</a></div>';
			}
			// not logged in: show default blue div
		} else {
			echo '<div onclick="location.href=\'' . BASE . 'index.php?p=content&folder=' . $episode['folder_id'] . '\'" class="standout clickableStandout"><a href="' . BASE . 'index.php?p=content&folder=' . $episode['folder_id'] . '">' . $episode['title'] . '</a></div>';
		}
			foreach ($admin_users as $admin_user) {
			if ($uvanetid == $admin_user) {
				$episodesResult2 = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $folderid);
				unset($current);
				unset($next_id);
				unset($prev_id);
				while ($episodes = mysql_fetch_array($episodesResult2)) {
					if (isset($current)) {
						$next_id = $episode['folder_id'];
						?><a class="button" onclick="swapEpisodes('<?php echo $episode_folderid; ?>', '<?php echo $next_id; ?>');">Move Down</a><?php
						break;
					}
					if ($episode['folder_id'] == $folderid) {
						$current = true;
						if (isset($prev_id)) {
							?><a class="button" onclick="swapEpisodes('<?php echo $episode_folderid; ?>', '<?php echo $prev_id; ?>');">Move Up</a><?php
						}
					} else {
						$prev_id = $episode['folder_id'];
					}
				}
				?>
				<a class="button" onclick="deleteEpisode('<?php echo $folderid; ?>', '<?php echo $episode_folderid; ?>');">Delete</a>
				<?php
				break;
			}
		}
	}
} else {
	?>
	<div class="error">
		<p>Overzicht niet gevonden!</p>
	</div>
<?php } ?>