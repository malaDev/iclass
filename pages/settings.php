<?php if (!isset($uvanetid)) { ?>
	<div class="warning">U moet ingelogd zijn om deze pagina te bezoeken.</div>
<?php
/* if user is logged in: */
} else {
	?>

	<h1>Settings</h1><br />
	<!-- show current avatar -->
	<h2>Huidige avatar:</h2><br />
	<img src='<?php echo lookforavatar($uvanetid, ""); ?>' width="100" height="100" alt="avatar" /><br />

	<!-- form to change avatar -->
	<h2>Verander je avatar:</h2><br />
	<form enctype="multipart/form-data" action="process/uploadAvatar.php" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="200000" />
		<input class="inputfield" name="uploadedAvatar" type="file" /><br />
		<input class="inputfield" name="uvanetid" type="hidden" value="<?php echo $uvanetid; ?>" /><br />
		<input class="button" type="submit" value="Upload Avatar" />
	</form>

	<?php
	/* uploading avatar done (process/uploadAvatar.php): success/error message*/
	if (isset($_GET['result'])) {
		if ($_GET['result'] == 0)
			echo "<div class=success>Upload gelukt</div>";
		else if ($_GET['result'] == 1)
			echo "<div class=error>Extensie niet toegestaan</div>";
		else if ($_GET['result'] == 2)
			echo "<div class=error>Bestand te groot</div>";
		else if ($_GET['result'] == 3)
			echo "<div class=error>Het uploaden is mislukt</div>";
	}
}
?>