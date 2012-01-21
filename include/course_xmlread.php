<?php 

// LEARNSCAPE UVA AMSTERDAM //

function dump_filecode(){
	$filen = end(explode('/',$_SERVER["SCRIPT_NAME"]));
	$content = (file($filen));
	echo '<div class="codebox"> <h2>Code current file:</h2>
	<div style="border:1px solid black;background: #CCC;height: 200px;overflow-y: scroll;">
	<pre style="color: #006600;font-family: Courier New, Courier;font-size: 13px;">'
	.htmlspecialchars(implode("",$content)).
	'</pre></div></div>';
}

class video_extract{
/*  The easy method would be to perform simplexml_load_file and then search for the
	videos in the object we receive, but that would mean a double deep iteration through
	the feed so a lot of overhead. Hence we do the is-video check directly during parsing */
	
	var $parser;

	function video_extract() 
	{
		$this->parser = xml_parser_create();
		$this->videos = Array();
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, "tag_open", "tag_close");
		xml_set_character_data_handler($this->parser, "cdata");
	}

	function parse($data){
		xml_parse($this->parser, $data);
	}

	function tag_open($parser, $tag, $attributes){
		// Video filename recognition 
		if($tag=='BOOKMARK'&&strpos($attributes['HREF'],'cs50.tv')){$this->videos[]=$attributes['HREF'];}
	}

	// Unused handlers
	function cdata($parser, $cdata){}
	function tag_close($parser, $tag){}

}



/* ------------------------------------------------- */
?>


<!DOCTYPE HTML> <!-- HTML5 doctype -->
<body>

<?php 
dump_filecode();

function html5_player($src, $src_fallback=""){
  // Print html5 video player for web format videos (mp4,webm,ogg)
  print "\n<video width='320' height='240' controls='controls'>\n";
  html5_video($src);
  if($src_fallback){html5_video($src_fallback);} // When fallback filetype is specified
  print "  Je browser ondersteunt geen HTML5.
Installeer een moderne browser, of <a href='".$src."'>download de video direct</a>.
</video>\n";
}

function html5_video($src){
  $ext = substr($src,strrpos($src,'.')+1); // For MIME type
  // Specific 'issue' for Harvard CS: videos are hosted on a different
  // root domain than specified in the xml but the rest of the URL
  // is correct. This replacement does the job but after a Harvard
  // site structure change this might become invalid.
  $src = str_replace('cs50.tv','cdn.cs50.net',$src);
  print "  <source src='{$src}' type='video/{$ext}' />\n";
}

//////////////////////

function course_exists($course_name){
	$result = mysql_query("SELECT * FROM courses WHERE course_name = '".$course_name."'");
	while ($row = mysql_fetch_row($result)) {
		return true; //if there is any
	}
	return false;
}

function dbname($course_name){
  $course_id = mysql_query("SELECT course_id FROM courses WHERE course_name='{$course_name}'");
  $row = mysql_fetch_array($course_id);
  return 'course_'.$row[0];
}

function top_xml($xml,$course_dbname,$action,&$weight,&$updatecount){
  $tab_counter=1;
  foreach($xml as $folder){
	// These are all type 'folder'
	sub_xml($folder,$tab_counter,'',$course_dbname,$action,$weight,$updatecount);
	$tab_counter++;
  }
}

