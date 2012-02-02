<?php
$form = true;

/* Form is submitted? insert user in database and hide form */
if (isset($_POST['submit'])) {
	/* Name has to be longer than 2 chars */
	if (strlen($_POST['firstname']) < 2 || strlen($_POST['lastname']) < 2) {
		echo "<div class=error>Voor -en achternaam moeten minstens 2 tekens bevatten</div>";
	} else {
		$form = false;
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
		$query = mysql_query("INSERT INTO users (uvanetid,type,firstname,lastname) VALUES ('{$uvanetid}', '{$type}', '{$firstname}', '{$lastname}')") or die(mysql_error());
		if ($query)
			echo "<div class=success>Succesvol uw naam geregistreerd als " . $firstname . " " . $lastname . " met het UvaNetID nummer: " . $uvanetid . "!<br /> U kunt nu de website bezoeken als " . $type . "</div>";
		else
			echo "<div class=error>Er is iets misgegaan met de database query";
	}
}

/* $form = true? show form */
if ($form == true) {
	?>
	<h1>Nieuwe Gebruiker</h1>
	Welkom <?php echo $uvanetid; ?>!<br />
	Omdat dit de eerste keer is dat u inlogt op deze website is het noodzakelijk om uw voor- en achternaam in te vullen:<br /><br />
	<form method="post" target="_self" name="register">
		<table width="321px">
			<tr>
				<td width="100px">Voornaam:</td>  <td><input class="inputfield" type="text" name="firstname" /></td>
			</tr>
			<tr>
				<td>
					Achternaam: </td><td><input class="inputfield" type="text" name="lastname" /><br /></td>
		</tr>
		<tr>
			<td></td><td>
		<input class="submit" type="submit" name="submit" value="Registreer" />
		</td>
		</tr>
		</table>
	</form>
	<?php
}
?>