<?php

switch ($request[2])
{
	case "":
		//print_r($page_items) and die();
		render('admin', 'sections.html', array(
			'page_name' => $request[1],
			'page_items' => $page_items
			//'sections' => $page_done_links
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
