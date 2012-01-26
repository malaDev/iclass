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
//Hiermee selecteer je alle folders van de db waar parents = 1, dus de hoofdfolders das $result
while ($row = mysql_fetch_array($result)) {
	echo '<li><a href="index.php?p=overview&folder='.$row['folder_id'].'">'.$row['title'].'</a>';
//als je op de $result klikt, dan ga je naar overview
	echo '<ul>';
	//dropdown
	$resultsub = mysql_query("SELECT * FROM ".DB_COURSE_FOLDERS." WHERE parent=".$row['folder_id']);
	//resultsub zijn alle folders van db parent=".$row['folder_id']);
	while ($rowsub = mysql_fetch_array($resultsub)) {
	
		//ok... id halen van user
		if (isset($uvanetid)){
			$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = $uvanetid");
			$userid = mysql_fetch_array($useridresult);
			$foldersresult = mysql_query("SELECT folder_id FROM progress WHERE id =".$userid['id']);
			$match = false;
			while ($rowfolders = mysql_fetch_array($foldersresult)){
					if($rowfolders['folder_id'] == $rowsub['folder_id']) {
					//testen of folder_id van de gebruiker overeenkomt met de folder_id
						echo '<li class="green"><a href="index.php?p=content&folder='.$rowsub['folder_id'].'">'.$rowsub['title'].'</a></li>';
						$match = true;
						break;
					}
				}
					if ($match == false) {
						echo '<li class="orange"><a  href="index.php?p=content&folder='.$rowsub['folder_id'].'">'.$rowsub['title'].'</a></li>';
					}
			
		} else {
			echo '<li><a href="index.php?p=content&folder='.$rowsub['folder_id'].'">'.$rowsub['title'].'</a></li>';
		}

	}
	echo '</ul></li></li>';
}
	
	
	
//
//-In de database staat een tabel met user_id en folder_id.

//-Voor elk item in het dropdown menu moet gecheckt worden of de items voor de ingelogde user in de database staan. (checken met folder_id)
//Als dit wel zo is, dan div class = done, anders div class = notdone.

//-Onder de video moet je een button hebben met een vinkje, als je het aanvinkt insert je in de database, afvinken delete je uit de database.
//-Boven de video staat ook een div class = done of notdone.

//-De progress bar staat rechtsboven naast je naam, en onder je naam bij een post. 
//De progress van een student is het percentage van het aantal bekeken items van het dropdown menu. Hoe vaak staat de user_id in de database/hoeveel items zijn er.
?>