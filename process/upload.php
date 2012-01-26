<?php
// Upload map ( zorg dat deze de permissies 776 krijgt ) 
$map = "../uploads/";

//maximale groote van het bestand in bytes 
$max = "8000000";

// Welke extensies kunnen er worden geupload ( als alles mag dan niks invullen ) 
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
$bestandfinal = null;
$bestand2 = null;
$result = 3;
if ($_FILES['myfile']['name'] != "") {
	if ($_POST['title'] != "" && $_POST['body'] != "") {
		// Bestands naam opvragen 
		$bestand2 = explode("\\", $_FILES['myfile']['name']);
		$laatste = count($bestand2) - 1;
		$bestand2 = "$bestand2[$laatste]";

		// Extensie van bestand opvragen 
		$bestand3 = explode(".", $bestand2);
		$laatste = count($bestand3) - 1;
		$ext = "$bestand3[$laatste]";
		$ext = strtolower($ext);
		
		for ($i = 0; $i < $laatste; $i++){
		$bestandfinal .= $bestand3[$i];
		}
		$bestandfinal .= "-".time().".".$ext;

		// Toegestaande extensies opvragen 
		foreach ($extensions as $extension) {
			if ($ext == $extension) {
				$extfout = "false";
			}
		}

		if (!$extfout) {
			$result = 1;
		} else {
			if ($_FILES['myfile']['size'] > $max)
				$result = 2;
			else {
				// Opslaan van het bestand 
				if (@move_uploaded_file($_FILES['myfile']['tmp_name'], $map.$bestandfinal)) {
					$result = 0;
				}
			}
		}
		sleep(1);
	}
}
?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload('<?php echo $result; ?>', '<?php echo $bestandfinal; ?>', '<?php echo $bestand2; ?>');</script>