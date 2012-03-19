<?php

class Folder
{
	public $id;
	public $name;
	public $pages;
	
	function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
	}
	
	function add_page($page)
	{
		$this->pages[] = $page;
	}
}

class FolderPage
{
	public $id;
	public $name;

	function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
	}
}

function course_load()
{
	// hardcoded course tables
	define("DB_COURSE_FOLDERS", "folders");
	define("DB_COURSE_ITEMS", "items");
}

function course_sections()
{
	// These are the pages and sections in our course
	$result = mysql_query("SELECT folders.folder_id, folders.weight as folder_weight, folders.title as folder_title, pages.folder_id as page_id, pages.weight as page_weight, pages.title as page_title FROM folders LEFT OUTER JOIN folders as pages ON folders.folder_id = pages.parent WHERE folders.parent = 1 ORDER BY folders.weight, pages.weight;");
	$current_folder = 0;
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		if($current_folder != $row['folder_id'])
		{
			$current_folder = $row['folder_id'];
			$page_links[$row['folder_id']] = new Folder($row['folder_id'], $row['folder_title']);
		}

		if($row['page_id'])
		{
			$page = new FolderPage($row['page_id'], $row['page_title']);
			$page_links[$row['folder_id']]->add_page($page);
		}
	}
	
	return $page_links;
}

course_load();
$site_links = course_sections();
