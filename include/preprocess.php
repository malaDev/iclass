<?php
/* Connect with database. Can't connect? show install1.php */
if (!@mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
	require("pages/install1.php");
	die();
}

/* Define required tables */
$tables = mysql_query("SHOW TABLES FROM " . DB_NAME);
$required = Array('comments', 'courses', 'progress', 'replies', 'users');
while ($table = mysql_fetch_row($tables)) {
	$key = array_search($table[0], $required);
	if (is_numeric($key)) {
		unset($required[$key]);
	}
}

/* Table is missing? show install2.php */
if (count($required) > 0) {
	require("pages/install2.php");
	die();
}

/* Select database */
mysql_select_db(DB_NAME);
$row = mysql_fetch_row(mysql_query("SELECT course_id, course_name FROM courses ORDER BY update_date DESC"));
// Derived constants
define("SLOGAN", $row[1]);
define("DB_COURSE_FOLDERS", "course__" . $row[0] . "_folders");
define("DB_COURSE_ITEMS", "course__" . $row[0] . "_items");
?>