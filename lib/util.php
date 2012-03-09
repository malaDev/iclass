<?php

function parse_request()
{
	// get path info relative to directory this router is in
	// "page/2", "admin/sections"
	$clean_path = $_SERVER['REQUEST_URI'];
	if(strpos($clean_path, "?") > 0) $clean_path = strstr($clean_path, "?", true);
	$clean_path = substr($clean_path, strlen(dirname($_SERVER['SCRIPT_NAME']))); 
	$clean_path = trim($clean_path, '/');
	return explode('/', $clean_path);
}

// This is a filter that may be used in templates. It is used in the navbar
// to find the icon name given a descriptive string.
function icon_from_text($text)
{
	switch ($text) {
		case 'Subtitles':
			return 'icon-font';
		case 'Transcript':
			return 'icon-th-list';
		case 'Specification':
			return 'icon-list-alt';
		case 'Video':
		case 'Videos':
			return 'icon-film';
		case 'Slides':
			return 'icon-picture';
		case 'Syllabus':
			return 'icon-align-justify';
		case 'Personal settings':
			return 'icon-user';
		case 'Edit sections':
			return 'icon-th-list';
		case 'Import course pack':
			return 'icon-download-alt';
		case 'Student progress':
			return 'icon-ok';
	}
}

function icon_tag_from_text($text)
{
	if ($i = icon_from_text($text)) {
		return "<i class=\"$i\"></i>";
	} else {
		return $i;
	}
}

// function load_model($name)
// {
// 	require_once('../lib/models/' . $name . '.php');
// }

function rebase_path($path)
{
	$base = trim(dirname($_SERVER['SCRIPT_NAME']),'/') . '/';
	return $base . $path;
}

// To boot Twig from our router
function start_twig($folder)
{
	// Hardcoded path
	require_once 'lib/Twig/lib/Twig/Autoloader.php';
	Twig_Autoloader::register();

	// Name of folder where html is found
	// always add 'views' folder as second path for base components
	$loader = new Twig_Loader_Filesystem(array($folder, 'lib/views'));
	$twig = new Twig_Environment($loader, array(
				// Cache could be set to a folder name or false
				'cache' => false,
			));
	$twig->addFilter('icon', new Twig_Filter_Function('icon_from_text'));
	$twig->addFilter('icon_tag', new Twig_Filter_Function('icon_tag_from_text'));
	$twig->addFunction('url', new Twig_Function_Function('rebase_path'));

	return $twig;
}

/* progress of user */
function percentage($uvanetid) {
	if ($uvanetid == '' || (!DB_COURSE_FOLDERS || !DB_COURSE_ITEMS))
		return 0;
	//progress bar, percentage -> pixels:(121/100)*(100-percent done) = pixels
	//total numbers of episodes:
	$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
	$episodeCount = 0;
	if (!$result)
		return 0;
	while ($row = mysql_fetch_array($result)) {
		$resultsub = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $row['folder_id']) or die(mysql_error());
		$numRows = mysql_num_rows($resultsub);
		$episodeCount = $episodeCount + $numRows;
	}
	if ($episodeCount > 0) {
		//total number of episodes done 
		$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
			if (mysql_num_rows($useridresult) == 0)
		return;
		$userid = mysql_fetch_array($useridresult);
		$resultuser = mysql_query("SELECT COUNT(id) FROM progress WHERE id =" . $userid['id']) or die(mysql_error());
		$resultuser2 = mysql_result($resultuser, 0);

		//percentage done
		$percentage = $resultuser2 / $episodeCount;
		$percentage = $percentage * 100;
	} else {
		$percentage = 0;
	}
	return round($percentage);
}

function isAdmin($uvanetid) {
	global $admin_users;
	foreach ($admin_users as $admin_user) {
//see if the logged in user is part of the admin group
		if ($uvanetid == $admin_user) {
			return true;
		}
	}
	return false;
}

function error_404()
{
	header("Status: 404 Not Found");
	echo "404";
}
