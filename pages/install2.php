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
  height: 340px;
  margin: auto;
  border: 1px solid #B3B4B4;
}

.content img {
  margin-top: 30px;
}

.text {
  width: 550px;
  margin: auto;
  text-align: left;
}

p{
  margin: 5px 0;
}

h2 {
  font-size: 130%;
  color: #D36C00;
  margin: 15px 0;
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

ul {
  margin-left: 30px;
  color: #D36C00;
}
</style>
</head>
<body>
<div class="vertcenter">
<div class="content"><img src="images/logo2.png" alt="logo" />
<h2>Installatie: stap 2 van 2</h2>
<div class="text">
<p>Er kan verbinding worden gemaakt met de database. De database is echter nog leeg of anderszins nog incompleet en moet dus worden aangevuld tot alle benodigde tabellen aanwezig zijn. De volgende tabellen missen op dit moment:</p>
<ul><?php 
foreach($required as $key => $table){
  echo '<li>'.$table.'</li>';
}
?></ul>
<p>Deze tabellen kunnen automatisch aangemaakt worden, wanneer u op onderstaande knop drukt. U wordt vervolgens automatisch doorverwezen naar de dan werkende site.</p>
<div class="center"><a class="button" href="process/createTables.php">maak tabellen</a></div>
</div.
</div>
</div>
</body>
</html>