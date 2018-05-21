CREATE TABLE `tadnews_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `col_name` varchar(255) NOT NULL default '',
  `col_sn` mediumint(9) unsigned NOT NULL default 0,
  `sort` smallint(5) unsigned NOT NULL default 0,
  `kind` enum('img','file') NOT NULL default 'img',
  `file_name` varchar(255) NOT NULL default '',
  `file_type` varchar(255) NOT NULL default '',
  `file_size` int(10) unsigned NOT NULL default 0,
  `description` text NOT NULL,
  `counter` mediumint(8) unsigned NOT NULL default 0,
  `original_filename` varchar(255) NOT NULL,
  `hash_filename` varchar(255) NOT NULL,
  `sub_dir` varchar(255) NOT NULL,
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM;



CREATE TABLE `tad_news` (
  `nsn` smallint(5) unsigned NOT NULL auto_increment,
  `ncsn` smallint(5) unsigned NOT NULL default 0,
  `news_title` varchar(255) NOT NULL default '',
  `news_content` longtext NOT NULL,
  `start_day` datetime NOT NULL,
  `end_day` datetime default NULL,
  `enable` enum('1','0') NOT NULL default '1',
  `uid` mediumint(8) unsigned NOT NULL default 0,
  `passwd` varchar(255) NOT NULL default '',
  `enable_group` varchar(255) NOT NULL default '',
  `counter` smallint(5) unsigned NOT NULL default 0,
  `prefix_tag` varchar(255) NOT NULL default '',
  `always_top` enum('0','1') NOT NULL default '0',
  `always_top_date` datetime NOT NULL,
  `have_read_group` varchar(255) NOT NULL default '',
  `page_sort` SMALLINT(5) UNSIGNED NOT NULL default 0,
  PRIMARY KEY  (`nsn`)
) ENGINE=MyISAM;



CREATE TABLE `tad_news_cate` (
  `ncsn` smallint(5) unsigned NOT NULL auto_increment,
  `of_ncsn` smallint(5) unsigned NOT NULL default 0,
  `nc_title` varchar(255) NOT NULL default '',
  `enable_group` varchar(255) NOT NULL default '',
  `enable_post_group` varchar(255) NOT NULL default '',
  `sort` smallint(5) unsigned NOT NULL default 0,
  `cate_pic` varchar(255) NOT NULL default '',
  `not_news` enum('0','1') NOT NULL,
  `setup` TEXT NOT NULL,
  PRIMARY KEY  (`ncsn`)
) ENGINE=MyISAM;



CREATE TABLE `tad_news_paper` (
  `npsn` smallint(5) unsigned NOT NULL auto_increment,
  `nps_sn` mediumint(8) unsigned NOT NULL default 0,
  `number` smallint(5) unsigned NOT NULL default 0,
  `np_title` varchar(255)  NOT NULL default '',
  `nsn_array` text NOT NULL,
  `np_content` text NOT NULL,
  `np_date` datetime NOT NULL,
  PRIMARY KEY  (`npsn`)
) ENGINE=MyISAM;


CREATE TABLE `tad_news_paper_email` (
  `nps_sn` smallint(6) NOT NULL default 0,
  `email` varchar(100) NOT NULL default '',
  `order_date` datetime NOT NULL,
  PRIMARY KEY  (`nps_sn`,`email`)
) ENGINE=MyISAM;


CREATE TABLE `tad_news_paper_setup` (
  `nps_sn` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `head` text NOT NULL,
  `foot` text NOT NULL,
  `themes` varchar(255) NOT NULL default '',
  `status` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`nps_sn`)
) ENGINE=MyISAM;


CREATE TABLE `tad_news_sign` (
  `sign_sn` mediumint(8) UNSIGNED NOT NULL auto_increment,
  `nsn` SMALLINT UNSIGNED NOT NULL  default 0,
  `uid` mediumint(8) UNSIGNED NOT NULL  default 0,
  `sign_time` DATETIME NOT NULL,
  PRIMARY KEY  (`sign_sn`)
) ENGINE=MyISAM;

CREATE TABLE `tad_news_paper_send_log` (
  `npsn` smallint(5) unsigned NOT NULL default 0,
  `email` varchar(100) NOT NULL default '',
  `send_time` datetime NOT NULL,
  `log` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`npsn`,`email`)
) ENGINE=MyISAM;


CREATE TABLE `tad_news_tags` (
  `tag_sn` smallint(5) UNSIGNED NOT NULL auto_increment,
  `tag` varchar(255) NOT NULL default '',
  `font_color` varchar(255) NOT NULL default '',
  `color` varchar(255) NOT NULL default '',
  `enable` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`tag_sn`)
) ENGINE=MyISAM;

INSERT INTO `tad_news_tags` (`tag_sn`, `tag`, `font_color`, `color`, `enable`) VALUES
(1, '公告', 'white', 'blue', '1'),
(2, '緊急', 'white', 'red', '1'),
(3, '調查', 'white', '#993333', '1'),
(4, '活動', 'white', '#99CC33', '1'),
(5, '注意', 'white', '#999900', '1'),
(6, '重要', 'white', '#0066CC', '1');


CREATE TABLE `tadnews_rank` (
  `col_name` varchar(100) NOT NULL default '',
  `col_sn` smallint(5) unsigned NOT NULL default 0,
  `rank` tinyint(3) unsigned NOT NULL default 0,
  `uid` mediumint(8) unsigned NOT NULL default 0,
  `rank_date` datetime NOT NULL,
  PRIMARY KEY (`col_name`,`col_sn`,`uid`)
) ENGINE=MyISAM;