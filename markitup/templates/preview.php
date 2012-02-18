<?php
// No cache headers for AJAX
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require("../../include/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>markItUp, markdown preview</title>
				<link rel="stylesheet" title="Default" href="<?php echo BASE; ?>highlight/styles/vs.css" />
		<script type="text/javascript" src="<?php echo BASE; ?>highlight/highlight.pack.js"></script>
		<script type="text/javascript">
			hljs.initHighlightingOnLoad();
		</script>
		<link href="<?php echo BASE; ?>css/markdown.css" rel="stylesheet"></link>
	</head>
	<body>
		<?php
		include_once "../../include/markdown.php";
		echo '<div id="markdownInfo">';
		$html = Markdown($_POST['data']);
		echo $html;
		echo '</div>';
		?>
	</body>
</html>


