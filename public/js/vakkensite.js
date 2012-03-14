function toggleVideo()
{
	if($('#video').is(':visible'))
	{
		$('#player').get(0).pause();
		$('#player').get(0).controls = false;
	}
	else
	{
		$('#videos').addClass('black');
	}
	$('#video').slideToggle(200, function()
	{
		if(!$('#video').is(':visible'))
		{
			$('#videos').removeClass('black');
		}
		else
		{
			$('#player').get(0).controls = true;
		}
	});
	return false;
}

function showVideo(name)
{
	if(!$('#video').is(':visible'))
	{
		toggleVideo();
	}
	$('#player').attr('src', name);
	$('#player').get(0).play();
	return false;
}

function editpage()
{
	if(!$('#markdown-editor').is(':visible'))
	{
		$('#markdown-editor').animate({
			opacity: 'show', 
			width: '600px'
		}, 200, function() {
			$('#markdown-editor-area').focus();
		});
	}
	else
	{
		$('#markdown-editor').animate({
			opacity: 'hide', 
			width: '0px'
		}, 200);
	}
}


//Done/not Done checkbox (AJAX)
function change_progress(id, folderid, done)
{
	var xmlhttp;
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
			document.getElementById("done-progress").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","/page/change_progress?id="+id+"&fid="+folderid+"&done="+done,true);
	xmlhttp.send();
}

$(document).ready(function(){
	$('#comments textarea').autosize();    
});

