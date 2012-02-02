<?php

/* Exports database folder structure to xml format using the XBEL dialect */

header ("content-type: text/xml");

require("../include/config.php");
require("../include/preprocess.php");

function row($str,$depth){
  print $depth.$str."\n";
}

function folderprint($depth,$parent){
  $result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent={$parent} ORDER BY weight");
  while ($row = mysql_fetch_array($result)) {
    row("<folder>", $depth);  
	itemprint($depth.'  ',$row['folder_id'],$row['title']);
	row("</folder>", $depth); 
  }
}

function itemprint($depth,$parent,$title){
  row("<title>".htmlspecialchars($title)."</title>", $depth);
  $result = mysql_query("SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder={$parent} ORDER BY weight");
  while ($row = mysql_fetch_array($result)) {
    switch($row['type']){
	  case 1:
		row('<bookmark href="'.$row['attr'].'">', $depth);  
		row('<title>'.htmlspecialchars($row['innerhtml']).'</title>', $depth.'  ');
		row("</bookmark>", $depth);
		break;
	  case 2:
		row('<desc>'.htmlspecialchars($row['innerhtml']).'</desc>', $depth);
		break;
	}
  }
  folderprint($depth.'  ',$parent);
}

print <<<PRE
<?xml version='1.0' encoding='UTF-8'?>

<!DOCTYPE xbel PUBLIC "+//IDN python.org//DTD XML Bookmark Exchange Language 1.0//EN//XML" "http://www.python.org/topics/xml/dtds/xbel-1.0.dtd">

<xbel version='1.0'>
PRE;
folderprint('  ',1);
print <<<POST
</xbel>
POST;


?>