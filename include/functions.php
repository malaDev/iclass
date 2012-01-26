<?php


if (isset($_GET["ticket"])) {
	$ticket = $_GET["ticket"];
	$validateURL = "https://secure.uva.nl/cas/serviceValidate?ticket=" . $ticket . "&service=http://websec.science.uva.nl" . BASE . "index.php";
	$file = file_get_contents($validateURL);
	$_SESSION['UvANetID'] = $file;
	header("Location: index.php");
}

if (isset($_GET["logout"])) {
	session_destroy();
	?>
	<iframe id="logout" style="display:none" onload="window.location = 'index.php'" src="https://secure.uva.nl/cas/logout">
	<p>Your browser does not support iframes.</p>
	</iframe>
	<?php

}
if (isset($_SESSION['UvANetID']) && !isset($_GET["logout"]) && !isset($_GET["ticket"])) {
	$uvanetid = $_SESSION['UvANetID'];
	$startUser = stripos($uvanetid,"<cas:user>") + 10;
	$endUser = stripos($uvanetid,"</cas:user>");
	$length = $endUser - $startUser;
	$uvanetid = substr($uvanetid,$startUser,$length);
	$name = $uvanetid;
	$sql = "SELECT * FROM users WHERE uvanetid = '{$uvanetid}'";
	$result = mysql_query($sql);
	
	$rows = mysql_num_rows($result);
	if ($rows == 0 && $_GET['p'] != "nieuweUser")
		header("Location: " . BASE . "nieuweUser");
	if ($rows == 1) {
		$user = mysql_fetch_array($result);
		$name = $user['firstname']." ".$user['lastname'];
		$user_type = $user['type'];
		$user_id = $user['id'];
	}

}

$usercount = mysql_fetch_row(mysql_query("SELECT count(*) FROM users"));
// As we can't set cronjobs, this is a cron workaround that works fine aswell.
srand(date('z')); // Seed pseudorandom nr generator with day of the year
$focus_uid = rand(1,$usercount[0])-1;
$focus_user_row = mysql_fetch_row(mysql_query("SELECT firstname, lastname FROM users LIMIT {$focus_uid}, 1"));
$focus_user_firstn = $focus_user_row[0];
$focus_user_lastn = $focus_user_row[1];


?>

