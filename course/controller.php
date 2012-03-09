<?php

// this controller needs Twig
$twig = start_twig('course');

// this controller handles the default page and all course pages with at "page/..."
switch ($request[0]) {
	case "":
		// http://www.learnscape.nl/
		if (DB_COURSE_FOLDERS && DB_COURSE_ITEMS && isset($page_links)) {
			reset($page_links);
			echo $twig->render('start.html', array(
				'page_title' => TITLE,
				'page_slogan' => SLOGAN,
				'page_links' => $page_links,
				'page_items' => array(),
				'logged_in' => $loggedIn,
				'progress' => percentage($uvanetid),
				'url' => urlencode($url),
				'username' => $name,
				'admin' => isAdmin($uvanetid)
			));
		} else {
			echo $twig->render('start.html', array(
				'page_title' => TITLE,
				'page_slogan' => SLOGAN,
				'page_links' => '',
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

		if(!isset($request[1])) return error_404();
		if(!is_numeric($request[1])) return error_404();
		$folderid = $request[1];

		require_once("lib/markdown/markdown.php");
		require_once("lib/models/comments.php");
		require_once("lib/models/page.php");
		
		echo $twig->render('page.html', array(
			'page_name' => Page::title($folderid),
			'page_title' => TITLE,
			'page_links' => $page_links,
			'page_editable' => isset($uvanetid) && isAdmin($uvanetid),
			'page_doneable' => true,
			'page_done' => Page::done_by_user($folderid, $uvanetid),
			'page_items' => Page::items($folderid),
			'logged_in' => $loggedIn,
			'progress' => percentage($uvanetid),
			'url' => urlencode($url),
			'username' => $name,
			'uvanetid' => $uvanetid,
			'admin' => isAdmin($uvanetid),
			'info' => Markdown(Page::markdown($folderid)),
			'comments' => Comments::for_page($folderid)
		));
		
		return;
}
