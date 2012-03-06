<?php

/* start session in order to create a session for the uvanetid of the user who is logged in */
session_start();

/* Redirect to secure https 
  if ($_SERVER['HTTPS'] != "on") {
  $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header("Location:$redirect");
  } */
$redirect = rebase_path('');
/* Validate the ticket gain from logging in on the CAS server */
if (isset($_GET["ticket"])) {
	$ticket = $_GET["ticket"];
	$validateURL = "https://bt-lap.ic.uva.nl/cas/serviceValidate?ticket=" . $ticket . "&service=" . $url;
	// make sure extension=php_openssl.dll is added to php.ini in order to make this work
	$file = file_get_contents($validateURL);
	$_SESSION['UvANetID'] = $file;

	header("Location: $redirect");
}

/* Logout (destroy our session and also log out on the CAS server with invisible iframe */
if (isset($_GET["logout"])) {
	session_destroy();
	?>
	<iframe id="logout" style="display:none" onload="window.location = '<?php echo $redirect; ?>'" src="https://bt-lap.ic.uva.nl/cas/logout">
	<p>Your browser does not support iframes.</p>
	</iframe>
	<?php

}

/* Get uvanetid of current user */
$_SESSION['UvANetID'] = '<cas:user>10184465</cas:user>';
if (isset($_SESSION['UvANetID']) && !isset($_GET["logout"]) && !isset($_GET["ticket"])) {
	$uvanetid = $_SESSION['UvANetID'];
	$startUser = stripos($uvanetid, "<cas:user>") + 10;
	$endUser = stripos($uvanetid, "</cas:user>");
	$length = $endUser - $startUser;
	$uvanetid = substr($uvanetid, $startUser, $length);
	$name = $uvanetid;
	$sql = "SELECT * FROM users WHERE uvanetid = '$uvanetid'";
	$result = mysql_query($sql);
	/* uvanetid not found in database? redirect to nieuweUser.php */
	$rows = mysql_num_rows($result);
	if ($rows == 0){
		$new = true;
		if ($request[1] != "settings")
			header("Location: " . rebase_path('admin/settings') . "");
	}
	/* uvanetid found in database? Get name and id of user */
	if ($rows > 0) {
		$user = mysql_fetch_array($result);
		$name = array($user['firstname'], $user['lastname']);
		$user_type = $user['type'];
		$user_id = $user['id'];
		$email = $user['email'];		
	} else {
	$user_type = '';
	$email = '';
	}
	$loggedIn = true;
} else {
	$loggedIn = false;
	$name = '';
	$email = '';
	$uvanetid = '';
	$user_type = '';
}

/* progress of user */
function percentage($uvanetid) {
	if ($uvanetid == '' || (!DB_COURSE_FOLDERS || !DB_COURSE_ITEMS))
		return 0;
	//progress bar, percentage -> pixels:(121/100)*(100-percent done) = pixels
	//total numbers of episodes:
	$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
	$episodeCount = 0;
	while ($row = mysql_fetch_array($result)) {
		$resultsub = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $row['folder_id']) or die(mysql_error());
		$numRows = mysql_num_rows($resultsub);
		$episodeCount = $episodeCount + $numRows;
	}
	if ($episodeCount > 0) {
		//total number of episodes done 
		$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
			if (mysql_num_rows($useridresult) == 0)
		return;
		$userid = mysql_fetch_array($useridresult);
		$resultuser = mysql_query("SELECT COUNT(id) FROM progress WHERE id =" . $userid['id']) or die(mysql_error());
		$resultuser2 = mysql_result($resultuser, 0);

		//percentage done
		$percentage = $resultuser2 / $episodeCount;
		$percentage = $percentage * 100;
	} else {
		$percentage = 0;
	}
	return round($percentage);
}

function isAdmin($uvanetid) {
	global $admin_users;
	foreach ($admin_users as $admin_user) {
//see if the logged in user is part of the admin group
		if ($uvanetid == $admin_user) {
			return true;
		}
	}
	return false;
}
?>

