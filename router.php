<?php

include('lib/config.php');     // base configuration settings
require('lib/util.php');       // utility functions
require('lib/database.php');   // basic database connection setup
require('lib/course.php');     // base site info from database
require('lib/session.php');    // user session setup handling

// Find out the page type per the first URL segment and pass everything
// to the selected controller. $request will again be available there.
$request = parse_request();

switch($request[0])
{
	case "":
	case "page":
		// all course content pages as well as the home page
		include('course/controller.php');
		break;
	case "admin":
		// admin pages
		require('admin/controller.php');
		break;
	case "comments":
		// mostly ajax for page comments
		include('course/comments.php');
		break;
	case "replies":
		// mostly ajax for page comments
		include('course/replies.php');
		break;
	case "auth":
		// CAS authentication
		include('auth/controller.php');
		break;
	default:
		// return an error to the user
		header("Status: 404 Not Found");
		echo "404 - Page could not be found?!";
		break;
}
