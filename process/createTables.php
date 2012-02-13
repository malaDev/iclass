<?php 

require("../include/config.php");
//require("../include/preprocess.php");
mysql_connect(DB_SERVER, DB_USER, DB_PASS);
mysql_select_db(DB_NAME);
//Insert tables according to database schema per table if it does not exist
mysql_query("
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17");

header("Location: ".BASE);

?>


