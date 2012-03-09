<?php

/* Connect with database. Can't connect? show install1.php */
if (!@mysql_connect(DB_SERVER, DB_USER, DB_PASS))
{
	$case = 'database_connect';
	require("lib/install.php");
	die();
}
/* Select database */
if (!@mysql_select_db(DB_NAME))
{
	$case = 'database_select';
	require("lib/install.php");
	die();
}

// start session in order to create a session for the uvanetid of the user who is logged in
session_start();

// get id of current user
if (isset($_SESSION['UvANetID']) && !isset($_GET["logout"]) && !isset($_GET["ticket"]))
{
	$uvanetid = $_SESSION['UvANetID'];
	$startUser = stripos($uvanetid, "<cas:user>") + 10;
	$endUser = stripos($uvanetid, "</cas:user>");
	$length = $endUser - $startUser;
	$uvanetid = substr($uvanetid, $startUser, $length);
	$name = $uvanetid;
	$sql = "SELECT * FROM users WHERE uvanetid = '$uvanetid'";
	$result = mysql_query($sql);
	$rows = mysql_num_rows($result);
	
	// uvanetid not found in database?
	// then require user to fill in name
	if ($rows == 0){
		$new = true;
		if ($request[1] != "settings")
			header("Location: " . rebase_path('admin/settings') . "");
	}
	
	// uvanetid found in database?
	// then get name and id
	if ($rows > 0)
	{
		$user = mysql_fetch_array($result);
		$name = array($user['firstname'], $user['lastname']);
		$user_type = $user['type'];
		$user_id = $user['id'];
		$email = $user['email'];		
	}
	else
	{
		$user_type = '';
		$email = '';
	}
	
	$loggedIn = true;
}

// no valid user in session
else
{
	$loggedIn = false;
	$name = '';
	$email = '';
	$uvanetid = '';
	$user_type = '';
}
