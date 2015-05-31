<?php

class m141114_082318_set_id_tag_as_varchar_in_tracker extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `tracker` CHANGE `id_localization_tag` `id_localization_tag` VARCHAR(64)  NOT NULL  DEFAULT '';");
	}

	public function down()
	{
		echo "m141114_082318_set_id_tag_as_varchar_in_tracker does not support migration down.\n";
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