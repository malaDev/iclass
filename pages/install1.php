<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" version="XHTML+RDFa 1.0" dir="ltr">
<head>
<!-- Due to unknown path location of css (pre-installation), the css is still in this document -->
<style type="text/css">
*{
  margin: 0;
  padding: 0;
}

body {
  background-color: #f4f4f4;
  font-family: Arial, Helvetica;
  font-size: 14px;
}

div.vertcenter{
  position:absolute;
  top:50%;
  height:1px;
  width: 100%;
  text-align: center;
}

div.content{
  clear:both;
  position: relative;
  top: -170px;
  background-color: #FFF;
  width: 600px;
  height: 300px;
  margin: auto;
  border: 1px solid #B3B4B4;
}

.text {
  width: 550px;
  margin: auto;
  text-align: center;
}

p{
  margin: 10px 0;
}
.button, .button:link, .button:visited, .button:active {
	font-weight: bold;
	padding: 5px;
	background-color: #efefef;
	border: 1px #dddddd solid;
	cursor: pointer;
}
.button:hover {
	background-color: #ff8300;
	color: white;
	border: 1px #ff8300 solid;
}
.center {
  text-align: center;
  margin-top: 20px;
}
a,a:link, a:visited, a:active {
	color: #d36c00;
	text-decoration: none;
}

a:hover {
	color: #ff8300;
}

h2 {
  font-size: 130%;
  color: #D36C00;
  margin: 15px 0;
}
</style>
</head>
<body>
<div class="vertcenter">
<div class="content">
<h2>Installatie:</h2>
<div class="text">
<?php
if ($case == 'database_connect'){
?>
<p><b>Deze website is nog niet juist geconfigureerd!</b></p><br />
<p>Er kan geen connectie met de database worden gemaakt.</p>
<p>Open het bestand in /include/config.php en vul daar de constanten in zoals aangegeven in de comments in het bestand.</p>
<p>Als u deze stap denkt te hebben voltooid, laad dan deze pagina opnieuw. Wanneer ditzelfde bericht vervolgens nog steeds verschijnt, kan er nog steeds geen verbinding gemaakt worden met de database en is er iets mis. Probeer dan na te gaan of de gegevens die u heeft ingevuld wel correct zijn, zowel in config.php als in de database.</p>
<?php
} else if ($case == 'database_select') {
?>
<p><b>Deze website is nog niet juist geconfigureerd!</b></p><br />
<p>Er is wel connectie met de database, maar de database '<?php echo DB_NAME; ?>' bestaat niet.</p>
<p>Open het bestand in /include/config.php en vul daar de juiste naam in van de database, of maak database '<?php echo DB_NAME; ?>' inclusief bijbehorende tabellen door op onderstaande knop te klikken:</p>
<div class="center"><a class="button" href="process/createDatabase.php">Maak Database</a></div>
<?php
} else if ($case == 'database_tables'){
?>
<p>Er kan verbinding worden gemaakt met de database. De database is echter nog leeg of anderszins nog incompleet en moet dus worden aangevuld tot alle benodigde tabellen aanwezig zijn. De volgende tabellen missen op dit moment:</p>
<b><?php 
foreach($required as $key => $table){
  echo $table.'<br />';
}
?></b>
<p>Deze tabellen kunnen automatisch aangemaakt worden, wanneer u op onderstaande knop drukt. U wordt vervolgens automatisch doorverwezen naar de dan werkende site.</p>
<div class="center"><a class="button" href="process/createDatabase.php">Maak Tabellen</a></div>
<?php
} else if ($case == 'base'){
?>
<p><b>Deze website is nog niet juist geconfigureerd!</b></p><br />
<p>De base van deze website is niet correct ingesteld. Als uw website in de root staat, vul dan als BASE in config.php alleen een '/' in. <br />Staat uw website in een submap vul dan '/naamvansubmap/' in.</p>
<p>De base is op dit moment: <?php echo BASE; ?>.<br />Suggestie voor correcte base: <br /><b><?php echo strstr($_SERVER['SCRIPT_NAME'], 'index', true); ?></b></p>
<?php
}
?>
</div>
</div>
</div>
</body>
</html>