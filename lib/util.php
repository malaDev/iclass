<?php

// from http://www.zachstronaut.com/posts/2009/01/20/php-relative-date-time-string.html
function time_elapsed_string($ptime) {
    $etime = time() - $ptime;
    
    if ($etime < 1) {
        return 'just now';
    }
    
    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                 7 * 24 * 60 * 60       =>  'week',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );
    
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}

// find urls and make them clickable links
function make_links($text) {
	return preg_replace(
					array(
				'/(?(?=<a[^>]*>.+<\/a>)
             (?:<a[^>]*>.+<\/a>)
             |
             ([^="\']?)((?:https?|ftp|bf2|):\/\/[^<> \n\r]+)
         )/iex',
				'/<a([^>]*)target="?[^"\']+"?/i',
				'/<a([^>]+)>/i',
				'/(^|\s)(www.[^<> \n\r]+)/iex',
				'/(([_A-Za-z0-9-]+)(\\.[_A-Za-z0-9-]+)*@([A-Za-z0-9-]+)
       (\\.[A-Za-z0-9-]+)*)/iex'
					), array(
				"stripslashes((strlen('\\2')>0?'\\1<a href = \"\\2\" >\\2</a>\\3':'\\0'))",
				'<a\\1',
				'<a\\1 target="_blank" >',
				"stripslashes((strlen('\\2')>0?'\\1<a href = \"http://\\2\" >\\2</a>\\3':'\\0'))",
				"stripslashes((strlen('\\2')>0?'<a href = \"mailto:\\0\" >\\0</a>':'\\0'))"
					), $text
	);
}

// find youtube video urls and embed them at the bottom of the comment
function embed_youtube($text) {
	preg_match_all('#(http://www.youtube.com)?/(v/([-|~_0-9A-Za-z]+)|watch\?v\=([-|~_0-9A-Za-z]+)&?.*?)#i', $text, $output);
	foreach ($output[4] AS $video_id) {
		if (!isset($video[$video_id])) {
			$video[$video_id] = true;
			$embed_code = '<iframe width="450" height="259" src="http://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
			$text .= $embed_code;
		}
	}
	return $text;
}

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
		case 'Info':
			return 'icon-home';
		case 'Demos':
			return 'icon-play';
		case 'Music':
			return 'icon-music';
		case 'Galleries':
			return 'icon-star';
		case 'Hacker Edition':
			return 'icon-fire';
		case 'Notes':
			return 'icon-list-alt';
		case 'Transcripts':
			return 'icon-align-left';
		case 'MP3':
			return 'icon-headphones';
		case 'MP4':
			return 'icon-film';
		case 'Review Session':
			return 'icon-facetime-video';
		case 'standard edition':
			return 'icon-cog';
		case 'Source Code':
			return 'icon-folder-open';
		case 'Subtitles':
			return 'icon-font';
		case 'Transcript':
			return 'icon-th-list';
		case 'Specification':
			return 'icon-list-alt';
		case 'Video':
		case 'Videos':
			return 'icon-facetime-video';
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

function rebase_path($path)
{
	$base = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
	return $base . $path;
}

function checked($string)
{
	if($string != '')
	{
		return "checked";
	}
}

function download_link($file)
{
	return('/' . UPLOAD_FOLDER . '/' . $file);
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
	$twig->addFilter('checked', new Twig_Filter_Function('checked'));
	$twig->addFilter('ago', new Twig_Filter_Function('time_elapsed_string'));
	$twig->addFunction('url', new Twig_Function_Function('rebase_path'));
	$twig->addFunction('download_link', new Twig_Function_Function('download_link'));

	return $twig;
}

function render($folder, $template, $context)
{
	global $site_links, $loggedIn, $uvanetid, $name;
	
	$twig = start_twig($folder);
	echo $twig->render($template, array_merge($context, array(
			'site_name'  => SITE_NAME,
			'site_title' => SITE_TITLE,
			'site_links' => $site_links,
			'logged_in' => $loggedIn,
			'progress' => percentage($uvanetid),
			'username' => $name,
			'admin' => isAdmin($uvanetid),
			'uvanetid' => $uvanetid
		)));
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
