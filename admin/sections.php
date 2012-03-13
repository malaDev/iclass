<?php

switch ($request[2])
{
	// all four pages share common information so we group them early on
	// http://www.learnscape.nl/admin/*
	case "":
		$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$episode_title = $row['title'];
			$resultsub = mysql_query("SELECT parent, " . DB_COURSE_FOLDERS . ".folder_id, weight, title, (progress.id=" . 18 . ") as done FROM " . DB_COURSE_FOLDERS . " left outer join progress on " . DB_COURSE_FOLDERS . ".folder_id = progress.folder_id having  " . DB_COURSE_FOLDERS . ".parent=" . $row['folder_id'] . " ORDER BY weight");
			$subaarray = array();
			while ($rowsub = mysql_fetch_array($resultsub, MYSQL_ASSOC)) {
				$subitemTitle = $rowsub['title'];
				$subitemFolder = $rowsub['folder_id'];
				$subaarray[$subitemTitle] = array($subitemFolder, $rowsub['done']);
			}
			$page_done_links[$episode_title] = array($row['folder_id'], $subaarray);
			unset($subaarray);
		}

		echo $twig->render($request[1] . '.html', array(
			'page_name' => $request[1],
			'page_title' => TITLE,
			'page_links' => $page_links,
			'page_items' => $page_items,
			'logged_in' => $loggedIn,
			'progress' => percentage($uvanetid),
			//'url' => urlencode($url),
			'username' => $name,
			'type' => $user_type,
			'admin' => isAdmin($uvanetid),
			'email' => $email,
			'new' => $new,
			'uvanetid' => $uvanetid,
			'courses' => $courses,
			'message' => $message,
			'sections' => $page_done_links
		));
		break;
	case "add":
		$parent = $_POST['parent'];
		$name = $_POST['name'];
		$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=$parent ORDER BY weight desc limit 1;");
		$result = mysql_fetch_array($result);
		if($result)
			$weight = $result['weight'] + 1;
		else
			$weight = 1;
		error_log("$weight");
		mysql_query("INSERT INTO " . DB_COURSE_FOLDERS . "(weight, parent, title) VALUES ($weight, $parent, '$name')");
		$referer = $_SERVER['HTTP_REFERER'];
		header("Location: $referer");
		break;
	case "edit":
		$folder_id = $_POST['id'];
		$name = $_POST['name'];
		mysql_query("UPDATE " . DB_COURSE_FOLDERS . " SET title = '$name' WHERE folder_id = $folder_id;");
		$referer = $_SERVER['HTTP_REFERER'];
		header("Location: $referer");
		break;
	case "remove":
		$folder_id = $_POST['id'];
		mysql_query("DELETE FROM " . DB_COURSE_FOLDERS . " WHERE folder_id = $folder_id;");
		$referer = $_SERVER['HTTP_REFERER'];
		header("Location: $referer");
		break;
}
