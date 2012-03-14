<?php

$cas_path = "http://" . $_SERVER['SERVER_NAME'] . rebase_path('auth/validate');

switch ($request[1])
{
	case "login":
		// redirect to CAS server specified in config
		header("Location: " . CAS_LOGIN_URL . urlencode($cas_path));
		return;
	case "validate":
		// check ticket provided and enter user session
		if (isset($_GET["ticket"]))
		{
			$ticket = $_GET["ticket"];
			$validateURL = CAS_VALIDATE_URL . $ticket . "&service=" . urlencode($cas_path);
			
			if(defined('USER_OVERRIDE'))
			{
				error_log('USERNAME OVERRIDE ACTIVE!');
				$_SESSION['UvANetID'] = USER_OVERRIDE;
				header("Location: " . rebase_path(''));
			}
			else
			{
				// make sure extension=php_openssl.dll is added to php.ini in order to make this work
				$file = file_get_contents($validateURL);
				if(preg_match('/<cas:user>([^<]+)<\/cas:user>/', $file, $match))
				{
					$_SESSION['UvANetID'] = $match[1];
					header("Location: " . rebase_path(''));
				}
				else
				{
					echo "500 - Errorrr getting CAS response";
				}
			}
		}
		return;
	case "logout":
		// redirect to CAS logout and return here
		session_destroy();
		render('auth', 'logout.html', array(
			'redirect' => rebase_path(''),
			'cas_logout_url' => ''
		));
		return;
}
