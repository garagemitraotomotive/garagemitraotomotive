DROP TABLE IF EXISTS `ads`;

CREATE TABLE `ads` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `adcode` varchar(255) NOT NULL DEFAULT '',
  `adby` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `blocked`;

CREATE TABLE `blocked` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `blog`;

CREATE TABLE `blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(102) NOT NULL,
  `time` varchar(10) NOT NULL,
  `category` varchar(15) NOT NULL DEFAULT '1',
  `tag` varchar(200) NOT NULL,
  `allow_comment` varchar(3) NOT NULL,
  `draft` varchar(1) NOT NULL DEFAULT '0',
  `private` varchar(1) NOT NULL DEFAULT '0',
  `count` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `blog_id` text NOT NULL,
  `link` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `chat`;

CREATE TABLE `chat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) NOT NULL,
  `text` text NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `chat_comment`;

CREATE TABLE `chat_comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `text` text NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `chat_like`;

CREATE TABLE `chat_like` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `comment`;

CREATE TABLE `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `blog_id` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `site` varchar(132) NOT NULL,
  `email` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `status` varchar(1) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `following`;

CREATE TABLE `following` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `url` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `subscribe` varchar(1) NOT NULL DEFAULT '1',
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `following_post`;

CREATE TABLE `following_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `link` varchar(255) NOT NULL,
  `read` text NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `forum`;

CREATE TABLE `forum` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` varchar(10) NOT NULL,
  `scid` varchar(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(10) NOT NULL,
  `perm` varchar(1) NOT NULL DEFAULT '0',
  `close` varchar(1) NOT NULL DEFAULT '0',
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8;




DROP TABLE IF EXISTS `forum_notify`;

CREATE TABLE `forum_notify` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` varchar(10) NOT NULL,
  `scid` varchar(10) NOT NULL,
  `tid` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `new` int(10) NOT NULL DEFAULT '0',
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `forum_post`;

CREATE TABLE `forum_post` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `cid` varchar(10) NOT NULL,
  `scid` varchar(10) NOT NULL,
  `tid` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `text` text NOT NULL,
  `quote` varchar(15) NOT NULL,
  `file` varchar(80) NOT NULL,
  `file_hits` int(10) NOT NULL DEFAULT '0',
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `forum_setting`;

CREATE TABLE `forum_setting` (
  `site` int(10) NOT NULL AUTO_INCREMENT,
  `create_topic` varchar(1) NOT NULL DEFAULT '1',
  `allow_guest` varchar(1) NOT NULL DEFAULT '1',
  `allow_upload` varchar(1) NOT NULL DEFAULT '1',
  `anti_flood` int(3) NOT NULL DEFAULT '60',
  PRIMARY KEY (`site`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `guestbook`;

CREATE TABLE `guestbook` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `site` varchar(70) NOT NULL,
  `text` text NOT NULL,
  `status` varchar(1) NOT NULL,
  `time` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `kb`;

CREATE TABLE `kb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `tag` varchar(200) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `kuis`;

CREATE TABLE `kuis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bandar` int(15) NOT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban` varchar(200) NOT NULL,
  `hadiah` int(10) NOT NULL,
  `success` varchar(300) NOT NULL,
  `error` varchar(300) NOT NULL,
  `status` int(1) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `kuis_jawaban`;

CREATE TABLE `kuis_jawaban` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kuis_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `text` tinytext NOT NULL,
  `win` int(1) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `kupon_reg`;

CREATE TABLE `kupon_reg` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` int(10) NOT NULL,
  `email` varchar(60) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `navigation`;

CREATE TABLE `navigation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `code` text NOT NULL,
  `place` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `pm`;

CREATE TABLE `pm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `receiver_id` varchar(15) NOT NULL,
  `sender_id` varchar(15) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(250) NOT NULL,
  `text` text NOT NULL,
  `read` varchar(1) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `replace_text`;

CREATE TABLE `replace_text` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `site`;

CREATE TABLE `site` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `url_www` varchar(255) NOT NULL,
  `description` varchar(200) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `meta_google` varchar(255) NOT NULL,
  `language` varchar(3) NOT NULL DEFAULT 'id',
  `theme` varchar(50) NOT NULL,
  `theme_web` varchar(50) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `favicon` varchar(50) NOT NULL,
  `cat_loc` varchar(6) NOT NULL DEFAULT 'top',
  `display_following` varchar(1) NOT NULL,
  `display_count` varchar(1) NOT NULL,
  `comment_email` varchar(255) NOT NULL,
  `comment_mod` varchar(1) NOT NULL,
  `comment_captcha` varchar(1) NOT NULL,
  `num_post_main` varchar(3) NOT NULL DEFAULT '10',
  `desc_post_main` varchar(1) NOT NULL DEFAULT '0',
  `allow_reg` varchar(1) NOT NULL,
  `reg_email` varchar(1) NOT NULL,
  `reg_author` varchar(1) NOT NULL,
  `gmt` varchar(10) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'lainnya',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `sponsor`;

CREATE TABLE `sponsor` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `show` int(10) NOT NULL DEFAULT '0',
  `click` int(10) NOT NULL DEFAULT '0',
  `expired` int(10) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `stats`;

CREATE TABLE `stats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `total` varchar(10) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `subscribe`;

CREATE TABLE `subscribe` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sub` varchar(62) NOT NULL,
  `status` varchar(1) NOT NULL,
  `code` varchar(15) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `cookie` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `birthday` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `no_hp` varchar(50) NOT NULL DEFAULT '',
  `language` varchar(3) NOT NULL DEFAULT 'id',
  `about` text NOT NULL,
  `site` varchar(255) NOT NULL,
  `admin` varchar(1) NOT NULL DEFAULT '0',
  `author` varchar(1) NOT NULL DEFAULT '0',
  `credit` int(10) NOT NULL DEFAULT '5000',
  `ban` varchar(1) NOT NULL DEFAULT '0',
  `confirm` varchar(38) NOT NULL DEFAULT '0',
  `ip_proxy` varchar(15) NOT NULL,
  `ip_browser` varchar(15) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `lastdate` int(11) NOT NULL DEFAULT '0',
  `date_reg` varchar(10) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `place` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;