CREATE TABLE IF NOT EXISTS `submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `owner` int(11) NOT NULL,
  `attachment` varchar(500) DEFAULT NULL,
  `headline` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `text` varchar(100) DEFAULT NULL,
  `link_explanation` varchar(100) DEFAULT NULL,
  `trending` varchar(100) DEFAULT NULL,
  `contest_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contest_it` (`contest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
