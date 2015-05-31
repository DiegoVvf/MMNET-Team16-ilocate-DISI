<?php

class m141202_085633_add_last_known_position extends CDbMigration
{
	public function up()
	{
		$this->execute("DROP TABLE IF EXISTS `last_known_position`;");
		$this->execute("CREATE TABLE `last_known_position` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_tracked_asset` varchar(64) NOT NULL DEFAULT '',
		  `lat` float NOT NULL,
		  `lon` float NOT NULL,
		  `accuracy` float NOT NULL,
		  `type` varchar(32) NOT NULL DEFAULT '',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}

	public function down()
	{
		echo "m141202_085633_add_last_known_position does not support migration down.\n";
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