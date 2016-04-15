CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer` tinyint(1) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `topic` varchar(100) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
