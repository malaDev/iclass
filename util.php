<?php

// This is a filter that may be used in templates. It is used in the navbar
// to find the icon name given a descriptive string.
function icon_from_text($text) {
	switch ($text) {
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

function icon_tag_from_text($text) {
	if ($i = icon_from_text($text)) {
		return "<i class=\"$i\"></i>";
	} else {
		return $i;
	}
}

// To boot Twig from our router
function start_twig($folder) {
	// Hardcoded path
	require_once 'lib/Twig/lib/Twig/Autoloader.php';
	Twig_Autoloader::register();

	// Name of folder where html is found
	// always add 'views' folder as second path for base components
	$loader = new Twig_Loader_Filesystem(array($folder, 'views'));
	$twig = new Twig_Environment($loader, array(
				// Cache could be set to a folder name or false
				'cache' => false,
			));
	$twig->addFilter('icon', new Twig_Filter_Function('icon_from_text'));
	$twig->addFilter('icon_tag', new Twig_Filter_Function('icon_tag_from_text'));
	$twig->addFunction('url', new Twig_Function_Function('rebase_path'));

	return $twig;
}

// These are the pages and sections in our course
if (DB_COURSE_FOLDERS && DB_COURSE_ITEMS){
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
}