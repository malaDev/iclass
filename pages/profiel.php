<h1>Profiel</h1><br />
<?php
if (isset($_GET['user'])) {
    $uvanetid_profile = $_GET['user'];
    $query = "SELECT * FROM users WHERE uvanetid = $uvanetid_profile";
	$result = mysql_query($query);
    if (mysql_num_rows($result) == 0){
        echo "<div class='error'>Gebruiker niet gevonden</div>";
    } else {
	$user_profile = mysql_fetch_array($result);
	$name_profile = $user_profile['firstname']." ".$user_profile['lastname'];
    $type = $user_profile['type'];
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
    </table>
    <?php
    }
} else {
    echo "<div class='error'>Gebruiker niet gevonden</div>";
}
?>