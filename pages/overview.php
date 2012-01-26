<?php
	if (isset($_GET['folder'])) {
		$folderid = $_GET['folder'];
	} else {
		$folderid = '';
	}
			
	$result = mysql_query("SELECT * FROM ".DB_COURSE_FOLDERS." WHERE parent=".$folderid);
		
	while ($row = mysql_fetch_array($result)) {
		$sucess = $row['done'];
		if($sucess) {
			echo '<div class="done"><a href="index.php?p=content&folder='.$row['folder_id'].'">'.$row['title'].'</a></div>';
		}
		else {
			echo '<div class="notdone"><a href="index.php?p=content&folder='.$row['folder_id'].'">'.$row['title'].'</a></div>';
		}
	}
?>