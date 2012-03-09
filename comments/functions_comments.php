<?php
// find urls and make them clickable links
function make_links($text) {
	return preg_replace(
					array(
				'/(?(?=<a[^>]*>.+<\/a>)
             (?:<a[^>]*>.+<\/a>)
             |
             ([^="\']?)((?:https?|ftp|bf2|):\/\/[^<> \n\r]+)
         )/iex',
				'/<a([^>]*)target="?[^"\']+"?/i',
				'/<a([^>]+)>/i',
				'/(^|\s)(www.[^<> \n\r]+)/iex',
				'/(([_A-Za-z0-9-]+)(\\.[_A-Za-z0-9-]+)*@([A-Za-z0-9-]+)
       (\\.[A-Za-z0-9-]+)*)/iex'
					), array(
				"stripslashes((strlen('\\2')>0?'\\1<a href = \"\\2\" >\\2</a>\\3':'\\0'))",
				'<a\\1',
				'<a\\1 target="_blank" >',
				"stripslashes((strlen('\\2')>0?'\\1<a href = \"http://\\2\" >\\2</a>\\3':'\\0'))",
				"stripslashes((strlen('\\2')>0?'<a href = \"mailto:\\0\" >\\0</a>':'\\0'))"
					), $text
	);
}

// find youtube video urls and embed them at the bottom of the comment
function embed_youtube($text) {
	preg_match_all('#(http://www.youtube.com)?/(v/([-|~_0-9A-Za-z]+)|watch\?v\=([-|~_0-9A-Za-z]+)&?.*?)#i', $text, $output);
	foreach ($output[4] AS $video_id) {
		if (!isset($video[$video_id])) {
			$video[$video_id] = true;
			$embed_code = '<iframe width="450" height="259" src="http://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
			$text .= $embed_code;
		}
	}
	return $text;
}
?>
