<?php

if (isset($request[0]) && $request[0] == 'create'){
	require('process/createDatabase.php');
die();
}

$query_course = mysql_query("SELECT course_id, course_name FROM courses ORDER BY update_date DESC");
if (mysql_num_rows($query_course) > 0) {
	$row_course = mysql_fetch_row($query_course);
	$slogan = $row_course[1];
	$course_folders = "course__" . $row_course[0] . "_folders";
	$course_items = "course__" . $row_course[0] . "_items";
} else {
	$slogan = "No course initialized yet..";
	$course_folders = false;
	$course_items = false;
}
// Derived constants
define("SLOGAN", $slogan);
define("DB_COURSE_FOLDERS", $course_folders);
define("DB_COURSE_ITEMS", $course_items);
$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = explode("?ticket", $url);
$url = $url[0];
$url = explode("index.php", $url);
$url = $url[0];

/* Define required tables */
$tables = mysql_query("SHOW TABLES FROM " . DB_NAME);
$required = Array('comments', 'courses', 'progress', 'replies', 'users');
while ($table = mysql_fetch_row($tables)) {
        $key = array_search($table[0], $required);
        if (is_numeric($key)) {
                unset($required[$key]);
        }
}

/* Table is missing? show install.php */
if (count($required) > 0) {
        $case = 'database_tables';
        require("lib/install.php");
        die();
}

// These are the pages and sections in our course
$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$episode_title = $row['title'];
	$resultsub = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $row['folder_id'] . " ORDER BY weight");
	while ($rowsub = mysql_fetch_array($resultsub, MYSQL_ASSOC)) {
		$subitemTitle = $rowsub['title'];
		$subitemFolder = $rowsub['folder_id'];
		$subarray[$subitemTitle] = 'page/' . $subitemFolder;
	}
	$page_links[$episode_title] = $subarray;
	unset($subarray);
}

?>
