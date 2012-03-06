<?php

// this controller needs Twig
$twig = start_twig('course');

// this controller handles the default page and all course pages with at "page/..."
switch ($request[0]) {
	case "":
		// http://www.learnscape.nl/
		// mysql
		
		if (DB_COURSE_FOLDERS && DB_COURSE_ITEMS && isset($page_links)) {
			reset($page_links);
			echo $twig->render('start.html', array(
				'page_name' => SLOGAN,
				'page_links' => $page_links,
				'page_next' => array(key(current($page_links)), current(current($page_links))),
				'page_items' => array(),
				'logged_in' => $loggedIn,
				'progress' => percentage($uvanetid),
				'url' => urlencode($url),
				'username' => $name,
				'admin' => isAdmin($uvanetid)
			));
		} else {
			echo $twig->render('start.html', array(
				'page_name' => SLOGAN,
				'page_links' => '',
				'page_next' => '',
				'page_items' => array(),
				'logged_in' => $loggedIn,
				'progress' => percentage($uvanetid),
				'url' => urlencode($url),
				'username' => $name,
				'admin' => isAdmin($uvanetid)
			));
		}
		return;
	case "page":
		// http://www.learnscape.nl/page/20
		if (isset($request[1]))
			$folderid = $request[1];
		else
			$folderid = '';
		if ($folderid != '' && is_numeric($folderid)) {
			require_once('mysql/page.php');
			require_once('ajax/get_comments.php');
			echo $twig->render('page.html', array(
				'page_name' => $request[1],
				'page_links' => $page_links,
				'page_editable' => $edit,
				'page_prev' => array($backtitle, 'page/' . $backid),
				'page_next' => array($nexttitle, 'page/' . $nextid),
				'page_doneable' => true,
				'page_done' => $done,
				'page_items' => $page_items,
				'logged_in' => $loggedIn,
				'progress' => percentage($uvanetid),
				'url' => urlencode($url),
				'username' => $name,
				'uvanetid' => $uvanetid,
				'admin' => isAdmin($uvanetid),
				'info' => $info,
				'comments' => $comments
			));
		} else {
			header("Status: 404 Not Found");
			echo "404";
		}
		return;
}
