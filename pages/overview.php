<script type="text/javascript">	
	//AJAX code
    function deleteEpisode(folderid, id)
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
				document.getElementById('episodes').innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/deleteEpisode.php?folder="+folderid+"&subfolder="+id,true);
		xmlhttp.send();
	}
	
	//AJAX code
    function addEpisode(folder, title, weight)
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
				document.getElementById('episodes').innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/addEpisode.php?folder="+folder+"&title="+title+"&weight="+weight,true);
		xmlhttp.send();
	}
	function swapEpisodes(folder, cur_id, prev_id)
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
				document.getElementById('episodes').innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/swapEpisodes.php?folder="+folder+"&curid="+cur_id+"&previd="+prev_id,true);
		xmlhttp.send();
	}
</script>
<?php
echo '<div id="episodes">';
require 'process/getEpisodes.php';
echo '</div>';
?>