<!-- This page is the base of the comment section -->

<script type="text/javascript">
	//AJAX code to expand a comment
    function expandComment(id, uvanetid)
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
				document.getElementById(id).innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/expandComment.php?q="+id+"&u="+uvanetid,true);
		xmlhttp.send();

	}
	
	//Once AJAX loaded the information when a comment is expanded, this functions is used to expand/hide a comment
	function showHideComment(id)
	{
		var comment = document.getElementById( id );
		var expandComment = document.getElementById("expand"+id);
		
		if (comment.style.display == "block"){
			comment.style.display = "none";
			expandComment.style.display = "block";
		}else{
			comment.style.display = "block";
			expandComment.style.display = "none";
		}
	}

	// this function is called when a new reply is posted (AJAX)
	function getReply(id, body, uvanetid)
	{
		if (body=="")
		{
			document.getElementById("0"+id).innerHTML="";
			return;
		} 
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
				document.getElementById("0"+id).innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/newReply.php?q="+id+"&b="+body+"&u="+uvanetid,true);
		xmlhttp.send();
		document.getElementById("body_reply"+id).value="";
	}
	
	// this function is called when a new comment is posted (AJAX)
	function getComment(title, file, body, uvanetid, folderid)
	{
		document.getElementById("errorTitle").innerHTML="";
			
		document.getElementById("errorBody").innerHTML="";
		if (title=="" || body=="")
		{
			if (title=="")
				document.getElementById("errorTitle").innerHTML="<div class='error'>Geen titel ingevuld!</div>";
			if (body=="")
				document.getElementById("errorBody").innerHTML="<div class='error'>Geen bericht ingevuld!</div>";
			return;
		} 
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
				document.getElementById("comments").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?php echo BASE; ?>process/newComment.php?t="+title+"&f="+file+"&b="+body+"&u="+uvanetid+"&fid="+folderid,true);
		xmlhttp.send();
		document.getElementById("mesComment").innerHTML="<div class='success'>Comment succesvol geplaatst!</div>";
		window.location.hash = '#scrollComment'; 
		setTimeout("document.getElementById('mesComment').innerHTML=''; document.getElementById('uploadSucces').innerHTML='';",3000);
		document.forms["form_comment"].reset();
	}
	
	// this function is called when a comment is deleted (AJAX)
	function delComment(id, uvanetid, folderid)
	{
		var confirm = window.confirm("Weet u zeker dat u dit bericht wilt verwijderen? Alle reacties op dit bericht worden dan ook verwijderd..");
		if (confirm){
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
					document.getElementById("comments").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","<?php echo BASE; ?>process/delComment.php?q="+id+"&u="+uvanetid+"&f="+folderid,true);
			xmlhttp.send();
		} else {
			return;
		}
	}
	
	// this function is called when a reply is deleted (AJAX)
	function delReply(comment_id, reply_id, uvanetid)
	{
		var confirm = window.confirm("Weet u zeker dat u deze reactie wilt verwijderen?");
		if (confirm){
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
					document.getElementById("0"+comment_id).innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","<?php echo BASE; ?>process/delReply.php?q="+comment_id+"&r="+reply_id+"&u="+uvanetid,true);
			xmlhttp.send();
		} else {
			return;
		}
	}

	// this function is called when a file is being uploaded (shows a loading bar)
	function startUpload(){
		document.getElementById('f1_upload_process').style.display = 'block';
		document.getElementById('f1_upload_form').style.display = 'none';
		return true;
	}
	
	// this function is called when a file is done uploading.
	// if everything went OK, call getComment() to show the new comment (AJAX)
	function stopUpload(melding, bestand, bestandOrgineel)
	{
		var result = '';
		if (melding == 0){
			document.getElementById('uploadSucces').innerHTML = '<div class="success">Het bestand '+bestandOrgineel+' is succesvol geupload!</div>';
		} else if (melding == 1){
			result = '<div class="error">Het bestand '+bestandOrgineel+' kan niet worden geupload omdat de extensie niet is toegestaan!</div>';
		}
		
		
		
		else if (melding == 2){
			result = '<div class="error">Het bestand '+bestandOrgineel+' mag niet groter zijn dan 8 mb!</div>';
		} else if (melding == 3){
			result = '';
		}
		else {
			result = '<div class="error">Er is iets misgegaan met uploaden</div>';
		}
		document.getElementById('f1_upload_process').style.display = 'none';
		document.getElementById('uploadError').innerHTML = result;
		document.getElementById('f1_upload_form').style.display = 'block';
		if (melding == 0 || melding == 3)
			getComment(document.getElementById('title_comment').value, bestand, document.getElementById('body_comment').value.replace(/\n/g,'[enter]'), '<?php echo $uvanetid; ?>', '<?php echo $folderid; ?>');
		return true;
	}
</script>

<!-- Title, is being scrolled to when a new comment is posted -->
<h2 class="crossline" id="scrollComment"><span>Comments</span></h2><br />

<!-- These div's show success/error messages when a comment (with file) is posted -->
<div id="mesComment"></div><div id="uploadSucces"></div>

<?php
/* if user is logged in */
if (isset($uvanetid)) {
	?>
	<div id="comments">
		<?php
		/* Get all the comments for this 'episode' */
		require 'process/getComment.php';
		?>
	</div>
	<br />
	<!-- These div's show error messages when a comment is posted in the wrong way-->
	<div id="errorTitle"></div><div id="errorBody"></div><div id="uploadError"></div>
	
	<!-- Form to post a new comment-->
	<div class="comments" id="form">
		<form action="<?php echo BASE; ?>process/upload.php" method="post" enctype="multipart/form-data" target="upload_target" id="form_comment" name="form_comment" onsubmit="startUpload();" >
			<label>Titel
				<span class="small">Korte omschrijving van comment</span>
			</label>
			<input class="input" type="text" id="title_comment" maxlength="127" name="title" />
			<br />
			<label>Bijlage
				<span class="small">Voeg bestand aan comment toe</span>
			</label>


			<span id="f1_upload_process" style="display: none">&nbsp; Laden...<br/><img src="<?php echo BASE; ?>images/loader.gif" /><br/></span>
			<span id="f1_upload_form"><br/>
				<input class="input" name="myfile" id="upload" type="file" />
			</span>                     
			<iframe id="upload_target" name="upload_target" src="#" style="display:none"></iframe>

			<br />
			<label>Body
				<span class="small">Bericht van je comment</span>
			</label>
			<textarea cols="50" rows="10" name="body" id="body_comment" ></textarea>

			<input type="submit" class="submit" name="submitComment" value="Plaats comment" />
			<input style="margin-right: 20px;" class="submit" type="reset" value="Reset">

		</form>
		<br /><br />
	</div>
	<div class="info">Als je een website url in je bericht zet wordt dit automatisch een klikbare link. 
		<br />Mocht deze website url een youtube video betreffen wordt deze video automatisch ge-embed onderaan je bericht
	</div>
	<?php
}
?>