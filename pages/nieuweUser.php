<?php
if (isset($_POST['submit'])) {

	if (is_numeric($uvanetid)) {
		$type = "Student";
	} else {
		$type = "Docent";
	}
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$firstname = htmlspecialchars(stripslashes($firstname));
	$firstname = mysql_real_escape_string($firstname);
	$lastname = htmlspecialchars(stripslashes($lastname));
	$lastname = mysql_real_escape_string($lastname);
	mysql_query("INSERT INTO users (uvanetid,type,firstname,lastname) VALUES ('{$uvanetid}', '{$type}', '{$firstname}', '{$lastname}')") or die(mysql_error());
	echo "Succesvol uw naam geregistreerd als " . $firstname . " " . $lastname . " met het UvaNetID nummer: " . $uvanetid . "!<br /> U kunt nu de website bezoeken als " . $type;
} else {
	?>
	<h1>Nieuwe Gebruiker</h1>
	Welkom <?php echo $uvanetid; ?>!<br />
	Omdat dit de eerste keer is dat u inlogt op deze website is het noodzakelijk om uw voor- en achternaam in te vullen:
	<form method="post" target="_self" name="register">
		Voornaam: <input type="text" name="firstname" />
		Achternaam: <input type="text" name="lastname" />
		<input type="submit" name="submit" value="Registreer" />
	</form>
	<?php
}
?>