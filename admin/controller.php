<?php

$twig = start_twig('admin');

if (!DB_COURSE_FOLDERS || !DB_COURSE_ITEMS) {
	$users = null;
	$page_links = null;
}

if (isAdmin($uvanetid))
	$page_items = array(
		'Personal settings' => rebase_path('admin/settings'),
		'Edit sections' => rebase_path('admin/sections'),
		'Import course pack' => rebase_path('admin/import'),
		'Student progress' => rebase_path('admin/users')
	);
else
	$page_items = null;

	$courses = null;
	$message = null;
if (!isset($page_links))
	$page_links = null;
if (!isset($new))
	$new = null;

switch ($request[1]) {
	// all four pages share common information so we group them early on
	// http://www.learnscape.nl/admin/*
	case "import":
		require 'admin_import.php';
		echo $twig->render($request[1] . '.html', array(
			'page_name' => $request[1],
			'page_title' => TITLE,
			'page_links' => $page_links,
			'page_items' => $page_items,
			'logged_in' => $loggedIn,
			'progress' => percentage($uvanetid),
			'url' => urlencode($url),
			'username' => $name,
			'type' => $user_type,
			'admin' => isAdmin($uvanetid),
			'email' => $email,
			'new' => $new,
			'uvanetid' => $uvanetid,
			'courses' => $courses,
			'message' => $message
		));
		return;
	case "change_settings":
		require 'php/change_settings.php';
		return;
	case "settings":
	case "sections":
		echo $twig->render($request[1] . '.html', array(
			'page_name' => $request[1],
			'page_title' => TITLE,
			'page_links' => $page_links,
			'page_items' => $page_items,
			'logged_in' => $loggedIn,
			'progress' => percentage($uvanetid),
			'url' => urlencode($url),
			'username' => $name,
			'type' => $user_type,
			'admin' => isAdmin($uvanetid),
			'email' => $email,
			'new' => $new,
			'uvanetid' => $uvanetid,
			'courses' => $courses,
			'message' => $message
		));
		return;
	case "users":
		mysql_query("SET lc_time_names=nl_NL");
		$query_users = "SELECT *, DATE_FORMAT(last_login, '%H:%i, %W %d %M %Y') AS last_login FROM users;";
		$result = mysql_query($query_users);
		while ($user = mysql_fetch_array($result)) {
			$users[$user['uvanetid']] = array($user['firstname'], $user['lastname'], $user['type'], percentage($user['uvanetid']), $user['last_login']);
		}
		
		echo $twig->render($request[1] . '.html', array(
			'page_name' => $request[1],
			'page_title' => TITLE,
			'page_links' => $page_links,
			'page_items' => $page_items,
			'logged_in' => $loggedIn,
			'progress' => percentage($uvanetid),
			'url' => urlencode($url),
			'username' => $name,
			'type' => $user_type,
			'admin' => isAdmin($uvanetid),
			'users' => $users,
			'email' => $email,
			'new' => $new,
			'uvanetid' => $uvanetid,
			'courses' => $courses,
			'message' => $message
		));
		return;
	default:
		header("Status: 404 Not Found");
		echo "404";
		return;
}
