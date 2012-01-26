<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" version="XHTML+RDFa 1.0" dir="ltr"> <!-- <- wat doet rdf in de doctype, daar doen we toch niks mee? overigens moeten we sowieso nog de html5 doctype -->
	<?php
	// LEARNSCAPE UVA AMSTERDAM //

	session_start();
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1); 

	require("include/config.php");
	require("include/functions.php");

	if (isset($_GET['p'])) {
		$page = $_GET['p'];
	} else {
		$page = 'index_main';
	}

	$page = "pages/{$page}.php";
	?>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="" />
        <meta name="description" content="" />
		<link rel="shortcut icon" href="<?php echo BASE; ?>images/favicon.ico" type="image/ico" />
		<title><?php echo TITLE; ?></title>
		<link type="text/css" rel="stylesheet" media="all" href="<?php echo BASE; ?>style.css" />
		<script src="<?php echo BASE; ?>js/tabs.js" type="text/javascript"></script>
		<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
			<script src="http://vjs.zencdn.net/c/video.js"></script>
			<?php if (isset($uvanetid) && $user_type == 'Docent') { ?><script src="<?php echo BASE; ?>js/teacher_funcs.js" type="text/javascript"></script><?php } ?>
	</head>
	<body onload="init()">
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
					if (isset($uvanetid)) {
						echo "Je bent ingelogd  als <a href='" . BASE . "profiel&user=" . $uvanetid . "'><b>" . $name . "</b></a>";
						if ($user_type == 'Docent') {
							// Teacher options
							echo " <a class='button' href='" . BASE . "configuratie'>Configuratie</a>";
						} else {
							// Student options
						}
						?>
						<a class="button" href="<?php echo BASE; ?>?logout=true">Uitloggen</a>
						<?php
					} else {
						echo "Log in met je UvAnetID: ";
						echo '<a class="button" href="https://secure.uva.nl/cas/login?service=http://websec.science.uva.nl' . BASE . 'index.php">Inloggen</a>'; //todo: laat deze url automatisch opgevraagd worden zodat het ook op een ander domein werkt
					}
					?>

				</div>
				<ul id="nav">
					<?php
					require("include/menu.php");
					?>
				</ul>
			</div>

			<div id="banner">
				<div class="transbox">
					<div id="titletrans">Uitgelicht: <?php if (isset($focus_user_firstn)) {
						echo $focus_user_firstn . ' ' . $focus_user_lastn;
					} ?></div>
<?php echo $focus_user_firstn; ?> heeft al 100% van de cursus voltooid en is onze beste student ooit! <br />
					Zo'n buitengewoon knappe, sterke, heldhaftige, slimme en leuke jongen als hij kom je niet vaak tegen. Hij is ieders grootste idool!
				</div>
			</div>

			<div id="content">
				<div id="messages"></div>
				<?php
				if (file_exists($page)) {
					require_once($page);
				} else {
					require_once('pages/error.php');
				}
				?>
			</div>

			<div id="footer">
				&copy; <?php echo date("Y") . " " . TITLE . " - " . SLOGAN . " -"; ?>
				<a href="<?php echo BASE; ?>over">over ons</a> | <a href="<?php echo BASE; ?>faq">veel gestelde vragen</a> | <a href="<?php echo BASE; ?>contact">contact</a> | <a href="<?php echo BASE; ?>disclaimer">disclaimer</a> | 
				<div class="clearfix"></div>
			</div>
		</div>
	</body>
</html>