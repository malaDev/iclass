<?php

// Upload folder (make sure the permissions for this folder are 776)
$upload_folder = "public/uploads";

//maximum allowed size of the file in bytes 
$max_size = "8000000";

// Allowed extensions 
$extensions = array(
	'jpg',
	'gif',
	'png',
	'rar',
	'zip',
	'pdf',
	'txt',
	'doc',
	'docx'
);
$file_newname = null;
$file_original = null;

// result = 3: no file uploaded
$result = 3;
if ($_FILES['myfile']['name'] != "" && $_POST['new-comment'] != "")
{
	// Get file name 
	$file_original = explode("\\", $_FILES['myfile']['name']);
	$last = count($file_original) - 1;
	$file_original = "$file_original[$last]";

	// Get extension 
	$file_temp = explode(".", $file_original);
	$last = count($file_temp) - 1;
	$ext = "$file_temp[$last]";
	$ext = strtolower($ext);
	
	// Make a unique filename with a timestamp
	// This makes file overwriting impossible
	for ($i = 0; $i < $last; $i++) {
		$file_newname .= $file_temp[$i];
	}
	$file_newname .= "-" . time() . "." . $ext;

	// Test if extension is in the list of allowed extensions
	$ext_error = TRUE;
	foreach ($extensions as $extension) {
		if ($ext == $extension) {
			$ext_error = FALSE;
		}
	}
	
	if ($ext_error == TRUE)
	{
		error_log("extension not allowed");
		$result = 1;
	}
	
	else
	{
		// result = 2: maximum size exceeded
		if ($_FILES['myfile']['size'] > $max_size)
			$result = 2;
		else {
			// result = 0: no errors, save the file
			error_log($upload_folder . '/' . $file_newname);
			if (@move_uploaded_file($_FILES['myfile']['tmp_name'], getcwd() . '/' . $upload_folder . '/' . $file_newname)) {
				$result = 0;
			}
			else
			{
				error_log("could not move file to final location, possible permissions error");
			}
		}
	}
	sleep(1);
}
