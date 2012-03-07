<?php

include('lib/config.php');
include('lib/process.php');
include('lib/util.php');
include('lib/functions.php');

$request = parse_request();

// select page type
// $request is avaiable in all controllers and not modified before passing control
switch($request[0])
{
	case "":
	case "page":
		// all content pages
		include('course/controller.php');
		return;
	case "admin":
		// admin pages
		include('admin/controller.php');
		return;
	case "comments":
		include('comments/controller.php');
		return;
	case "replies":
		include('comments/controller.php');
		return;
	default:
		if (isset($_GET['logout']))
			echo "logging out, please wait..";
		else{
		header("Status: 404 Not Found");
		echo "404";
		}
		return;
}

