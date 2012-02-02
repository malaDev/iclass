function set_remove_msg(course_name,course_id) {
	document.getElementById("messages").innerHTML = "<div class='warning'><b>Weet u zeker dat u cursus <span class='messages_course_name'>&lsquo;"+course_name+"&rsquo;</span> wilt verwijderen?</b><br />Wanneer u bevestigt dat u deze cursus wilt verwijderen, zal de tabel die bij deze cursus hoort uit de database verwijderd worden en daarmee verliezen de studenten ook de opgeslagen informatie of ze bepaalde cursusinhoud al wel of nog niet hebben bekeken. Wanneer u de cursus vervolgens her-inlaadt is deze dat dus nog steeds weg.<br /><br /><a class='button' onclick='document.deleteform.submit();'>verwijderen</a> <a class='button' onclick='clear_messages();'>annuleren</a><form name='deleteform' method='POST' action='configuratie'><input type='hidden' name='delete' value='"+course_id+"' /></form></div>";
}

function clear_messages() {
	document.getElementById("messages").innerHTML = "";
}