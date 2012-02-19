<?php
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
        $case = 'database_tables';
        require("pages/install1.php");
        die();
}

if (BASE != strstr($_SERVER['SCRIPT_NAME'], 'index', true)) {
        $case = 'base';
        require("pages/install1.php");
        die();
}

?>

