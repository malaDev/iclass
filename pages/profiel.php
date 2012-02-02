<h1>Profiel</h1><br />
<?php
/* Show profile (if uvanetid is in database) */
if (isset($_GET['user'])) {
    $uvanetid_profile = mysql_real_escape_string($_GET['user']);
	/* Get info of user*/
	$userResult = mysql_query("SELECT * FROM users WHERE uvanetid = '$uvanetid_profile'");
	/* uvanetid not in database? error */
    if (mysql_num_rows($userResult) == 0) {
        echo "<div class='error'>Gebruiker niet gevonden</div>";
    } else {
	$user_profile = mysql_fetch_array($userResult);
	$name_profile = $user_profile['firstname']." ".$user_profile['lastname'];
    $type = $user_profile['type'];
	$userid = $user_profile['id'];
    ?>
    <table>
        <tr>
            <td>
                <h3>Naam: </h3>
            </td>
            <td>
                <?php echo $name_profile; ?>
            </td>
        </tr>
        <tr>
            <td>
                <h3>UvAnetID: </h3>
            </td>
            <td>
                <?php echo $uvanetid_profile; ?>
            </td>
        </tr>
        <tr>
            <td>
                <h3>Type: </h3>
            </td>
            <td>
                <?php echo $type; ?>
            </td>
        </tr>
		<tr>
            <td>
                <h3>Avatar: </h3>
            </td>
            <td>
               <img src='<?php echo lookforavatar($uvanetid_profile, ""); ?>' width="100" height="100" alt="avatar" /> 
			</td>
        </tr>
		<tr>
			<td>
				<h3>Voortgang: </h3>
			</td>
			<td><?php progress($uvanetid_profile, true); ?></td>
		</tr>
    </table>
    <?php
    }
/* uvanetid not found? error */
} else {
    echo "<div class='error'>Gebruiker niet gevonden</div>";
}
?>