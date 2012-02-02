<?php 

function course_id($course_name){
  // course_name (=unique too) to course_id
	$result = mysql_query("SELECT course_id FROM courses WHERE course_name = '".$course_name."'");
	while ($row = mysql_fetch_row($result)) {
		return $row[0]; //if there is any
	}
	return false;
}

function sub_xml($xml,$parent,$course_dbname,&$updatecount,$cur_weight){
  //xml parser
  $folderweight=0; //Weight of folders in current folder
  $itemweight=0; //Weight of items in current folder
  foreach($xml as $type => $content){
    // Because PHP simplexml is such a strange class this is for
	// now the most efficient way I see to address a sub entity.
	// The first entry is the title anyway.
    if($type=='title'){
	  $title = mysql_real_escape_string($content[0]);
	  break;
	}
  }
  if(!isset($title)){$title='NULL';}
  
  $eval_row = mysql_query("SELECT folder_id FROM {$course_dbname}_folders WHERE parent={$parent} AND title='{$title}'");  
  $row = mysql_fetch_array($eval_row);
  if(isset($row[0])){
	$cur_folder=$row[0];
	// just update the weight 
	mysql_query("UPDATE {$course_dbname}_folders SET weight={$cur_weight} WHERE folder_id={$cur_folder}");
  }else{
    // insert folder
	mysql_query("INSERT INTO {$course_dbname}_folders (weight,parent,title)
				 VALUES ({$cur_weight},{$parent},'{$title}')");
	$updatecount['folders']++;
	$cur_folder=mysql_insert_id();
  }	
  
  foreach($xml as $type => $content){
	// these have different types (folder content)
	if($type=='folder'){
		$row = mysql_fetch_array($eval_row);
		$folderweight++;
		// recursion
		sub_xml($content,$cur_folder,$course_dbname,$updatecount,$folderweight);
	}else{
		$item_type=0;
		$itemweight++;
		if($type=='bookmark'){
			$item_type=1;
			$innerhtml = mysql_real_escape_string($content->children());
			$attr = $content->attributes();
		}else if($type=='desc'){
			$item_type=2;
			$innerhtml = mysql_real_escape_string($content);
			$attr = 'NULL';
		}
		
		if($item_type){
			// update/insert
			$eval_row = mysql_query("SELECT elem_id FROM {$course_dbname}_items WHERE folder={$cur_folder} AND type={$item_type} AND innerhtml='{$innerhtml}'");
			$row = mysql_fetch_array($eval_row);
			if(isset($row[0])){
				mysql_query("UPDATE {$course_dbname}_items SET weight={$itemweight} WHERE elem_id={$row[0]}");
			}else{
				mysql_query("INSERT INTO {$course_dbname}_items (weight,folder,type,innerhtml,attr)
							VALUES ({$itemweight},{$cur_folder},{$item_type},'{$innerhtml}','{$attr}')");
				$updatecount['items']++;
			}
		}

	}
	
  }
  
}

function create_course($course_name,$xml,$action,$teacher_id,$course_id){
$xmltree = simplexml_load_file($xml);
$updatecount=Array('folders'=>0,'items'=>0);
if($action=='update'){
  //update-action
  if($course_id>0){
    $course_dbname="course__".$course_id;
	mysql_query("UPDATE courses SET course_name='{$course_name}', update_date=NOW(), teacher_id={$teacher_id}, xml_feed='{$xml}' WHERE course_id={$course_id}");
	parse_xml($xmltree,$course_dbname,$updatecount);
  }else{
    echo <<<SCRIPT
<script type="text/javascript">document.getElementById("messages").innerHTML = "<p>Cursus bestaat niet!</p>";</script>
SCRIPT;
  }
}else{
  //create-action
  $course_id=course_id($course_name);
  if(is_numeric($course_id)){
    echo <<<SCRIPT
<script type="text/javascript">document.getElementById("messages").innerHTML = "<p>Cursus met deze naam bestaat al!</p>";</script>
SCRIPT;
  }else{
  mysql_query("INSERT INTO courses (course_name,teacher_id,xml_feed) VALUES ('{$course_name}',{$teacher_id},'{$xml}')");
  $course_dbname="course__".mysql_insert_id();
  mysql_query("CREATE TABLE {$course_dbname}_items(
	elem_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(elem_id),
	weight INT,
	folder INT,
	type BINARY,
	innerhtml TEXT,
	attr VARCHAR(200))"); 
  mysql_query("CREATE TABLE {$course_dbname}_folders(
	folder_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(folder_id),
	weight INT,
	parent INT,
	title VARCHAR(200))");
	}	
  parse_xml($xmltree,$course_dbname,$updatecount);
}

}

function parse_xml($xmltree,$course_dbname,$updatecount){
sub_xml($xmltree,0,$course_dbname,$updatecount,0);
echo <<<SCRIPT
<script type="text/javascript">
document.getElementById("messages").innerHTML = "<p><b>Nieuwe mappen:</b> {$updatecount['folders']}<br /> <b>Nieuwe items:</b> {$updatecount['items']}</p>";
</script>
SCRIPT;
}

?>