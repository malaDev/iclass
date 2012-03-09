<?php

if (isAdmin($uvanetid)) {
	if (isset($_POST['delete'])) {
		mysql_query("DROP TABLE course__" . $_POST['delete'] . "_folders, course__" . $_POST['delete'] . "_items");
		mysql_query("DELETE FROM courses WHERE course_id=" . $_POST['delete']);
	}

	if (isset($_POST['course_title']) && isset($_POST['course_xml']) && isset($_POST['course_action'])) {
		require_once("course_xmlread.php");
		$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : 0;

		if ($_POST['course_xml'] != "") {
			$paste = false;
			$course_xml = $_POST['course_xml'];
		} else {
			$paste = true;
			$course_xml = $_POST['course_xml_paste'];
		}
		create_course($_POST['course_title'], $course_xml, $paste, $_POST['course_action'], $user_id, $course_id);
	}
	
		$result = mysql_query("SELECT course_name, firstname, lastname, update_date, course_id, xml_feed FROM courses LEFT JOIN users ON courses.teacher_id = users.id ORDER BY courses.update_date DESC");
		if (mysql_num_rows($result) != 0) {
			while ($row = mysql_fetch_row($result)) {
				if (preg_match("/^assets\/xml\/xml.txt/", $row[5])) 
					$xml_feed = array(htmlspecialchars(file_get_contents($row[5])), true); 
				else
					$xml_feed = array($row[5], false); 
				$courses[$row[4]] = array ($row[0], $row[1], $row[2], $row[3], $xml_feed);
				}
			}
}
