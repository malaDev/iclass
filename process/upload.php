<?php
// Upload folder (make sure the permissions for this folder are 776) 
$upload_folder = "../uploads/";

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
if ($_FILES['myfile']['name'] != "") {
	if ($_POST['title'] != "" && $_POST['body'] != "") {
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
		
		// result = 1: extension not allowed
		if ($ext_error == TRUE) {
			$result = 1;
		} else {
			// result = 2: maximum size exceeded
			if ($_FILES['myfile']['size'] > $max_size)
				$result = 2;
			else {
				// result = 0: no errors, save the file
				if (@move_uploaded_file($_FILES['myfile']['tmp_name'], $upload_folder . $file_newname)) {
					$result = 0;
				}
			}
		}
		sleep(1);
	}
}
?>
<!-- Uploading is done, call stopUpload function (comments.php) -->
<script language="javascript" type="text/javascript">window.top.window.stopUpload('<?php echo $result; ?>', '<?php echo $file_newname; ?>', '<?php echo $file_original; ?>');</script>