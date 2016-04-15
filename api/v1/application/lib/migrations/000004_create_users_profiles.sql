CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int(11) NOT NULL,
  `logo_url` varchar(500) NOT NULL DEFAULT '',
  `mission` varchar(500) NOT NULL DEFAULT '',
  `extra_info` varchar(500) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
