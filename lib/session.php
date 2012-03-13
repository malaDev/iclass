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
