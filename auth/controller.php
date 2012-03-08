<?php

// $twig = start_twig('course');

$cas_path = "http://" . $_SERVER['SERVER_NAME'] . rebase_path('auth/validate');

switch ($request[1])
{
	case "login":
		// redirect to CAS server specified in config
		header("Location: " . CAS_LOGIN_URL . urlencode($cas_path));
		return;
	case "validate":
		error_log("checking ...");
		// check ticket provided and enter user session
		if (isset($_GET["ticket"]))
		{
			$ticket = $_GET["ticket"];
			$validateURL = CAS_VALIDATE_URL . $ticket . "&service=" . urlencode($cas_path);
			// make sure extension=php_openssl.dll is added to php.ini in order to make this work
			$file = file_get_contents($validateURL);
			$_SESSION['UvANetID'] = $file;
			header("Location: " . rebase_path(''));
		}
		return;
	case "logout":
		// redirect to CAS logout and return here
		if (isset($_GET["logout"]))
		{
			session_destroy();
			echo <<<IFRAME
<iframe id="logout" style="display:none" onload="window.location = '$redirect'" src="CAS_LOGOUT_URL">
<p>Your browser does not support iframes.</p>
</iframe>
IFRAME;
		}
}
