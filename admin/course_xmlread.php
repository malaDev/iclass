<?php 

global $message;

// xml parser
function sub_xml($xml,$parent,&$updatecount,$cur_weight)
{
	$folderweight = 0; //Weight of folders in current folder
	$itemweight = 0;   //Weight of items in current folder
	$title = $xml->title; if(!isset($title)) $title='NULL';

	// find this item and either update position or add it if missing
	if($eval_row = mysql_query("SELECT folder_id FROM folders WHERE parent={$parent} AND title='{$title}'") and
		$row = mysql_fetch_array($eval_row))
	{
		if(isset($row[0]))
		{
			$cur_folder = $row[0];
			// just update the weight 
			mysql_query("UPDATE folders SET weight={$cur_weight} WHERE folder_id={$cur_folder}");
		}
	}
	else
	{
		// insert folder
		mysql_query("INSERT INTO folders (weight,parent,title) VALUES ({$cur_weight},{$parent},'{$title}')");
		$updatecount['folders']++;
		$cur_folder = mysql_insert_id();
	}	

	// combine title and description to default text
	// desc has to be cast from CDATA to string
	// TODO desc is not well-formed yet as it may contain superfluous spaces disrupting the markdown
	$markdown = '# ' . $xml->title . '\n\n' . (string)$xml->desc;
	mysql_query("UPDATE folders SET markdown='{$markdown}' WHERE folder_id={$cur_folder}");

	foreach($xml as $type => $content)
	{
		// these have different types (folder content)
		if($type=='folder')
		{
			//$row = mysql_fetch_array($eval_row);
			$folderweight++;
			// recursion
			sub_xml($content, $cur_folder, $updatecount, $folderweight);
		}
		else
		{
			$item_type = 0;
			$itemweight++;
			if($type == 'bookmark')
			{
				$item_type=1;
				$innerhtml = mysql_real_escape_string($content->children());
				$attr = $content->attributes();
			}
			else if($type == 'desc')
			{
				$item_type=2;
				$innerhtml = mysql_real_escape_string($content);
				$attr = 'NULL';
			}

			if($item_type)
			{
				// update/insert
				$eval_row = mysql_query("SELECT elem_id FROM items WHERE folder={$cur_folder} AND type={$item_type} AND innerhtml='{$innerhtml}'");
				$row = mysql_fetch_array($eval_row);
				if(isset($row[0]))
				{
					mysql_query("UPDATE items SET weight={$itemweight} WHERE elem_id={$row[0]}");
				}
				else
				{
					mysql_query("INSERT INTO items (weight,folder,type,innerhtml,attr) VALUES ({$itemweight},{$cur_folder},{$item_type},'{$innerhtml}','{$attr}')");
					$updatecount['items']++;
				}
			}

		}

	}

}

function create_course($course_name, $xml, $paste, $action, $teacher_id, $course_id)
{
	global $message;
		
	$real_xml = false;
	if (!$paste)
	{
		if (@$xmltree = simplexml_load_file($xml, 'SimpleXMLElement', LIBXML_NOCDATA))
			$real_xml = true;
	}
	else
	{
		if (@$xmltree = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA))
			$real_xml = true;
		$fh = fopen('assets/xml/xml.txt', 'w') or die("can't create xml file");
		fwrite($fh, $xml);
		fclose($fh);
		$xml = "assets/xml/xml.txt";

	}
	
	$updatecount = array('folders' => 0, 'items' => 0);
	if($action == 'update')
	{
		//update-action
		if($course_id > 0){
			mysql_query("UPDATE courses SET course_name='{$course_name}', update_date=NOW(), teacher_id={$teacher_id}, xml_feed='{$xml}' WHERE course_id={$course_id}");
			if ($real_xml)
				parse_xml($xmltree, $updatecount);
			else
				$message = "<div class='alert alert-warning'><a class='close' data-dismiss='alert'>&times;</a><p>no xml file given..</p></div>";	  
		}
		else
		{
			$message = "<div class='alert alert-error'><a class='close' data-dismiss='alert'>&times;</a><p>Cursus bestaat niet!</p></div>";
		}
	}
	else
	{
		mysql_query("INSERT INTO courses (course_name,teacher_id,xml_feed) VALUES ('{$course_name}',{$teacher_id},'{$xml}')");
		if ($real_xml)
			parse_xml($xmltree,$updatecount);
		else
			$message = "<div class='alert alert-error'><a class='close' data-dismiss='alert'>&times;</a><p>xml file niet gevonden!</p></div>";
	}

}

function parse_xml($xmltree,$updatecount)
{
	global $message;
	sub_xml($xmltree, 0, $updatecount, 0);
	$message = "<div class='alert alert-success'><a class='close' data-dismiss='alert'>&times;</a><b>Nieuwe mappen:</b> {$updatecount['folders']}<br /> <b>Nieuwe items:</b> {$updatecount['items']}</div>";
}
