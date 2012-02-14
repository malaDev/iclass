<?php
/* start session in order to create a session for the uvanetid of the user who is logged in */
session_start();

/* Redirect to secure https 
if ($_SERVER['HTTPS'] != "on") {
	$redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header("Location:$redirect");
}*/

/* Validate the ticket gain from logging in on the CAS server */
if (isset($_GET["ticket"])) {
	$ticket = $_GET["ticket"];
	$validateURL = "https://bt-lap.ic.uva.nl/cas/serviceValidate?ticket=" . $ticket . "&service=" . $url;
	// make sure extension=php_openssl.dll is added to php.ini in order to make this work
	$file = file_get_contents($validateURL);
	$_SESSION['UvANetID'] = $file;
	header("Location: index.php");
}

/* Logout (destroy our session and also log out on the CAS server with invisible iframe */
if (isset($_GET["logout"])) {
	session_destroy();
	?>
	<iframe id="logout" style="display:none" onload="window.location = 'index.php'" src="https://bt-lap.ic.uva.nl/cas/logout">
	<p>Your browser does not support iframes.</p>
	</iframe>
	<?php

}

/* Get uvanetid of current user */
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
	if ($rows == 0 && $_GET['p'] != "nieuweUser")
		header("Location: " . BASE . "index.php?p=nieuweUser");
	/* uvanetid found in database? Get name and id of user */
	if ($rows == 1) {
		$user = mysql_fetch_array($result);
		$name = $user['firstname'] . " " . $user['lastname'];
		$user_type = $user['type'];
		$user_id = $user['id'];
	}
}

/* progress of user */

function progress($uvanetid, $bar) {
	//progress bar, percentage -> pixels:(121/100)*(100-percent done) = pixels
	//total numbers of episodes:
	$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
	$episodeCount = 0;
	while ($row = mysql_fetch_array($result)) {
		$resultsub = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $row['folder_id']) or die(mysql_error());
		$numRows = mysql_num_rows($resultsub);
		$episodeCount = $episodeCount + $numRows;
	}
	if ($episodeCount > 0){
		//total number of episodes done 
		$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
		$userid = mysql_fetch_array($useridresult);
		$resultuser = mysql_query("SELECT COUNT(id) FROM progress WHERE id =" . $userid['id']) or die(mysql_error());
		$resultuser2 = mysql_result($resultuser, 0);

		//percentage done
		$percentage = $resultuser2 / $episodeCount;
		$percentage = $percentage * 100;
	} else {
		$percentage = 0;
	}
	

	if ($bar == true) {
		//show progress bar
		$reken1 = 121 / 100;
		$reken2 = 100 - $percentage;
		$pxpercentage = $reken1 * $reken2;
		$pxpercentage = $pxpercentage;
		echo '<img src="' . BASE . 'images/percentimage.png" alt="progress bar" class="percentImage" style="background-position:-' . $pxpercentage . 'px 0pt;" />';
	}
	echo " <span class='smallfont'>" . round($percentage) . "% </span>";
}

function percentage($uvanetid) {
	//progress bar, percentage -> pixels:(121/100)*(100-percent done) = pixels
	//total numbers of episodes:
	$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
	$episodeCount = 0;
	while ($row = mysql_fetch_array($result)) {
		$resultsub = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $row['folder_id']) or die(mysql_error());
		$numRows = mysql_num_rows($resultsub);
		$episodeCount = $episodeCount + $numRows;
	}
	if ($episodeCount > 0){
		//total number of episodes done 
		$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
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
?>

