//AJAX code to expand a comment
var commentID = ''; 
var replyID = ''; 
var folderid = ''; 
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
function newReply(id, body)
{
	if (body=="")
		return;
	
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
	xmlhttp.open("GET","../course/ajax/new_reply.php?q="+id+"&b="+body,true);
	xmlhttp.send();
	document.getElementById("new-reply"+id).value="";
}
	
// this function is called when a new comment is posted (AJAX)
function newComment(file, body, folderid)
{
	$.post(
		'/comments/new', $("#form_comment").serialize(), function() {
			alert('hi');
			document.getElementById("message_comment").innerHTML = 
			"<div class='alert alert-success'><a class='close' data-dismiss='alert'>&times;</a>Comment succesvol geplaatst!</div>";
			document.forms["form_comment"].reset();
		}
	);
}
	
// this function is called when a comment is deleted (AJAX)
function delComment(uvanetid, folderid)
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
				document.getElementById("comments_ajax").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","../course/ajax/delete_comment.php?q="+commentID+"&u="+uvanetid+"&f="+folderid,true);
		xmlhttp.send();
}
	
// this function is called when a reply is deleted (AJAX)
function delReply(uvanetid)
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
				document.getElementById("0"+commentID).innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","../course/ajax/delete_reply.php?q="+commentID+"&r="+replyID+"&u="+uvanetid,true);
		xmlhttp.send();
}

// this function is called when a file is being uploaded (shows a loading bar)
function startUpload(){
	document.getElementById('upload-loading').style.display = 'block';
	document.getElementById('submit').style.display = 'none';
	return true;
}
	
// this function is called when a file is done uploading.
// if everything went OK, call getComment() to show the new comment (AJAX)
function stopUpload(melding, bestand, bestandOrgineel)
{
	var result = '';
	if (melding == 0){
		result = '<div class="alert alert-success"><a class="close" data-dismiss="alert">&times;</a>Het bestand '+bestandOrgineel+' is succesvol geupload!</div>';
	} else if (melding == 1){
		result = '<div class="alert alert-error"><a class="close" data-dismiss="alert">&times;</a>Het bestand '+bestandOrgineel+' kan niet worden geupload omdat de extensie niet is toegestaan!</div>';
	} else if (melding == 2){
		result = '<div class="alert alert-error"><a class="close" data-dismiss="alert">&times;</a>Het bestand '+bestandOrgineel+' mag niet groter zijn dan 8 mb!</div>';
	} else if (melding == 3){
		result = '';
	}
	else {
		result = '<div class="alert alert-error"><a class="close" data-dismiss="alert">&times;</a>Er is iets misgegaan met uploaden</div>';
	}
	document.getElementById('upload-loading').style.display = 'none';
	document.getElementById('message_upload').innerHTML = result;
	document.getElementById('submit').style.display = 'block';
	if (melding == 0 || melding == 3)
		newComment(bestand, document.getElementById('new-comment').value, folderid);
	return true;
}