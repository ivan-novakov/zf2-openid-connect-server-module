--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `authn_time` datetime NOT NULL,
  `authn_method` varchar(16) COLLATE utf8_czech_ci NOT NULL,
  `authorization_code` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `access_token` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `refresh_token` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `user_data` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`client_id`),
  UNIQUE KEY `access_token` (`access_token`),
  UNIQUE KEY `refresh_token` (`refresh_token`),
  UNIQUE KEY `authorization_code` (`authorization_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
