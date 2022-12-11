CREATE TABLE `db_5m_default_text` (
  `id` bigint(20) NOT NULL auto_increment,
  `kennung` varchar(45) default NULL,
  `subkenn` varchar(45) default NULL,
  `inhalt` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
CREATE TABLE `db_5m_extra_content` (
  `id` bigint(20) NOT NULL auto_increment,
  `contype` smallint(6) NOT NULL default '0',
  `titel` varchar(1000) default NULL,
  `bild` varchar(500) default NULL,
  `lkey` varchar(300) default NULL,
  `inhalt` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
CREATE TABLE `db_5m_conf_data` (
  `id` bigint(20) NOT NULL auto_increment,
  `kennung` varchar(45) default NULL,
  `eintrag` varchar(45) default NULL,
  `wert` varchar(2000) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
CREATE TABLE `db_5m_images` (
  `id` bigint(20) NOT NULL auto_increment,
  `datei` varchar(500) default NULL,
  `mintr` varchar(500) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
CREATE TABLE `db_5m_images_banner` (
  `id` bigint(20) NOT NULL auto_increment,
  `datei` varchar(500) default NULL,
  `mintr` varchar(500) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
CREATE TABLE `db_5m_files` (
  `id` bigint(20) NOT NULL auto_increment,
  `datei` varchar(500) default NULL,
  `ordner` varchar(500) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
CREATE TABLE `db_5m_news` (
  `id` bigint(20) NOT NULL auto_increment,
  `datum` date default NULL,
  `titel` varchar(1000) default NULL,
  `inhalt` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
CREATE TABLE `db_5m_smart_menu` (
  `id` bigint(20) NOT NULL auto_increment,
  `item_label` varchar(300) default NULL,
  `item_code` varchar(1000) default NULL,
  `item_code_type` smallint(6) NOT NULL default '0',
  `item_tooltip` varchar(3000) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
CREATE TABLE `db_5m_smart_menu_links` (
  `id` bigint(20) NOT NULL auto_increment,
  `menu_id` bigint(20) NOT NULL default NULL,
  `item_label` varchar(300) default NULL,
  `item_code` varchar(1000) default NULL,
  `item_code_type` smallint(6) NOT NULL default '0',
  `item_tooltip` varchar(3000) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
