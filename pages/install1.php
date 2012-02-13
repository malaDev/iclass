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
</style>
</head>
<body>
<div class="vertcenter">
<div class="content"><img src="images/logo2.png" alt="logo" />
<h2>Installatie: stap 1 van 2</h2>
<div class="text">
<p>Deze website is nog niet juist geconfigureerd. Open het bestand in /include/config.php en vul daar de constanten in zoals aangegeven in de comments in het bestand.</p>
<p>Als u deze stap denkt te hebben voltooid, laad dan deze pagina opnieuw. Wanneer ditzelfde bericht vervolgens nog steeds verschijnt, kan er nog steeds geen verbinding gemaakt worden met de database en is er iets mis. Probeer dan na te gaan of de gegevens die u heeft ingevuld wel correct zijn, zowel in config.php als in de database.</p>
<p>Error: <?= mysql_error() ?></p>
</div>
</div>
</div>
</body>
</html>
