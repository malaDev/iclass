<script type="text/javascript">	
	//AJAX code
    function deleteSection(id)
	{
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById('sections').innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/deleteSection.php?folder="+id,true);
		xmlhttp.send();
	}
	
	//AJAX code
    function addSection(title, weight)
	{
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById('sections').innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/addSection.php?title="+title+"&weight="+weight,true);
		xmlhttp.send();
	}
	function swapSections(cur_id, prev_id)
	{
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById('sections').innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/swapSections.php?curid="+cur_id+"&previd="+prev_id,true);
		xmlhttp.send();
	}
</script>
<h2>Welkom
	<?php
	//checks if user is currently logged in.
	//stores the id from the user if logged in.
	if (isset($uvanetid)) {
		$useridresult = mysql_query("SELECT id FROM users WHERE uvanetid = '$uvanetid'");
		$userid = mysql_fetch_array($useridresult);
		$id = $userid['id'];
		echo "<a href='" . BASE . "index.php?p=profiel&user=" . $uvanetid . "'><b>" . $name . "</b></a></h2>";
	} else {
		//page shown if not logged in.
		?>
		Gast</h2>
	<div class='warning'><b>Niet ingelogd</b></div>
	<div class='info'>Om je <b>voortgang</b> bij te houden moet je ingelogd zijn.<br /><br />Log rechtsboven in met je UvAnetID.</div>

	<div class='info'>Om de <b>comment sectie</b> te zien met vragen/opmerkingen, antwoorden en materiaal van andere studenten/docenten of om zelf berichten en materiaal te plaatsten moet je ook ingelogd zijn.</div>
	<?php
}
if (isset($uvanetid)) {
echo '<div id="sections">';
require 'process/getSections.php';
echo '</div>';
}
?>