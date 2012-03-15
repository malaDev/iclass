//AJAX code to expand a comment
//var commentID = ''; 
//var replyID = ''; 
//var folderid = ''; 

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
			$("#posts").html(data.responseText);
			$("#form_comment")[0].reset();
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
			$("#posts").html(data.responseText);
		}
	});
	
	return false;
}
	
function newReply(form, comment_id)
{
/*
	if (body=="")
		return;
*/
	
	$.ajax({
		type: "POST",
		url: "/replies/new",
		data: $(form).serialize(),
		dataType: "html",
		complete: function(data)
		{
			$("#replies0"+comment_id).html(data.responseText);
			$("#reply"+comment_id)[0].reset();
		}
	});
	
	return false;
}

function delReply(reply_id, comment_id)
{
	$.ajax({
		type: "POST",
		url: "/replies/delete",
		data: { comment_id: comment_id, reply_id: reply_id },
		dataType: "html",
		complete: function(data)
		{
			$("#replies0"+comment_id).html(data.responseText);
		}
	});
}
