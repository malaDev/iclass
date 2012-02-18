<?php
if (isset($uvanetid)) {
	if (isset($_GET['sort']))
		$sort = $_GET['sort'];
	else
		$sort = 'lastname';

	if (isset($_GET['flow']))
		if ($_GET['flow'] == "ASC")
			$flow = 'ASC';
		else if ($_GET['flow'] == "DESC")
			$flow = 'DESC';
		else
			$flow = 'ASC';
	else
		$flow = 'ASC';

	if ($sort == "lastname")
		if ($flow == "ASC")
			$flow1 = "DESC";
		else if ($flow == "DESC")
			$flow1 = "ASC";
		else
			$flow1 = 'ASC';
	else
		$flow2 = 'ASC';

	if ($sort == "firstname")
		if ($flow == "ASC")
			$flow2 = "DESC";
		else if ($flow == "DESC")
			$flow2 = "ASC";
		else
			$flow2 = 'ASC';
	else
		$flow2 = 'ASC';

	if ($sort == "uvanetid")
		if ($flow == "ASC")
			$flow3 = "DESC";
		else if ($flow == "DESC")
			$flow3 = "ASC";
		else
			$flow3 = 'ASC';
	else
		$flow3 = 'ASC';

	if ($sort == "type")
		if ($flow == "ASC")
			$flow4 = "DESC";
		else if ($flow == "DESC")
			$flow4 = "ASC";
		else
			$flow4 = 'ASC';
	else
		$flow4 = 'ASC';
	?>
	<table cellspacing="15px">
		<tr>
			<td><a href="index.php?p=users&sort=lastname&flow=<?php echo $flow1; ?>"><b>Lastname</b></a></td>
			<td><a href="index.php?p=users&sort=firstname&flow=<?php echo $flow2; ?>"><b>Firstname</b></a></td>
			<td><a href="index.php?p=users&sort=uvanetid&flow=<?php echo $flow3; ?>"><b>UvAnetID</b></a></td>
			<td><a href="index.php?p=users&sort=type&flow=<?php echo $flow4; ?>"><b>Type</b></a></td>
		</tr>
		<?php
		$query_users = "SELECT * FROM users ORDER BY $sort $flow";
		$result = mysql_query($query_users);
		while ($user = mysql_fetch_array($result)) {
			?>
			<tr>
				<td><a href="index.php?p=profiel&user=<?php echo $user['uvanetid']; ?>"><?php echo $user['lastname']; ?></a></td>
				<td><a href="index.php?p=profiel&user=<?php echo $user['uvanetid']; ?>"><?php echo $user['firstname']; ?></a></td>
				<td><a href="index.php?p=profiel&user=<?php echo $user['uvanetid']; ?>"><?php echo $user['uvanetid']; ?></a></td>
				<td><a href="index.php?p=profiel&user=<?php echo $user['uvanetid']; ?>"><?php echo $user['type']; ?></a></td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
} else {
	echo '<div class="error">Not authorized to view this page.</div>';
}
