<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" version="XHTML+RDFa 1.0" dir="ltr"> <!-- <- wat doet rdf in de doctype, daar doen we toch niks mee? overigens moeten we sowieso nog de html5 doctype -->
	<?php
	session_start();

	require("include/config.php");
	require("include/functions.php");

	if (isset($_GET['p'])) {
		$page = $_GET['p'];
	} else {
		$page = 'index_main';
	}

	function l($path, $name, $li=FALSE) {
		$path_ext = $path ? $path . '/' : '';
		$link = '<a href="' . BASE . $path_ext . '">' . $name . '</a>';
		if ($li) {
			$extra = $_GET['p'] == $path ? ' id="active"' : '';
			$link = "<li{$extra}>" . $link . "</li>";
		}
		print $link;
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
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="logo">
					<a href="<?php echo BASE; ?>"><img id="title" src="<?php echo BASE; ?>images/logo2.png" alt="logo"/></a>
					<div id="slogan">
						<?php echo SLOGAN; ?>
					</div>
				</div>
				<div id="user-bar">
					<?php
					if (isset($uvanetid)) {
						echo "Je bent ingelogd  als <a href='" . BASE . "profiel'><b>" . $name . "</b></a>";
						?>
						<a class="button" href="<?php echo BASE; ?>?logout=true">Uitloggen</a>
						<?php
					} else {
						echo "Log in met je UvAnetID: ";
						echo '<a class="button" href="https://bt-lap.ic.uva.nl/cas/login?service=http://websec.science.uva.nl' . BASE . 'index.php">Inloggen</a>'; //todo: laat deze url automatisch opgevraagd worden zodat het ook op een ander domein werkt
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
					<div id="titletrans">Transparante box</div>
					In deze leuke transparante box kunnen we nog wat leuks zetten. <br />
					Als we willen in ieder geval. <br />
					of natuurlijk weer weghalen als het niks is.
				</div>
			</div>

			<div id="content">
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