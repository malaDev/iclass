<?php

// Derived constants

function course_load()
{
	// $query_course = mysql_query("SELECT course_id, course_name FROM courses ORDER BY update_date DESC");
	// if (mysql_num_rows($query_course) > 0) {
	// 	$row_course = mysql_fetch_row($query_course);
	// 	$course_folders = "course__" . $row_course[0] . "_folders";
	// 	$course_items = "course__" . $row_course[0] . "_items";
	// } else {
	// 	$course_folders = false;
	// 	$course_items = false;
	// }
	// define("DB_COURSE_FOLDERS", $course_folders);
	// define("DB_COURSE_ITEMS", $course_items);
	
	// hardcoded course tables
	define("DB_COURSE_FOLDERS", "folders");
	define("DB_COURSE_ITEMS", "items");
}

/*
$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = explode("?ticket", $url);
$url = $url[0];
$url = explode("index.php", $url);
$url = $url[0];
*/

function course_sections()
{
	// These are the pages and sections in our course
	$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
	$page_links = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$episode_title = $row['title'];
		$resultsub = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $row['folder_id'] . " ORDER BY weight");
		$subarray = array();
		while ($rowsub = mysql_fetch_array($resultsub, MYSQL_ASSOC)) {
			$subitemTitle = $rowsub['title'];
			$subitemFolder = $rowsub['folder_id'];
			$subarray[$subitemTitle] = 'page/' . $subitemFolder;
		}
		$page_links[$episode_title] = $subarray;
	}
	return $page_links;
}

course_load();
$site_links = course_sections();
