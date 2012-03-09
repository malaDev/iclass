<?php

require('lib/config.php');     // base configuration settings
require('lib/util.php');       // utility functions
$request = parse_request();
require('lib/session.php');    // user session setup handling
require('lib/process.php');    // base site info from database

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
		require('course/controller.php');
		return;
	case "admin":
		// admin pages
		require('admin/controller.php');
		return;
	case "comments":
		require('comments/controller.php');
		return;
	case "replies":
		require('comments/controller.php');
		return;
	case "auth":
		require('auth/controller.php');
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

