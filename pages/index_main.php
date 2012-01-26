<?php
	$result = mysql_query("SELECT * FROM ".DB_COURSE_FOLDERS." WHERE parent=1 ORDER BY weight");
	
	while ($row = mysql_fetch_array($result)) {
		$sucess = $row['done'];
		if($sucess) {
			echo '<div class="done"><li><a href="index.php?p=overview&folder='.$row['folder_id'].'">'.$row['title'].'</a></li></div>';
		}
		else {
			echo '<div class="notdone"><li><a href="index.php?p=overview&folder='.$row['folder_id'].'">'.$row['title'].'</a></li></div>';
		}
	}
?>