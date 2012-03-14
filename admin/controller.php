<?php

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

switch ($request[1]) {
	// all four pages share common information so we group them early on
	// http://www.learnscape.nl/admin/*
	case "import":
		require 'admin_import.php';
		render('admin', $request[1] . '.html', array(
			'page_name' => $request[1],
			'page_items' => $page_items,
			'courses' => $courses
		));
		return;
	case "settings":
	case "change_settings":
		require 'change_settings.php';
		render('admin', $request[1] . '.html', array(
			'page_name' => $request[1],
			'page_items' => $page_items
		));
		return;
	case "sections":
		require 'sections.php';
		return;
	case "users":
		mysql_query("SET lc_time_names=nl_NL");
		$query_users = "SELECT * FROM users;";
		$result = mysql_query($query_users);
		while ($user = mysql_fetch_array($result))
		{
			$users[$user['uvanetid']] = array(
				'first_name' => $user['firstname'],
				'last_name' => $user['lastname'],
				'type' => $user['type'],
				'progress' => percentage($user['uvanetid']),
				'last_login' => strtotime($user['last_login'])
			);
		}
		render('admin', 'users.html', array(
			'page_name' => $request[1],
			'page_items' => $page_items,
			'users' => $users
		));
		return;
	case "createdb":
		require "create_db.php";
		return;
	default:
		header("Status: 404 Not Found");
		echo "404";
		return;
}
