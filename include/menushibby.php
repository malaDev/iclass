<?php

function l($path, $name, $li=FALSE) {
	if (isset($_GET['p'])) {
		$pageExtra = $_GET['p'];
	} else {
		$pageExtra = '';
	}
	$path_ext = $path ? $path . '/' : '';
	$link = '<a href="' . BASE . $path_ext . '">' . $name . '</a>';
	if ($li) {
		$extra = $pageExtra == $path ? ' id="active"' : '';
		$link = "<li{$extra}>" . $link;
	}
	print $link;
}

l("", "Home", TRUE);

$result = mysql_query("SELECT * FROM ".DB_COURSE_FOLDERS." WHERE parent=1 ORDER BY weight");

while ($row = mysql_fetch_array($result)) {
	echo '<li><a href="index.php?p=overview&folder='.$row['folder_id'].'">'.$row['title'].'</a>';

	echo '<ul>';
	$resultsub = mysql_query("SELECT * FROM ".DB_COURSE_FOLDERS." WHERE parent=".$row['folder_id']);
	while ($rowsub = mysql_fetch_array($resultsub)) {
		echo '<li><a href="index.php?p=content&folder='.$rowsub['folder_id'].'">'.$rowsub['title'].'</a></li>';
	}
	echo '</ul></li></li>';
}
?>
