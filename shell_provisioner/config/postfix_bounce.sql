CREATE TABLE `bounce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` longtext NOT NULL,
  `date` datetime DEFAULT NULL,
  `sysLogTag` longtext NOT NULL,

  PRIMARY KEY (`id`)
);