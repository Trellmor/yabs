--
-- Table structure for table `yabs_category`
--

CREATE TABLE `yabs_category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `category_name` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `yabs_image`
--

CREATE TABLE IF NOT EXISTS `yabs_image` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_date` bigint(20) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `yabs_settings`
--

CREATE TABLE IF NOT EXISTS `yabs_settings` (
  `setting_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `setting_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Data for table `yabs_settings`
--

INSERT INTO `yabs_settings` (`setting_name`, `setting_value`) VALUES
('akismet', '0'),
('datetime_format', 'l, j. F Y G:i'),
('entries_per_page', '10'),
('template', 'default');

--
-- Table structure for table `yabs_user`
--

CREATE TABLE IF NOT EXISTS `yabs_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` binary(60) NOT NULL,
  `user_mail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_active` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Data for table `yabs_user`
--

INSERT INTO `yabs_user` (`user_id`, `user_name`, `user_password`, `user_mail`, `user_active`) VALUES
(1, 'Admin', '$2a$12$REdtnL.f35VTPjRpHVJpcOfA3yEYteMtVDR8yWeHwGle2sPh/6M42', 'admin@example.com', 1);

--
-- Table structure for table `yabs_user_permission`
--

CREATE TABLE IF NOT EXISTS `yabs_user_permission` (
  `user_id` int(10) unsigned NOT NULL,
  `user_permission` int(10) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`,`user_permission`),
  CONSTRAINT `yabs_user_permission_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `yabs_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Data for table `yabs_user_permission`
--

INSERT INTO `yabs_user_permission` (`user_id`, `user_permission`) VALUES
(1, 5);

--
-- Table structure for table `yabs_user_remember`
--

CREATE TABLE IF NOT EXISTS `yabs_user_remember` (
  `user_remember_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_remember_token` binary(32) NOT NULL,
  `user_remember_date` bigint(20) NOT NULL,
  PRIMARY KEY (`user_remember_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `yabs_user_remember_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `yabs_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `yabs_entry`
--

CREATE TABLE IF NOT EXISTS `yabs_entry` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entry_teaser` text COLLATE utf8_unicode_ci,
  `entry_content` text COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `entry_date` bigint(20) NOT NULL,
  `entry_visible` tinyint(4) NOT NULL,
  `entry_uri` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `entry_commentcount` int(11) NOT NULL,
  PRIMARY KEY (`entry_id`),
  UNIQUE KEY `entry_uri` (`entry_uri`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `yabs_entry_user_id` FOREIGN KEY (`user_id`) REFERENCES `yabs_user` (`user_id`),
  CONSTRAINT `yabs_entry_category_id` FOREIGN KEY (`category_id`) REFERENCES `yabs_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `yabs_comment`
--

CREATE TABLE IF NOT EXISTS `yabs_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(10) unsigned NOT NULL,
  `comment_author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment_mail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_text` text COLLATE utf8_unicode_ci NOT NULL,
  `comment_spam` tinyint(4) NOT NULL,
  `comment_visible` tinyint(4) NOT NULL,
  `comment_date` bigint(20) NOT NULL,
  `comment_ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL,
  `comment_hostname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `entry_id` (`entry_id`),
  CONSTRAINT `yabs_comment_entry_id` FOREIGN KEY (`entry_id`) REFERENCES `yabs_entry` (`entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

