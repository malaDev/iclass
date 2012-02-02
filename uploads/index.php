<?php
/* LEARNSCAPE UVA AMSTERDAM
 * 
 * Authors:
 *          Hidde Hensel
 *          Laurens Verspeek
 *          Ruben Janssen
 *          Diederik Beker
 *          Merijn van Wouden
 */

require("include/config.php");
require("include/preprocess.php");
require("include/functions.php");

/* get the page to be included */
if (isset($_GET['p'])) {
	$pagebase = $_GET['p'];
} else {
	$pagebase = 'index_main';
}
$page = "pages/{$pagebase}.php";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" version="XHTML+RDFa 1.0" dir="ltr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="" />
        <meta name="description" content="" />
		<meta name="google" value="notranslate">
			<link rel="shortcut icon" href="<?php echo BASE; ?>images/favicon.ico" type="image/ico" />
			<title><?php echo TITLE; ?></title>
			<link type="text/css" rel="stylesheet" media="all" href="<?php echo BASE; ?>style.css" />
			<?php if ($pagebase == 'content') { ?><script type="text/javascript" src="<?php echo BASE; ?>js/tabs.js"></script>
				<script type="text/javascript" src="<?php echo BASE; ?>js/jwplayer.js"></script><?php } ?>
			<?php
			/* Check if current user is in the list of admin's */
			/* Better: use array_search and not check isset($uvanetid) every iteration */
			foreach ($admin_users as $admin_user) {
				if (isset($uvanetid) && $uvanetid == $admin_user) {
					?>
					<script src="<?php echo BASE; ?>js/teacher_funcs.js" type="text/javascript"></script>
					<?php
					break;
				}
			}
			?>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="logo">
					<a href="<?php echo BASE; ?>"><img src="<?php echo BASE; ?>images/logo2.png" alt="logo"/></a>
					<div id="slogan">
						<?php echo SLOGAN; ?>
					</div>
				</div>
				<div id="user-bar">
					<?php
					/* Check if user is logged in */
					if (isset($uvanetid)) {
						echo "Je bent ingelogd  als <a href='" . BASE . "profiel&user=" . $uvanetid . "'><b>" . $name . "</b></a>";
						if ($pagebase != 'nieuweUser') {
							/* Check if current user is in the list of admin's */
							foreach ($admin_users as $admin_user) {
								if ($uvanetid == $admin_user) {
									/* Admin options */
									echo " <a class='button' href='" . BASE . "configuratie'>Configuratie</a>";
									break;
								}
							}
						}
						?>
						<a class="button" href="<?php echo BASE; ?>settings">Settings</a>
						<a class="button" href="<?php echo BASE; ?>?logout=true">Uitloggen</a>
						<?php
					} else {
						echo "Log in met je UvAnetID: ";
						echo '<a class="button" href="https://bt-lap.ic.uva.nl/cas/login?service=' . urlencode('http://websec.science.uva.nl' . BASE . 'index.php') . '">Inloggen</a>';
					}
					?>
				</div>
				<div class="underUserbar">
				<?php
				if (isset($uvanetid) && $pagebase != 'nieuweUser') {
					echo '<span class="smallfont">Voortgang: </span>';
					progress($uvanetid, true);
				} else {
					echo "<div style='margin-right:210px'></div>"; 
				}
				?>
				<br /><br />
				<a href="<?php echo BASE; ?>xml"><img src="<?php echo BASE; ?>images/xml_icon.gif" alt="xml feed" /></a>
				</div>
				<ul id="nav">
					<?php
					require("include/menu.php");
					?>
				</ul>
			</div>

			<div id="banner">
				<div class="transbox">
					<div id="titletrans">Uitgelicht: <?php
					if (isset($focus_user_firstn)) {
						echo $focus_user_firstn . ' ' . $focus_user_lastn;
					}
					?></div>
						<?php echo $focus_user_firstn; ?> heeft al 70% van de cursus voltooid en is dus al aardig opweg. <br />
					Hier komt nog beetje tekst en avatar
				</div>
			</div>

			<div id="content">
				<?php
				/* Content gets included here */
				if (file_exists($page)) {
					require_once($page);
				} else {
					require_once('pages/error.php');
				}
				?>
			</div>

			<div id="footer">
				&copy; <?php echo date("Y") . " " . TITLE . " - " . SLOGAN . " -"; ?>
				<a href="<?php echo BASE; ?>over">over ons</a> |
				<a href="<?php echo BASE; ?>faq">veel gestelde vragen</a> |
				<a href="<?php echo BASE; ?>contact">contact</a> |
				<a href="<?php echo BASE; ?>disclaimer">disclaimer</a>
				<div class="clearfix"></div>
			</div>
		</div>
	</body>
</html>