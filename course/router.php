<?php
// get path info relative to directory this router is in
// "page/2", "admin/sections"
$clean_path = trim(substr($_SERVER['REQUEST_URI'], 1+strlen(dirname($_SERVER['SCRIPT_NAME']))), '/');
$request = explode('/', $clean_path);

include('include/config.php');
include('include/process.php');
include('util.php');
include('include/functions.php');



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
	default:
		if (isset($_GET['logout']))
			echo "logging out, please wait..";
		else{
		header("Status: 404 Not Found");
		echo "404";
		}
		return;
}
