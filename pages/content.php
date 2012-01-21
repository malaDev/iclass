<?php
$result = mysql_query("SELECT DISTINCT tabcount FROM course_CS50");

while($row = mysql_fetch_array($result))
  {
  $path = mysql_query("SELECT path FROM course_CS50 WHERE tabcount=".$row['tabcount']);
  $path_array = mysql_fetch_array($path);
	//echo $path_array['path'] . "<br>";
	
	$array_iets = explode("/", $path_array['path']);
	
		echo $array_iets[0] ."<br>";
  }
  
//	foreach($path_array as $awesome)
//	{
//	echo $awesome;
//	}
  //	$menu_array = explode("\\", $path_array['path']);
 //foreach($menu_array as $print)
 //{
//	echo $print;
 //}
 
 //echo $menu_array;

?>