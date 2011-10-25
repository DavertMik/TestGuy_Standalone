/*
SQLyog Ultimate v8.55 
MySQL - 5.1.40-community : Database - livestreet
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `blog` */

CREATE TABLE `blog` (
  `blog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_owner_id` int(11) unsigned NOT NULL,
  `blog_title` varchar(200) NOT NULL,
  `blog_description` text NOT NULL,
  `blog_type` enum('personal','open','invite','close') DEFAULT 'personal',
  `blog_date_add` datetime NOT NULL,
  `blog_date_edit` datetime DEFAULT NULL,
  `blog_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `blog_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `blog_count_user` int(11) unsigned NOT NULL DEFAULT '0',
  `blog_limit_rating_topic` float(9,3) NOT NULL DEFAULT '0.000',
  `blog_url` varchar(200) DEFAULT NULL,
  `blog_avatar` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `user_owner_id` (`user_owner_id`),
  KEY `blog_type` (`blog_type`),
  KEY `blog_url` (`blog_url`),
  KEY `blog_title` (`blog_title`),
  CONSTRAINT `blog_fk` FOREIGN KEY (`user_owner_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `blog` */

insert  into `blog`(`blog_id`,`user_owner_id`,`blog_title`,`blog_description`,`blog_type`,`blog_date_add`,`blog_date_edit`,`blog_rating`,`blog_count_vote`,`blog_count_user`,`blog_limit_rating_topic`,`blog_url`,`blog_avatar`) values (1,1,'Блог им. admin','This is your personal blog.','personal','2011-08-28 00:00:00',NULL,0.000,0,0,-1000.000,NULL,'0'),(2,2,'Блог им. user','Это ваш персональный блог.','personal','2011-10-03 17:42:36',NULL,0.000,0,0,-1000.000,NULL,NULL);

/*Table structure for table `blog_user` */

CREATE TABLE `blog_user` (
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `user_role` int(3) DEFAULT '1',
  UNIQUE KEY `blog_id_user_id_uniq` (`blog_id`,`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `blog_user_fk` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `blog_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blog_user` */

/*Table structure for table `city` */

CREATE TABLE `city` (
  `city_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_name` varchar(30) NOT NULL,
  PRIMARY KEY (`city_id`),
  KEY `city_name` (`city_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `city` */

/*Table structure for table `city_user` */

CREATE TABLE `city_user` (
  `city_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `city_id` (`city_id`),
  CONSTRAINT `city_user_fk` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `city_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `city_user` */

/*Table structure for table `comment` */

CREATE TABLE `comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment_pid` int(11) unsigned DEFAULT NULL,
  `comment_left` int(11) NOT NULL DEFAULT '0',
  `comment_right` int(11) NOT NULL DEFAULT '0',
  `comment_level` int(11) NOT NULL DEFAULT '0',
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` enum('topic','talk') NOT NULL DEFAULT 'topic',
  `target_parent_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL,
  `comment_text` text NOT NULL,
  `comment_text_hash` varchar(32) NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_user_ip` varchar(20) NOT NULL,
  `comment_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `comment_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_delete` tinyint(4) NOT NULL DEFAULT '0',
  `comment_publish` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`comment_id`),
  KEY `comment_pid` (`comment_pid`),
  KEY `type_date_rating` (`target_type`,`comment_date`,`comment_rating`),
  KEY `id_type` (`target_id`,`target_type`),
  KEY `type_delete_publish` (`target_type`,`comment_delete`,`comment_publish`),
  KEY `user_type` (`user_id`,`target_type`),
  KEY `target_parent_id` (`target_parent_id`),
  KEY `comment_left` (`comment_left`),
  KEY `comment_right` (`comment_right`),
  KEY `comment_level` (`comment_level`),
  CONSTRAINT `topic_comment_fk` FOREIGN KEY (`comment_pid`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `topic_comment_fk1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `comment` */

/*Table structure for table `comment_online` */

CREATE TABLE `comment_online` (
  `comment_online_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` enum('topic','talk') NOT NULL DEFAULT 'topic',
  `target_parent_id` int(11) NOT NULL DEFAULT '0',
  `comment_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`comment_online_id`),
  UNIQUE KEY `id_type` (`target_id`,`target_type`),
  KEY `comment_id` (`comment_id`),
  KEY `type_parent` (`target_type`,`target_parent_id`),
  CONSTRAINT `topic_comment_online_fk1` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `comment_online` */

/*Table structure for table `country` */

CREATE TABLE `country` (
  `country_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_name` varchar(30) NOT NULL,
  PRIMARY KEY (`country_id`),
  KEY `country_name` (`country_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `country` */

/*Table structure for table `country_user` */

CREATE TABLE `country_user` (
  `country_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `country_user_fk` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `country_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `country_user` */

/*Table structure for table `favourite` */

CREATE TABLE `favourite` (
  `user_id` int(11) unsigned NOT NULL,
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` enum('topic','comment','talk') DEFAULT 'topic',
  `target_publish` tinyint(1) DEFAULT '1',
  UNIQUE KEY `user_id_target_id_type` (`user_id`,`target_id`,`target_type`),
  KEY `target_publish` (`target_publish`),
  KEY `id_type` (`target_id`,`target_type`),
  CONSTRAINT `favourite_target_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `favourite` */

/*Table structure for table `friend` */

CREATE TABLE `friend` (
  `user_from` int(11) unsigned NOT NULL DEFAULT '0',
  `user_to` int(11) unsigned NOT NULL DEFAULT '0',
  `status_from` int(4) NOT NULL,
  `status_to` int(4) NOT NULL,
  PRIMARY KEY (`user_from`,`user_to`),
  KEY `user_to` (`user_to`),
  CONSTRAINT `friend_from_fk` FOREIGN KEY (`user_from`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `friend_to_fk` FOREIGN KEY (`user_to`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `friend` */

/*Table structure for table `invite` */

CREATE TABLE `invite` (
  `invite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invite_code` varchar(32) NOT NULL,
  `user_from_id` int(11) unsigned NOT NULL,
  `user_to_id` int(11) unsigned DEFAULT NULL,
  `invite_date_add` datetime NOT NULL,
  `invite_date_used` datetime DEFAULT NULL,
  `invite_used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`invite_id`),
  UNIQUE KEY `invite_code` (`invite_code`),
  KEY `user_from_id` (`user_from_id`),
  KEY `user_to_id` (`user_to_id`),
  KEY `invite_date_add` (`invite_date_add`),
  CONSTRAINT `invite_fk` FOREIGN KEY (`user_from_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invite_fk1` FOREIGN KEY (`user_to_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `invite` */

/*Table structure for table `notify_task` */

CREATE TABLE `notify_task` (
  `notify_task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(30) DEFAULT NULL,
  `user_mail` varchar(50) DEFAULT NULL,
  `notify_subject` varchar(200) DEFAULT NULL,
  `notify_text` text,
  `date_created` datetime DEFAULT NULL,
  `notify_task_status` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`notify_task_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `notify_task` */

/*Table structure for table `reminder` */

CREATE TABLE `reminder` (
  `reminder_code` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `reminder_date_add` datetime NOT NULL,
  `reminder_date_used` datetime DEFAULT '0000-00-00 00:00:00',
  `reminder_date_expire` datetime NOT NULL,
  `reminde_is_used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`reminder_code`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `reminder_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `reminder` */

/*Table structure for table `session` */

CREATE TABLE `session` (
  `session_key` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `session_ip_create` varchar(15) NOT NULL,
  `session_ip_last` varchar(15) NOT NULL,
  `session_date_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `session_date_last` datetime NOT NULL,
  PRIMARY KEY (`session_key`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `session_date_last` (`session_date_last`),
  CONSTRAINT `session_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `session` */

insert  into `session`(`session_key`,`user_id`,`session_ip_create`,`session_ip_last`,`session_date_create`,`session_date_last`) values ('64aeb0593b896a778b9032504c1a25a6',2,'127.0.0.1','127.0.0.1','2011-10-26 01:19:53','2011-10-26 01:19:53');

/*Table structure for table `stream_event` */

CREATE TABLE `stream_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(100) NOT NULL,
  `target_id` int(11) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `publish` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `event_type` (`event_type`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `publish` (`publish`),
  KEY `target_id` (`target_id`),
  CONSTRAINT `stream_event_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `stream_event` */

insert  into `stream_event`(`id`,`event_type`,`target_id`,`user_id`,`date_added`,`publish`) values (1,'add_topic',1,2,'2011-10-03 19:01:03',1),(2,'add_topic',2,2,'2011-10-03 19:13:27',1),(3,'add_topic',3,2,'2011-10-03 21:54:33',1);

/*Table structure for table `stream_subscribe` */

CREATE TABLE `stream_subscribe` (
  `user_id` int(11) unsigned NOT NULL,
  `target_user_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`,`target_user_id`),
  CONSTRAINT `stream_subscribe_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `stream_subscribe` */

/*Table structure for table `stream_user_type` */

CREATE TABLE `stream_user_type` (
  `user_id` int(11) unsigned NOT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  KEY `user_id` (`user_id`,`event_type`),
  CONSTRAINT `stream_user_type_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `stream_user_type` */

/*Table structure for table `talk` */

CREATE TABLE `talk` (
  `talk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `talk_title` varchar(200) NOT NULL,
  `talk_text` text NOT NULL,
  `talk_date` datetime NOT NULL,
  `talk_date_last` datetime NOT NULL,
  `talk_user_ip` varchar(20) NOT NULL,
  `talk_count_comment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`talk_id`),
  KEY `user_id` (`user_id`),
  KEY `talk_title` (`talk_title`),
  KEY `talk_date` (`talk_date`),
  KEY `talk_date_last` (`talk_date_last`),
  CONSTRAINT `talk_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `talk` */

/*Table structure for table `talk_blacklist` */

CREATE TABLE `talk_blacklist` (
  `user_id` int(10) unsigned NOT NULL,
  `user_target_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`user_target_id`),
  KEY `talk_blacklist_fk_target` (`user_target_id`),
  CONSTRAINT `talk_blacklist_fk_target` FOREIGN KEY (`user_target_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `talk_blacklist_fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `talk_blacklist` */

/*Table structure for table `talk_user` */

CREATE TABLE `talk_user` (
  `talk_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_last` datetime DEFAULT NULL,
  `comment_id_last` int(11) NOT NULL DEFAULT '0',
  `comment_count_new` int(11) NOT NULL DEFAULT '0',
  `talk_user_active` tinyint(1) DEFAULT '1',
  UNIQUE KEY `talk_id_user_id` (`talk_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `date_last` (`date_last`),
  KEY `date_last_2` (`date_last`),
  KEY `talk_user_active` (`talk_user_active`),
  CONSTRAINT `talk_user_fk` FOREIGN KEY (`talk_id`) REFERENCES `talk` (`talk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `talk_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `talk_user` */

/*Table structure for table `topic` */

CREATE TABLE `topic` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `topic_type` enum('topic','link','question','photoset') NOT NULL DEFAULT 'topic',
  `topic_title` varchar(200) NOT NULL,
  `topic_tags` varchar(250) NOT NULL COMMENT 'tags separated by a comma',
  `topic_date_add` datetime NOT NULL,
  `topic_date_edit` datetime DEFAULT NULL,
  `topic_user_ip` varchar(20) NOT NULL,
  `topic_publish` tinyint(1) NOT NULL DEFAULT '0',
  `topic_publish_draft` tinyint(1) NOT NULL DEFAULT '1',
  `topic_publish_index` tinyint(1) NOT NULL DEFAULT '0',
  `topic_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `topic_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_count_read` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_count_comment` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_cut_text` varchar(100) DEFAULT NULL,
  `topic_forbid_comment` tinyint(1) NOT NULL DEFAULT '0',
  `topic_text_hash` varchar(32) NOT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_date_add` (`topic_date_add`),
  KEY `topic_rating` (`topic_rating`),
  KEY `topic_publish` (`topic_publish`),
  KEY `topic_text_hash` (`topic_text_hash`),
  CONSTRAINT `topic_fk` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `topic_fk1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `topic` */

/*Table structure for table `topic_content` */

CREATE TABLE `topic_content` (
  `topic_id` int(11) unsigned NOT NULL,
  `topic_text` longtext NOT NULL,
  `topic_text_short` text NOT NULL,
  `topic_text_source` longtext NOT NULL,
  `topic_extra` text NOT NULL,
  PRIMARY KEY (`topic_id`),
  CONSTRAINT `topic_content_fk` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `topic_content` */

/*Table structure for table `topic_photo` */

CREATE TABLE `topic_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `description` text,
  `target_tmp` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `target_tmp` (`target_tmp`),
  CONSTRAINT `topic_photo_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `topic_photo` */

/*Table structure for table `topic_question_vote` */

CREATE TABLE `topic_question_vote` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `answer` tinyint(4) NOT NULL,
  UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_voter_id`),
  KEY `user_voter_id` (`user_voter_id`),
  CONSTRAINT `topic_question_vote_fk` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `topic_question_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `topic_question_vote` */

/*Table structure for table `topic_read` */

CREATE TABLE `topic_read` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_read` datetime NOT NULL,
  `comment_count_last` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_id_last` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `topic_read_fk` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `topic_read_fk1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `topic_read` */

/*Table structure for table `topic_tag` */

CREATE TABLE `topic_tag` (
  `topic_tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `blog_id` int(11) unsigned NOT NULL,
  `topic_tag_text` varchar(50) NOT NULL,
  PRIMARY KEY (`topic_tag_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `topic_tag_text` (`topic_tag_text`),
  CONSTRAINT `topic_tag_fk` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `topic_tag_fk1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `topic_tag_fk2` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `topic_tag` */

/*Table structure for table `user` */

CREATE TABLE `user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(30) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_mail` varchar(50) NOT NULL,
  `user_skill` float(9,3) unsigned NOT NULL DEFAULT '0.000',
  `user_date_register` datetime NOT NULL,
  `user_date_activate` datetime DEFAULT NULL,
  `user_date_comment_last` datetime DEFAULT NULL,
  `user_ip_register` varchar(20) NOT NULL,
  `user_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `user_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `user_activate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_activate_key` varchar(32) DEFAULT NULL,
  `user_profile_name` varchar(50) DEFAULT NULL,
  `user_profile_sex` enum('man','woman','other') NOT NULL DEFAULT 'other',
  `user_profile_country` varchar(30) DEFAULT NULL,
  `user_profile_region` varchar(30) DEFAULT NULL,
  `user_profile_city` varchar(30) DEFAULT NULL,
  `user_profile_birthday` datetime DEFAULT NULL,
  `user_profile_site` varchar(200) DEFAULT NULL,
  `user_profile_site_name` varchar(50) DEFAULT NULL,
  `user_profile_icq` bigint(20) unsigned DEFAULT NULL,
  `user_profile_about` text,
  `user_profile_date` datetime DEFAULT NULL,
  `user_profile_avatar` varchar(250) DEFAULT NULL,
  `user_profile_foto` varchar(250) DEFAULT NULL,
  `user_settings_notice_new_topic` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_comment` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_talk` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_reply_comment` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_friend` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_mail` (`user_mail`),
  KEY `user_activate_key` (`user_activate_key`),
  KEY `user_activate` (`user_activate`),
  KEY `user_rating` (`user_rating`),
  KEY `user_profile_sex` (`user_profile_sex`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`user_id`,`user_login`,`user_password`,`user_mail`,`user_skill`,`user_date_register`,`user_date_activate`,`user_date_comment_last`,`user_ip_register`,`user_rating`,`user_count_vote`,`user_activate`,`user_activate_key`,`user_profile_name`,`user_profile_sex`,`user_profile_country`,`user_profile_region`,`user_profile_city`,`user_profile_birthday`,`user_profile_site`,`user_profile_site_name`,`user_profile_icq`,`user_profile_about`,`user_profile_date`,`user_profile_avatar`,`user_profile_foto`,`user_settings_notice_new_topic`,`user_settings_notice_new_comment`,`user_settings_notice_new_talk`,`user_settings_notice_reply_comment`,`user_settings_notice_new_friend`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3','admin@admin.adm',0.000,'2011-08-28 00:00:00',NULL,NULL,'127.0.0.1',0.000,0,1,NULL,NULL,'other',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,1,1,1,1,1),(2,'user','5cc32e366c87c4cb49e4309b75f57d64','user@user.com',0.000,'2011-10-03 17:42:36',NULL,NULL,'127.0.0.1',0.000,0,1,NULL,NULL,'other',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,1,1);

/*Table structure for table `user_administrator` */

CREATE TABLE `user_administrator` (
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `user_administrator_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_administrator` */

insert  into `user_administrator`(`user_id`) values (1);

/*Table structure for table `user_field` */

CREATE TABLE `user_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_field` */

/*Table structure for table `user_field_value` */

CREATE TABLE `user_field_value` (
  `user_id` int(11) unsigned NOT NULL,
  `field_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  KEY `user_id` (`user_id`,`field_id`),
  KEY `field_id` (`field_id`),
  CONSTRAINT `user_field_value_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_field_value_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `user_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_field_value` */

/*Table structure for table `userfeed_subscribe` */

CREATE TABLE `userfeed_subscribe` (
  `user_id` int(11) unsigned NOT NULL,
  `subscribe_type` tinyint(4) NOT NULL,
  `target_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`,`subscribe_type`,`target_id`),
  CONSTRAINT `userfeed_subscribe_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `userfeed_subscribe` */

/*Table structure for table `vote` */

CREATE TABLE `vote` (
  `target_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_type` enum('topic','blog','user','comment') NOT NULL DEFAULT 'topic',
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_direction` tinyint(2) DEFAULT '0',
  `vote_value` float(9,3) NOT NULL DEFAULT '0.000',
  `vote_date` datetime NOT NULL,
  PRIMARY KEY (`target_id`,`target_type`,`user_voter_id`),
  KEY `user_voter_id` (`user_voter_id`),
  CONSTRAINT `topic_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `vote` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
