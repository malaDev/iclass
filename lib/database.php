<?php

date_default_timezone_set(TIMEZONE);

// if param given then just go ahead and create the database
if(isset($_GET['create_db']))
	create_database();
else
	check_database();

function check_database()
{
	/* Connect to database. Can't connect? show install1.php */
	if (!@mysql_connect(DB_SERVER, DB_USER, DB_PASS))
	{
		$case = 'database_connect';
		require("lib/install.php");
		die();
	}
	/* Select database */
	if (!@mysql_select_db(DB_NAME))
	{
		$case = 'database_select';
		require("lib/install.php");
		die();
	}
	
	// Set time names to dutch
	mysql_query("SET lc_time_names=nl_NL");
	
	/* Define required tables */
	$tables = mysql_query("SHOW TABLES FROM " . DB_NAME);
	$required = Array('comments', 'courses', 'progress', 'replies', 'users', 'items', 'folders');
	while ($table = mysql_fetch_row($tables)) {
	        $key = array_search($table[0], $required);
	        if (is_numeric($key)) {
	                unset($required[$key]);
	        }
	}
	
	/* Table is missing? show install.php */
	if (count($required) > 0) {
	        $case = 'database_tables';
	        require("lib/install.php");
	        die();
	}
}

function create_database()
{
	mysql_connect(DB_SERVER, DB_USER, DB_PASS);
	
	if (!@mysql_query("CREATE DATABASE IF NOT EXISTS ".DB_NAME)) {
		echo "Database kon niet worden gemaakt! Maak de database met de hand aan en vul de naam in van de database in het bestand include/config.php";
		die();
	}
	
	mysql_select_db(DB_NAME);
	//Insert tables according to database schema per table if it does not exist
	
	mysql_query("CREATE TABLE IF NOT EXISTS `comments` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) NOT NULL,
	  `public` BOOL,
	  `folder_id` int(11) NOT NULL,
	  `file` varchar(128) NOT NULL,
	  `body` text NOT NULL,
	  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `latest_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=289");
	
	mysql_query("CREATE TABLE IF NOT EXISTS `courses` (
	  `course_id` int(11) NOT NULL AUTO_INCREMENT,
	  `course_name` varchar(100) NOT NULL,
	  `teacher_id` int(11) NOT NULL,
	  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `xml_feed` varchar(200) NOT NULL,
	  UNIQUE KEY `course_id` (`course_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117");
	
	mysql_query("CREATE TABLE IF NOT EXISTS `progress` (
	  `id` int(11) NOT NULL,
	  `folder_id` int(11) NOT NULL,
	  KEY `user_id` (`id`,`folder_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1");
	
	mysql_query("CREATE TABLE IF NOT EXISTS `replies` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) NOT NULL,
	  `comment_id` int(11) NOT NULL,
	  `body` text NOT NULL,
	  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=209");
	
	mysql_query("CREATE TABLE IF NOT EXISTS `users` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `uvanetid` varchar(15) NOT NULL,
	  `type` varchar(7) NOT NULL,
	  `firstname` varchar(18) NOT NULL,
	  `lastname` varchar(18) NOT NULL,
	  `email` varchar(100) NOT NULL,
	  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=17");

	mysql_query("CREATE TABLE IF NOT EXISTS items (
		elem_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(elem_id),
		weight INT,
		folder INT,
		type BINARY,
		innerhtml TEXT,
		attr VARCHAR(200)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		
	mysql_query("CREATE TABLE IF NOT EXISTS folders (
		folder_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(folder_id),
		weight INT,
		parent INT,
		markdown TEXT,
		title VARCHAR(200)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1");
	
	//echo "Database was created.";
}
