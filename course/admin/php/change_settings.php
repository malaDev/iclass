<?php

if (isset($_POST['firstname'])) {
	if ($_POST['firstname'] != '' && $_POST['lastname'] != '' && $_POST['email'] != '') {
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$firstname = htmlspecialchars(stripslashes($firstname));
		$firstname = mysql_real_escape_string($firstname);
		$lastname = htmlspecialchars(stripslashes($lastname));
		$lastname = mysql_real_escape_string($lastname);
		$email = htmlspecialchars(stripslashes($email));
		$email = mysql_real_escape_string($email);
		$uvanetid = $_POST['uvanetid'];
		if (is_numeric($uvanetid)) {
			$type = "Student";
		} else {
			$type = "Docent";
		}
		$sql = "SELECT * FROM users WHERE uvanetid = '$uvanetid'";
		$result = mysql_query($sql);
		$rows = mysql_num_rows($result);
		if ($rows == 0)
			$query = mysql_query("INSERT INTO users (uvanetid,type,firstname,lastname, email) VALUES ('{$uvanetid}', '{$type}', '{$firstname}', '{$lastname}', '{$email}')");
		else
			$query = mysql_query("UPDATE users SET type='{$type}',firstname='{$firstname}',lastname='{$lastname}', email='{$email}' WHERE uvanetid = '{$uvanetid}'");
		if ($query && mysql_affected_rows() > 0)
			$resultuser = 1;
		else
			$resultuser = 0;
		if ($resultuser) {
			if ($_FILES['avatar']['name'] != "") {
//maximum allowed size of the file in bytes 
				$max = "400000";

// Upload folder (make sure the permissions for this folder are 776)
				$target_path = "assets/avatars/";

// Get extension 
				$extension = explode('.', basename($_FILES['avatar']['name']));
				$extension = end($extension);
				$target_path = $target_path . $uvanetid . "." . $extension;

// check if image is really a image
				$imageinfo = getimagesize($_FILES['avatar']['tmp_name']);

// Allowed extensions 
				$allowed_extensions = array(
					'jpg',
					'gif',
					'png',
					'bmp',
					'jpeg',
				);

				$correct_path = 0;

// Test if extension is in the list of allowed extensions
				foreach ($allowed_extensions as $value) {
					if ($imageinfo['mime'] == 'image/' . $value) {
						foreach ($allowed_extensions as $value2) {
							if ($extension == $value2) {
								$correct_path = 1;
								break;
							}
						}
					}
				}

				if ($correct_path) {
					if ($_FILES['avatar']['size'] <= $max) {
						if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path)) {
							//delete all other files
							foreach ($allowed_extensions as $value3)
								if (file_exists("../uploads/avatars/" . $uvanetid . "." . $value3) && $value3 != $extension) {
									unlink("../uploads/avatars/" . $uvanetid . "." . $value3);
									break;
								}
							$resultavatar = 1;
						}else
							$resultavatar = 0;
					}else
						$resultavatar = 2;
				} else
					$resultavatar = 3;
			} else
				$resultavatar = "";
		} else
			$resultavatar = "";
	} else {
		$resultuser = 2;
		$resultavatar = "";
	}
} else {
	$resultuser = "";
	$resultavatar = "";
}
?>