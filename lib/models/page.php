<?php

class Page
{
	public static function done_by_user($folderid, $uvanetid)
	{
		if ($uvanetid != '')
		{
			$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
			$userid = mysql_fetch_array($useridresult);
			$id = $userid['id'];
			$foldersResult = mysql_query("SELECT * FROM progress WHERE id = $id AND folder_id = $folderid");
			$numRowsDone = mysql_num_rows($foldersResult);
			if ($numRowsDone >= 1) {
				$done = array('done', $id, $folderid);
			} else {
				$done = array('notdone', $id, $folderid);
			}
		}
		else
		{
			$done = 'notdone';
		}
		
		return $done;
	}
	
	public static function title($id)
	{
		$sql = "SELECT title FROM " . DB_COURSE_FOLDERS . " WHERE folder_id=" . $id;
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['title'];
	}

	// recursively get all subnav menus from the database
	public static function items($id, $main=true)
	{
		$sql = "SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $id . " 
		        ORDER BY (CASE title WHEN 'Videos' THEN 1 ELSE 0 END) DESC, weight ASC";
		$result = mysql_query($sql) or die(mysql_error());
		
		$items = array();
		if($main)
		{
			$other = Page::files($id);
			if(count($other) > 0) $items['Info'] = $other;
		}
		
		while ($item = mysql_fetch_array($result))
		{
			// top level menus
			if($main)
			{
				$items[$item['title']] = array_merge(Page::files($item['folder_id']), Page::items($item['folder_id'], false));
			}
			// subfolders if any
			else
			{
				$items[$item['title']] = "SUBFOLDER";
				$items = array_merge($items, Page::items($item['folder_id'], false));
				$items = array_merge($items, Page::files($item['folder_id']));
			}
		}

		return $items;
	}
	
	// get all subnav menu items for a specific submenu
	public static function files($id)
	{
		$sql = "SELECT * FROM " . DB_COURSE_ITEMS . " WHERE folder=" . $id . " AND type=1 ORDER BY weight";
		$result = mysql_query($sql) or die(mysql_error());
		
		$items = array();
	
		while ($item = mysql_fetch_array($result)) {
			$split = explode(".", $item['attr']);
			if ($split[0] == "http://cs50") {
				$split2 = explode("tv", $item['attr']);
				$video = "http://cdn.cs50.net" . $split2[1];
				$items[$item['innerhtml']] = $video;
			} else {
				$items[$item['innerhtml']] = $item['attr'];
			}
		}
	
		return $items;
	}
	
	public static function markdown($id)
	{
		$sql = "SELECT markdown FROM " . DB_COURSE_FOLDERS . " WHERE folder_id=" . $id;
		$result = mysql_query($sql) or die(mysql_error());
	
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				return $row['markdown'];
			}
		}
		return NULL;
	}
}
