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

/* Include important constants and functions and processes */
require("include/config.php");
require("include/preprocess.php");
require("include/functions.php");
include("include/lookforavatar.php");

/* get the page to be included in the content */
if (isset($_GET['p'])) {
	$pagebase = $_GET['p'];
} else {
	$pagebase = 'index_main';
}
$page = "pages/{$pagebase}.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" version="XHTML+RDFa 1.0" dir="ltr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="" />
        <meta name="description" content="" />
		<meta name="google" value="notranslate">
			<link rel="shortcut icon" href="<?php echo BASE; ?>images/favicon.ico" type="image/ico" />
			<title><?php echo TITLE; ?></title>
			<link type="text/css" rel="stylesheet" media="all" href="<?php echo BASE; ?>style.css" />
			<!-- include javascript files for the jwplayer and for the tabs if page = content -->
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
						/* not logged in: show login button */
						echo "Log in met je UvAnetID: ";
						echo '<a class="button" href="https://bt-lap.ic.uva.nl/cas/login?service=' . urlencode('http://websec.science.uva.nl' . BASE . 'index.php') . '">Inloggen</a>';
					}
					?>
				</div>
				<!-- div between the userbar and the menu with progressbar and xml export button -->
				<div class="underUserbar">
				<?php
				/* Show progress bar if user is in database */
				if (isset($uvanetid) && $pagebase != 'nieuweUser') {
					echo '<span class="smallfont">Voortgang: </span>';
					progress($uvanetid, true);
				} else {
					echo "<div style='margin-right:210px'></div>"; 
				}
				?>
				<br /><br />
				<!-- xml export link -->
				<a href="<?php echo BASE; ?>xml"><img src="<?php echo BASE; ?>images/xml_icon.gif" alt="xml feed" /></a>
				</div>
				<!-- include navigation menu -->
				<ul id="nav">
					<?php
					require("include/menu.php");
					?>
				</ul>
			</div>

			<div id="banner">
				<!-- transparant box placed on the banner -->
				<div class="transbox">
					<?php
					require("include/uitgelicht.php");
					?>
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
			
			<!-- footer at bottom of the page -->
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