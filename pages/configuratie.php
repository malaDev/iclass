<h1>Configuratie</h1>
<?php 
if(isset($user_type)&&$user_type=='Docent'){

  if(isset($_POST['delete'])){
    mysql_query("DROP TABLE course__".$_POST['delete']."_folders, course__".$_POST['delete']."_items");
	mysql_query("DELETE FROM courses WHERE course_id=".$_POST['delete']);
	
  }

  if(isset($_POST['course_title'])&&isset($_POST['course_xml'])&&isset($_POST['course_action'])){
  require_once("include/course_xmlread.php");
  $course_id = isset($_POST['course_id']) ? $_POST['course_id']:0;
  create_course($_POST['course_title'],$_POST['course_xml'],$_POST['course_action'],$user_id,$course_id);  
  }

?>
<p>Hier kunt u vakken toevoegen, verwijderen of updaten.</p>
<h2>Aanwezige cursussen</h2>
<table id="course_overview">
<tr><th>Titel</th><th>XML-feed (XBEL format)</th><th>Laatst aangepast</th><th></th></tr>
<?php
$result = mysql_query("SELECT course_name, firstname, lastname, update_date, course_id, xml_feed FROM courses LEFT JOIN users ON courses.teacher_id = users.id ORDER BY courses.update_date DESC");
	while ($row = mysql_fetch_row($result)) {
		print '<form method="post" name="updateform'.$row[4].'" action="configuratie"><tr><td><input type="text" name="course_title" value="'.$row[0].'" /></td><td><input type="text" name="course_xml" value="'.$row[5].'" /></td><td>'.$row[3].' door '.$row[1].' '.$row[2]. '</td><td><a class="save-link" title="veranderingen opslaan" onclick="document.updateform'.$row[4].'.submit()"></a><a class="delete-link" title="verwijderen" onclick="set_remove_msg(\''.$row[0].'\','.$row[4].');"></a></td></tr><input type="hidden" name="course_id" value="'.$row[4].'" /><input type="hidden" name="course_action" value="update" /></form>';
	}
?>
<tr><th colspan="4">Nieuw vak toevoegen</th></tr>
<form method="post" name="createform" action="configuratie"><tr>
<td><input type="text" name="course_title" id="course_title" /></td>
<td><input type="text" name="course_xml" id="course_xml" /></td>
<td colspan="2"><a class="save-link" onclick="document.createform.submit();"></a></td>
</tr><input type="hidden" name="course_action" value="create" /></form>

</table>
<?php }else{ ?>

<p>U hebt niet de juiste bevoegdheden om deze pagina te bezoeken.</p>

<?php } ?>
