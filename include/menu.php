<?php
// In this file the menu is created. The items are taken from the database and sorted correctly.

//This function highlights the mainmenu item of the main or sub item that is being viewed.
function menuItem($path, $name, $single, $folder, $subitem) {
	if (isset($_GET['p'])) {
		$pageExtra = $_GET['p'];
	} else {
		$pageExtra = '';
	}
	$path_ext = $path ? $path : '';
	$link = '<a href="' . BASE . $path_ext . '">' . $name . '</a>';
	if (!$subitem) {
		if ($single) {
			$extra = $pageExtra == $path ? ' id="active"' : '';
			$link = "<li{$extra}>" . $link . "</li>";
		} else {
			if (isset($_GET['folder'])) {
				$currentfolder = $_GET['folder'];
			} else {
				$currentfolder = '';
			}
			if ($currentfolder == $folder) {
				$extra = ' id="active"';
			} else {
				$extra = '';
				$resultsub = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $folder);
				while ($rowsub = mysql_fetch_array($resultsub)) {
					$folder = $rowsub['folder_id'];
					if ($currentfolder == $folder) {
						$extra = ' id="active"';
						break;
					} else {
						$extra = '';
					}
				}
			}
			$link = "<li{$extra}>" . $link;
		}
	}
	print $link;
}

//This creates the home button
menuItem("", "Home", TRUE, FALSE, FALSE);

//It checks to see if this is an existing user, and if it is it executes the code below.
//Otherwise it will ask the user to input a name.
if($pagebase != 'nieuweUser'){
	//first it will select everything from the correct table that is specified
	//in config.php, where the folder has parent 1. This means that it will
	//select all the main menu items.
	$result = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=1 ORDER BY weight");
	
	//The main menu items are then printed on the page and for each item
	//the sub menu item is selected from the database.
	while ($row = mysql_fetch_array($result)) {
		$episode_folderid = $row['folder_id'];
		$episode_title = $row['title'];
		menuItem("overview/$episode_folderid/", "$episode_title", FALSE, "$episode_folderid", FALSE);
		echo '<ul>';
		
		//Here it selects the sub menu item, this is done by using the parent structure.
		//It finds the the folders where the parent is the main menu item.
		$resultsub = mysql_query("SELECT * FROM " . DB_COURSE_FOLDERS . " WHERE parent=" . $row['folder_id']);
		$pagecount = 0;
		
		//It then prints all the submenu items to a maximum amount of ten items.
		//After this it will show a 'more...' option that will link to the overview page.
		while (($rowsub = mysql_fetch_array($resultsub)) && ($pagecount < 10)) {
			$subitemTitle = $rowsub['title'];
			$subitemFolder = $rowsub['folder_id'];
			
			//If a user is logged in it will need to check the progress.
			if (isset($uvanetid)) {
				$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = $uvanetid");
				$userid = mysql_fetch_array($useridresult);
				$foldersresult = mysql_query("SELECT folder_id FROM progress WHERE id =" . $userid['id']);
				$match = false;
				//If a user has completed a certain week it will be shown in green
				//instead of black.
				while ($rowfolders = mysql_fetch_array($foldersresult)) {
					if ($rowfolders['folder_id'] == $rowsub['folder_id']) {
						echo '<li class="green">';
						menuItem("content/$subitemFolder/", "$subitemTitle", FALSE, FALSE, TRUE);
						echo '</li>';
						$match = true;
						break;
					}
				}
				if ($match == false) {
					echo '<li>';
					menuItem("content/$subitemFolder/", "$subitemTitle", FALSE, FALSE, TRUE);
					echo '</li>';
				}
			//If a user has not logged in all the items will be shown in black.
			} else {
				echo '<li>';
				menuItem("content/$subitemFolder/", "$subitemTitle", FALSE, FALSE, TRUE);
				echo '</li>';
			}
			$pagecount++;
		}
		
		//This makes sure that the 'more...' option is shown after ten items.
		if ($pagecount >= 10) {
			echo '<li><a href="'.BASE.'overview/' . $row['folder_id'] . '/">more...</a>';
		}
		echo '</ul></li></li>';
	}
}
?>