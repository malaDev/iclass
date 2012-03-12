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

function newComment(form)
{
    var formData = new FormData($(form)[0]);
    console.log(formData);

	$.ajax({
		type: "POST",
		url: "/comments/new",
		data: formData,
		contentType: false,
		processData: false,
		cache: false,
		dataType: "html",
		complete: function(data)
		{
			$("#comments_ajax").html(data.responseText);
			document.forms["form_comment"].reset();
		}
	});
	
	return false;
}
	
function delComment(uvanetid, folderid)
{
	$.ajax({
		type: "POST",
		url: "/comments/delete?q="+commentID+"&u="+uvanetid+"&f="+folderid,
		dataType: "html",
		complete: function(data)
		{
			$("#comments_ajax").html(data.responseText);
		}
	});
	
	return false;
}
	
function newReply(form, id, body)
{
	if (body=="")
		return;
	
	$.ajax({
		type: "POST",
		url: "/replies/new?q="+id+"&b="+body,
		data: $(form).serialize(),
		dataType: "html",
		complete: function(data)
		{
			$("#0"+id).html(data.responseText);
			$("#new-reply"+id).value = "";
		}
	});
	
	return false;
}

function delReply(uvanetid)
{
	$.ajax({
		type: "POST",
		url: "/replies/delete?q="+commentID+"&r="+replyID+"&u="+uvanetid,
		dataType: "html",
		complete: function(data)
		{
			$("#0"+commentID).html(data.responseText);
		}
	});
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