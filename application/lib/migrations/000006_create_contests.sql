CREATE TABLE IF NOT EXISTS `contests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `time_length` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stop_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `submission_limit` int(11) NOT NULL,
  `prize` float(8,2) NOT NULL,
  `objective` varchar(20) NOT NULL,
  `platform` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
