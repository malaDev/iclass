<?php

include('lib/config.php');     // base configuration settings
include('lib/util.php');       // utility functions

// Find out the page type per the first URL segment and pass everything
// to the selected controller. $request will again be available there.
$request = parse_request();

require('lib/session.php');    // user session setup handling
require('lib/process.php');    // base site info from database

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
