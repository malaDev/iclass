<?php

include_once "lib/markdown/markdown.php";
if ($uvanetid != '') {
	$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
	$userid = mysql_fetch_array($useridresult);
	$id = $userid['id'];
	$foldersResult = mysql_query("SELECT * FROM progress WHERE id = $id AND folder_id = $folderid");
	$numRowsDone = mysql_num_rows($foldersResult);
	if ($numRowsDone >= 1) {
		$done = array('done', $id, $folderid);
	} else {
		$done = array('notdone', $id, $folderid);
	}
} else {
	$done = 'notdone';
}


$sql = "SELECT title, parent FROM " . DB_COURSE_FOLDERS . " WHERE folder_id=" . $folderid . " ORDER BY weight";
$result = mysql_query($sql) or die(mysql_error());
$currentEpisode = mysql_fetch_array($result);

$resultEpisodes = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $currentEpisode['parent']);
while ($episode = mysql_fetch_array($resultEpisodes)) {
	if (isset($current)) {
		$nextid = $episode['folder_id'];
		$nexttitle = $episode['title'];
		break;
	}
	if ($episode['folder_id'] == $folderid) {
		$current = true;
		if (isset($backidtmp)) {
			$backid = $backidtmp;
			$backtitle = $backtitletmp;
		} else {
			$backid = $folderid;
			$backtitle = "No previous episode..";
		}
		$curTitle = $currentEpisode['title'];
	} else {
		$backidtmp = $episode['folder_id'];
		$backtitletmp = $episode['title'];
	}
}
if (!isset($nextid)) {
	$nextid = $folderid;
	$nexttitle = "No next episode..";
}
$info = Markdown(otherinfo($folderid));
//$info_markdown = Markdown($info);
if (isset($uvanetid) && isAdmin($uvanetid))
	$edit = false;
items($folderid);

global $page_items;

// function to get and show all the tab menu items
function items($id) {
	global $title, $arraytmp;
	$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " 
	        ORDER BY (CASE title WHEN 'Videos' THEN 1 ELSE 0 END) DESC, weight ASC";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		$title = $row['title'];
		$arraytmp = NULL;
		contrfolders($row['folder_id']);
		$arraytmp = NULL;
		contrfiles($row['folder_id']);
	}
	$arraytmp = NULL;
	otherfiles($id);
}

function contrfolders($id) {
	global $title, $page_items, $arraytmp;
	$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		$arraytmp[$row['title']] = "SUBFOLDER";
		contrfiles($row['folder_id']);
		contrfolders($row['folder_id']);
	}
}

// function to get and show the content of the tab menu items
function contrfiles($id) {
	global $title, $page_items, $arraytmp;
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		$split = explode(".", $row['attr']);
		if ($split[0] == "http://cs50") {
			$split2 = explode("tv", $row['attr']);
			$video = "http://cdn.cs50.net" . $split2[1];
			$arraytmp[$row['innerhtml']] = $video;
		} else {
			$arraytmp[$row['innerhtml']] = $row['attr'];
		}
	}
	if (isset($arraytmp)) {
		$page_items[$title] = $arraytmp;
	}
}

// Other content which is not part of a tab menu item
function otherfiles($id) {
	global $title, $page_items, $arraytmp;
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			// in case of cs50 we needed to alter the url of the mp4 file to be able to play the video
			$split = explode(".", $row['attr']);
			if ($split[0] == "http://cs50") {
				$split2 = explode("tv", $row['attr']);
				$video = "http://cdn.cs50.net" . $split2[1];
				$arraytmp[$row['innerhtml']] = $video;
			} else {
				$arraytmp[$row['innerhtml']] = $row['attr'];
			}
		}
		$page_items["Other"] = $arraytmp;
	}
}

// general info about current episode
function otherinfo($id) {
	$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=2 ORDER BY weight";
	$result = mysql_query($sql) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$info = $row['innerhtml'];
		}
	} else {
		$info = 'No information available';
	}
	return $info;
}

?>
