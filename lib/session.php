<?php

// start session in order to create a session for the uvanetid of the user who is logged in
session_start();

// get id of current user
if (isset($_SESSION['UvANetID']))
{
	$uvanetid = $_SESSION['UvANetID'];
	$name = $uvanetid;
	
	$sql = "SELECT * FROM users WHERE uvanetid = '$uvanetid'";
	$result = mysql_query($sql);
	$rows = mysql_num_rows($result);
	
	// uvanetid found in database?
	// then get name and id
	if ($rows > 0)
	{
		$user = mysql_fetch_array($result);
		$name = array($user['firstname'], $user['lastname']);
		$user_type = $user['type'];
		$user_id = $user['id'];
		$email = $user['email'];
		if($user['avatar'] != "")
			$user_avatar = 'public/avatars/' . $user['avatar'];
		else
			$user_avatar = 'public/img/no-avatar.gif';
	}
	else
	{
		$clean_path = $_SERVER['REQUEST_URI'];
		if(strpos($clean_path, "?") > 0) $clean_path = strstr($clean_path, "?", true);
		$clean_path = substr($clean_path, strlen(dirname($_SERVER['SCRIPT_NAME']))); 
		$clean_path = trim($clean_path, '/');
		
		if("admin/settings" != $clean_path)
			header("Location: /admin/settings");
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
