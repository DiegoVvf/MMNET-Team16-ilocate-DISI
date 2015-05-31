<?php

class m141113_144950_mysql_setup extends CDbMigration
{
	public function up()
	{
		// Creation of table deployment
		$this->execute("DROP TABLE IF EXISTS `deployment`;");
		$this->execute("CREATE TABLE `deployment` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(32) NOT NULL DEFAULT '',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		// Creation of table environment
		$this->execute("DROP TABLE IF EXISTS `environment`;");
		$this->execute("CREATE TABLE `environment` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_deployment` int(11) NOT NULL,
		  `name` varchar(32) NOT NULL DEFAULT '',
		  PRIMARY KEY (`id`),
		  KEY `id_deployment` (`id_deployment`),
		  CONSTRAINT `environment_ibfk_1` FOREIGN KEY (`id_deployment`) REFERENCES `deployment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		// Creation of table localization_system_type
		$this->execute("DROP TABLE IF EXISTS `localization_system_type`;");
		$this->execute("CREATE TABLE `localization_system_type` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `type` varchar(32) NOT NULL DEFAULT '',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		$this->execute("LOCK TABLES `localization_system_type` WRITE;");
		$this->execute("INSERT INTO `localization_system_type` (`id`, `type`)
			VALUES
				(1,'QUUPPA'),
				(2,'WIFI'),
				(3,'GPS');");
		$this->execute("UNLOCK TABLES;");

		// Creation of table localization_system
		$this->execute("DROP TABLE IF EXISTS `localization_system`;");
		$this->execute("CREATE TABLE `localization_system` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_type` int(11) NOT NULL,
		  `id_environment` int(11) NOT NULL,
		  `id_deployment` int(11) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `id_type` (`id_type`),
		  KEY `id_environment` (`id_environment`),
		  KEY `id_deployment` (`id_deployment`),
		  CONSTRAINT `localization_system_ibfk_3` FOREIGN KEY (`id_deployment`) REFERENCES `deployment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT `localization_system_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `localization_system_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT `localization_system_ibfk_2` FOREIGN KEY (`id_environment`) REFERENCES `environment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		// Creation of table asset
		$this->execute("DROP TABLE IF EXISTS `asset`;");
		$this->execute("CREATE TABLE `asset` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(32) NOT NULL DEFAULT '',
		  `id_asset` varchar(128) NOT NULL DEFAULT '',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		// Creation of table tracker
		$this->execute("DROP TABLE IF EXISTS `tracker`;");
		$this->execute("CREATE TABLE `tracker` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_localization_system` int(11) NOT NULL,
		  `id_localization_tag` int(11) NOT NULL,
		  `id_environment` int(11) NOT NULL,
		  `id_deployment` int(11) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `id_environment` (`id_environment`),
		  KEY `id_deployment` (`id_deployment`),
		  KEY `id_localization_system` (`id_localization_system`),
		  CONSTRAINT `tracker_ibfk_4` FOREIGN KEY (`id_localization_system`) REFERENCES `localization_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT `tracker_ibfk_2` FOREIGN KEY (`id_environment`) REFERENCES `environment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT `tracker_ibfk_3` FOREIGN KEY (`id_deployment`) REFERENCES `deployment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		// Creation of table tracked_asset
		$this->execute("DROP TABLE IF EXISTS `tracked_asset`;");
		$this->execute("CREATE TABLE `tracked_asset` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_asset` int(11) NOT NULL,
		  `id_deployment` int(11) NOT NULL,
		  `id_environment` int(11) NOT NULL,
		  `id_tracker` int(11) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `id_asset` (`id_asset`),
		  KEY `id_deployment` (`id_deployment`),
		  KEY `id_environment` (`id_environment`),
		  KEY `id_tracker` (`id_tracker`),
		  CONSTRAINT `tracked_asset_ibfk_4` FOREIGN KEY (`id_tracker`) REFERENCES `tracker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT `tracked_asset_ibfk_1` FOREIGN KEY (`id_asset`) REFERENCES `asset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT `tracked_asset_ibfk_2` FOREIGN KEY (`id_deployment`) REFERENCES `deployment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT `tracked_asset_ibfk_3` FOREIGN KEY (`id_environment`) REFERENCES `environment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}

	public function down()
	{
		echo "m141113_144950_mysql_setup does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}