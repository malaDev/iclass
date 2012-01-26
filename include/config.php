<?php
define("DB_SERVER", "localhost");
define("DB_USER", "webdb1233");
define("DB_PASS", "lovedoctors");
define("DB_NAME", "webdb1233");
mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());
$row = mysql_fetch_row(mysql_query("SELECT course_id FROM courses ORDER BY update_date DESC"));
define("DB_COURSE_FOLDERS", "course__".$row[0]."_folders");
define("DB_COURSE_ITEMS", "course__".$row[0]."_items");

define("TITLE", "LearnScape");
define("SLOGAN", "hier komt de slogan");

define("BASE", "/webdb1233/");
?>