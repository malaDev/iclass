<h2>Welkom
	<?php
	//checks if user is currently logged in.
	//stores the id from the user if logged in.
	if (isset($uvanetid)) {
		$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = $uvanetid");
		$userid = mysql_fetch_array($useridresult);
		$id = $userid['id'];
		echo "<a href='" . BASE . "profiel&user=" . $uvanetid . "'><b>" . $name . "</b></a></h2>";
	} else {
	//page shown if not logged in.
		?>
		Gast</h2>
	<div class='warning'><b>Niet ingelogd</b></div>
	<div class='info'>Om je <b>voortgang</b> bij te houden moet je ingelogd zijn.<br /><br />Log rechtsboven in met je UvAnetID.</div>

	<div class='info'>Om de <b>comment sectie</b> te zien met vragen/opmerkingen, antwoorden en materiaal van andere studenten/docenten of om zelf berichten en materiaal te plaatsten moet je ook ingelogd zijn.</div>
	<?php
}
?>

<br />Hier vind je een overzicht van alle onderdelen van <?php echo TITLE; ?><br />
<?php
//selects all the main menu items (episodes).
$resultSubject = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");

while ($subject = mysql_fetch_array($resultSubject)) {
	$folderid = $subject['folder_id'];
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
		echo '<div onclick="location.href=\'overview/' . $subject['folder_id'] . '/\'" class="standout clickableStandout"><a href="index.php?p=overview&folder=' . $subject['folder_id'] . '">' . $subject['title'] . '</a></div>';
	} else if ($done == true) {
		echo '<div onclick="location.href=\'overview/' . $subject['folder_id'] . '/\'" class="success clickableSucces"><a href="index.php?p=overview&folder=' . $subject['folder_id'] . '">' . $subject['title'] . '</a></div>';
	} else {
		echo '<div onclick="location.href=\'overview/' . $subject['folder_id'] . '/\'" class="warning clickableWarning"><a href="index.php?p=overview&folder=' . $subject['folder_id'] . '">' . $subject['title'] . '</a></div>';
	}
}
?>