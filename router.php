<?php

include('lib/config.php');     // base configuration settings
include('lib/util.php');       // utility functions
include('lib/session.php');    // user session setup handling
include('lib/process.php');    // base site info from database

$request = parse_request();

/* Redirect to secure https 
  if ($_SERVER['HTTPS'] != "on") {
  $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header("Location:$redirect");
  } */


// select page type
// $request is available in all controllers and not modified before passing control
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
	case "auth":
		include('auth/controller.php');
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