function sub_xml($xml,$tab_counter,$path,$course_dbname,$action,&$weight,&$updatecount){
  // Code assumes title comes before any other element. if another xml format is inserted and there is a bug, check this
  foreach($xml as $type => $content){
	// These have different types
	switch($type){
		case 'folder':
			sub_xml($content,$tab_counter,$path,$course_dbname,$action,$weight,$updatecount);
			break;
		case 'bookmark':
			$attrs = $content->attributes();$attrs = $attrs[0];
			$chldrn = mysql_real_escape_string($content->children());
			if($action=='create'){
				echo '<span style="background:lightgreen;">'.$tab_counter.' 1 '.$path.$chldrn.$attrs[0].'</span><br />';
				mysql_query("INSERT INTO {$course_dbname} (weight,tabcount,type,path,innerhtml,attr)
				VALUES ({$weight},{$tab_counter},1,'{$path}','{$chldrn}','{$attrs}')");
				$weight+=100;
				$updatecount++;
			}else{
				$eval_row = mysql_query("SELECT weight FROM {$course_dbname} WHERE tabcount={$tab_counter} AND type=1 AND path='{$path}' AND attr='{$attrs}'");
				// Did not include innerhtml in the condition here because when only a link's innerhtml would change we should'nt update (because we would
				// get duplicate links)
				$row = mysql_fetch_array($eval_row);
				
				if(isset($row[0])){
				  $weight=$row[0];
				}else{
				  $weight++;
				  mysql_query("INSERT INTO {$course_dbname} (weight,tabcount,type,path,innerhtml,attr)
				  VALUES ({$weight},{$tab_counter},1,'{$path}','{$chldrn}','{$attrs}')");
				  $updatecount++;
				}
				$x = isset($row[0])?'green':'red';
				echo '<div style="background:'.$x.';">'.$weight.$chldrn.'</div>';
			}
			break;
		case 'title':
			$path.=mysql_real_escape_string($content[0]).'/';
			break;
		case 'desc':
			$content = mysql_real_escape_string($content);
			if($action=='create'){
				echo '<span style="background:lightgreen;">'.$tab_counter.' 2 '.$path.$content.'</span><br />';
				mysql_query("INSERT INTO {$course_dbname} (weight,tabcount,type,path,innerhtml)
				VALUES ({$weight},{$tab_counter},2,'{$path}','{$content}')");
				$weight+=100;
				$updatecount++;
			}else{
				$eval_row = mysql_query("SELECT weight FROM {$course_dbname} WHERE tabcount={$tab_counter} AND type=2 AND path='{$path}'");
				// Max 1 desc per folder so no innerhtml checking required
				$row = mysql_fetch_array($eval_row);
				if(isset($row[0])){
				  $weight=$row[0];
				}else{
				  $weight++;
				  mysql_query("INSERT INTO {$course_dbname} (weight,tabcount,type,path,innerhtml)
				  VALUES ({$weight},{$tab_counter},2,'{$path}','{$content}')");
				  $updatecount++;
				}				
				$x = isset($row[0])?'lightblue':'red';
				echo '<div style="background:'.$x.';">'.$weight.$content.'</div>';
			}
			break;
	}
  }
}


// Connect to database
$link = mysql_connect('localhost', 'webdb1233', 're2uq8sa') or die('there was an error with connecting'); // Add mysql_error() to see the error. Not called for safety reasons.
mysql_select_db("webdb1233", $link);

$course_name='test';
$xml = simplexml_load_file("http://websec.science.uva.nl/webdb1233/include/cs50heel.xml");//http://cs50.tv/2007/fall/?output=xbel

$weight=100;
$updatecount=0;
if(course_exists($course_name)){
  echo "UPDATE-ACTION<br />";
  $course_dbname=dbname($course_name);
  top_xml($xml,$course_dbname,'update',$weight,$updatecount);
}else{
  echo "CREATE-ACTION<br />";
  mysql_query("INSERT INTO courses (course_name,teacher_id) VALUES ('{$course_name}', 0)");
  $course_dbname=dbname($course_name);
  mysql_query("CREATE TABLE {$course_dbname}(
	elem_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(elem_id),
	weight INT,
	tabcount INT,
	type BINARY,
	path VARCHAR(200),
	innerhtml TEXT,
	attr VARCHAR(200))"); 
  top_xml($xml,$course_dbname,'create',$weight,$updatecount);
}
echo 'NEW ROWS: '.$updatecount;

mysql_close($link);

?>

</body>
</html>