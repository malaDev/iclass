<?php
$result = mysql_query("SELECT DISTINCT tabcount FROM ".DB_COURSE." ORDER BY weight");

while($row = mysql_fetch_array($result))
	{
		$path = mysql_query("SELECT path, weight FROM ".DB_COURSE." WHERE tabcount=".$row['tabcount']);
		$path_array = mysql_fetch_array($path);
		$array_menu = explode("/", $path_array['path']);
		echo l(content, $array_menu[0], TRUE);
	}
?>
