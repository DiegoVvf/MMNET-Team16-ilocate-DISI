<?php

class m141114_081146_add_quuppa_position extends CDbMigration
{
	public function up()
	{
		// Add new quuppa_position table
		$this->execute("DROP TABLE IF EXISTS `quuppa_position`;");
		$this->execute("CREATE TABLE `quuppa_position` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_deployment` int(11) NOT NULL,
		  `id_tag` varchar(32) NOT NULL,
		  `x` float NOT NULL,
		  `y` float NOT NULL,
		  `z` float NOT NULL,
		  `dump` text NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}

	public function down()
	{
		echo "m141114_081146_add_quuppa_position does not support migration down.\n";
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