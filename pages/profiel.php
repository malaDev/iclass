<?php
if (isset($uvanetid)) {
    ?>
    <h1>Profiel</h1><br />
    <table>
        <tr>
            <td>
                <h3>Naam: </h3>
            </td>
            <td>
                <?php echo $name; ?>
            </td>
        </tr>
        <tr>
            <td>
                <h3>UvAnetID: </h3>
            </td>
            <td>
                <?php echo $uvanetid; ?>
            </td>
        </tr>
    </table>
    <?php
} else {
    echo "U bent niet ingelogd";
}
?>