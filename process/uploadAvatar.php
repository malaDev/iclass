<?php
// result = 3: no file uploaded
$result = 3;

//maximum allowed size of the file in bytes 
$max = "200000";

$uvanetid = $_POST['uvanetid'];

// Upload folder (make sure the permissions for this folder are 776)
$target_path = "../uploads/avatars/";

// Get extension 
$extension = end(explode('.', basename($_FILES['uploadedAvatar']['name'])));
$target_path = $target_path . $uvanetid . "." . $extension;

// check if image is really a image
$imageinfo = getimagesize($_FILES['uploadedAvatar']['tmp_name']);

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

// result = 1: extension not allowed
if ($correct_path == 0)
	$result = 1;
// result = 2: maximum size exceeded
else if ($_FILES['uploadedAvatar']['size'] > $max)
	$result = 2;
// result = 0: no errors, save the file
else if (move_uploaded_file($_FILES['uploadedAvatar']['tmp_name'], $target_path)) {
	//delete all other files
	foreach ($allowed_extensions as $value3) {
		if (file_exists("../uploads/avatars/" . $uvanetid . "." . $value3) && $value3 != $extension) {
			unlink("../uploads/avatars/" . $uvanetid . "." . $value3);
			break;
		}
	}
	$result = 0;
} else
	$result = 3;

// Uploading is done, go back to settings.php with an success/error result
header("Location: http://websec.science.uva.nl/webdb1233/settings&result=$result");
?>
