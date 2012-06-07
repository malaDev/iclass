<?php

// this controller handles the default page and all course pages with at "page/..."
switch ($request[0])
{
	case "":
		// course home
		$user = mysql_query("select id from users where uvanetid = '" . $uvanetid . "'");
		$user_id = mysql_fetch_array($user, MYSQL_ASSOC);
		$user_id = $user_id["id"];

		$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$episode_title = $row['title'];
			if ($user_id)
			{
				$resultsub = mysql_query("SELECT parent, folders.folder_id, weight, title, (progress.id) as done FROM folders left outer join progress on folders.folder_id = progress.folder_id and progress.id = " . $user_id . " having folders.parent=" . $row['folder_id'] . " ORDER BY weight");
			}
			else
			{
				$resultsub = mysql_query("SELECT parent, folders.folder_id, weight, title FROM folders WHERE folders.parent=" . $row['folder_id'] . " ORDER BY weight");
			}
			$subarray = array();
			while ($rowsub = mysql_fetch_array($resultsub, MYSQL_ASSOC)) {
				$subitemTitle = $rowsub['title'];
				$subitemFolder = $rowsub['folder_id'];
				$subarray[$subitemTitle] = array('page/' . $subitemFolder, $rowsub['done']);
			}
			$page_done_links[$episode_title] = $subarray;
		}

		render('course', 'start.html', array(
			'page_title' => SITE_TITLE,
			'page_done_links' => $page_done_links
		));
		return;

	case "page":
	
		switch($page_id = $request[1])
		{
			case "change_progress":
				// No cache headers for AJAX
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
				
				if ($_GET['done'] == 'notdone')
				{
					// change from not done to done (Insert in database)
					$id = $_GET['id'];
					$folderid = $_GET['fid'];
					$foldersResult = mysql_query("SELECT * FROM progress WHERE id = $id AND folder_id = $folderid");
					$numRowsDone = mysql_num_rows($foldersResult);
					if ($numRowsDone == 0) 
						mysql_query("INSERT INTO progress (id, folder_id) VALUES ($id, $folderid)");
					echo "<label onclick=\"change_progress($id, $folderid, 'done')\" class=\"checkbox\"><input type=\"checkbox\" checked> Changed to Done!</label>";
				}
				else
				{
					// change from done to not done (delete from database)
					$id = $_GET['id'];
					$folderid = $_GET['fid'];
					mysql_query("DELETE FROM progress WHERE id = $id AND folder_id = $folderid");
					echo "<label onclick=\"change_progress($id, $folderid, 'notdone')\" class=\"checkbox\"><input type=\"checkbox\"> Changed to  not Done</label>";
				}
				return;
			
			case "update":
				if(isset($_POST['markdown']))
				{
					$content = mysql_real_escape_string($_POST['markdown']);
					$page_id = mysql_real_escape_string($_POST['page']);
					mysql_query("UPDATE " . DB_COURSE_FOLDERS . " set markdown = '$content' where folder_id = $page_id;");
					$url = rebase_path("page/$page_id");
					header("Location: $url");
				}
				break;
			default:
				// http://www.learnscape.nl/page/20
				if(!isset($request[1])) return error_404();
				if(!is_numeric($request[1])) return error_404();
				$id = $request[1];
		
				require_once("lib/markdown/markdown.php");
				require_once("lib/models/comments.php");
				require_once("lib/models/page.php");
				
				$page_source = Page::markdown($id);				
				render('course', 'page.html', array(
					'page_id' => $page_id,
					'page_name' => Page::title($id),
					'page_editable' => isset($uvanetid) && isAdmin($uvanetid),
					'page_doneable' => true,
					'page_done' => Page::done_by_user($id, $uvanetid),
					'page_items' => Page::items($id),
					'page_source' => $page_source,
					'page_info' => Markdown($page_source),
					'page_comments' => Comments::for_page($id, $uvanetid)
				));
				return;
		}
}
