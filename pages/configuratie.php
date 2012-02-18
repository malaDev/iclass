<script type="text/javascript">	
	function showLoading(){
		document.getElementById('loading').style.display = 'block';
		return true;
	}
	function checkEmpty(){
		if (document.getElementById('course_xml').value == ""){
			document.getElementById('course_xml_paste').style.display = 'block';
			document.getElementById('course_xml_paste_hidden_msg').style.display = 'none';			
		} else {
			document.getElementById('course_xml_paste').style.display = 'none';
			document.getElementById('course_xml_paste_hidden_msg').style.display = 'block';
		}
	}
</script>
<h1>Configuratie</h1>
<div id="messages"></div>
<span id="loading" style="display: none">&nbsp; Laden... <br /><img src="<?php echo BASE; ?>images/loader.gif" /><br />Kan even duren bij grote xml bestanden.<br/>Duurt het langer dan een paar minuten? probeer het dan opnieuw<br /><br /></span>
<?php
if (isAdmin($uvanetid)) {
	if (isset($_POST['delete'])) {
		mysql_query("DROP TABLE course__" . $_POST['delete'] . "_folders, course__" . $_POST['delete'] . "_items");
		mysql_query("DELETE FROM courses WHERE course_id=" . $_POST['delete']);
	}

	if (isset($_POST['course_title']) && isset($_POST['course_xml']) && isset($_POST['course_action'])) {
		require_once("include/course_xmlread.php");
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
	?>
	<p>Hier kunt u vakken toevoegen, verwijderen of updaten. Het laatst gewijzigde vak wordt op de site gepresenteerd.</p>
	<p>Herlaad de pagina na een wijziging in de configuratie, om het verschil in de sitestructuur te zien.</p>
	<h2>Aanwezige cursussen</h2>
	<table id="course_overview">
		<?php
		//print a from with as default values the current values in the database
		// LEFT JOIN because if an admin is deleted from the db that has previously created a course, the course must still be printed (but without author name)
		$result = mysql_query("SELECT course_name, firstname, lastname, update_date, course_id, xml_feed FROM courses LEFT JOIN users ON courses.teacher_id = users.id ORDER BY courses.update_date DESC");
		while ($row = mysql_fetch_row($result)) {
			if (mysql_num_rows($result) != 0) {
				?>
				<tr><th width="250px">Titel</th><th width="350px">Laatst aangepast</th><th>Operaties</th><th></th></tr>
				<?php
				print '<form method="post" name="updateform' . $row[4] . '" action="index.php?p=configuratie">
							<tr><td><input class="inputfield" type="text" name="course_title" value="' . $row[0] . '" /></td>
								<td>' . $row[3] . ' door ' . $row[1] . ' ' . $row[2] . '</td><td><a class="save-link" title="veranderingen opslaan" onclick="document.updateform' . $row[4] . '.submit(); showLoading()"></a><a class="delete-link" title="verwijderen" onclick="set_remove_msg(\'' . $row[0] . '\',' . $row[4] . ');"></a></td>
							</tr>';
				?>
				<tr><td colspan="4"><b>XML-feed url (XBEL format): </b><input class="inputfield" onkeyup="checkEmpty()" type="text" name="course_xml" id="course_xml" value="<?php if (!preg_match("/^xml\/xml.txt/", $row[5]))
				echo $row[5]; ?>" /></td></tr>
				<tr><td colspan="4" id="course_xml_paste" <?php if (!preg_match("/^xml\/xml.txt/", $row[5]))
				echo 'style="display:none"'; ?>>of<br /><b>XML-feed plain text (XBEL format): </b><textarea name="course_xml_paste" style="border: 1px  #99d1f1 solid " cols="110" rows="30"><?php if (preg_match("/^xml\/xml.txt/", $row[5]))
				echo htmlspecialchars(file_get_contents($row[5])); ?></textarea></td>
					<td colspan="4" id="course_xml_paste_hidden_msg" <?php if (preg_match("/^xml\/xml.txt/", $row[5]))
					echo 'style="display:none"'; ?>>Maak het xml url import veld leeg voor de mogelijkheid om xml als plain text te importeren</td>				
				</tr>				
				<?php
				print '</td>
							</tr>
							<input type="hidden" name="course_id" value="' . $row[4] . '" /><input type="hidden" name="course_action" value="update" />
						</form>';
			}
		}
		// form submit button is a link to easily preserve layout
		?>
		<tr><th colspan="4">Nieuw vak toevoegen</th></tr>
		<?php
		if (mysql_num_rows($result) == 0) {
			?>
			<form method="post" name="createform" action="index.php?p=configuratie"><tr>
					<td><b>Titel: </b><input class="inputfield" type="text" name="course_title" id="course_title" /></td></tr>
				<tr><td><b>XML-feed url (XBEL format): </b><input class="inputfield" onkeyup="checkEmpty()" type="text" name="course_xml" id="course_xml" /></td></tr>
				<tr><td colspan="4" id="course_xml_paste">of<br /><b>XML-feed plain text (XBEL format): </b><textarea name="course_xml_paste" style="border: 1px  #99d1f1 solid " cols="110" rows="30"></textarea></td>
					<td colspan="4" id="course_xml_paste_hidden_msg" style="display:none">Maak het xml url import veld leeg voor de mogelijkheid om xml als plain text te importeren</td>
				</tr>				

				<tr>

					<td colspan="2"><a class="button" onclick="document.createform.submit(); showLoading()">Maak Vak</a></td>
				</tr><input type="hidden" name="course_action" value="create" /></form>
			<?php
		} else {
			echo "<tr><td colspan=4>De huidige architectuur van de website laat maar 1 vak toe.<br /> Het is daarom niet toegestaan meerdere vakken toe te voegen.<br /> De architectuur is echter zo gemaakt dat dit gemakkelijk aangepast kan worden voor meerdere vakken</td></tr>";
		}
		?>
	</table>
	<?php
} else {
	echo '<div class="warning">U hebt niet de juiste bevoegdheden om deze pagina te bezoeken.</div>';
}
?>
