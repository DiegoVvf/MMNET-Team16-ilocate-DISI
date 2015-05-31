<?php

class TagResolver
{
	public static function getTagId($objectId) {
		$asset = AssetManager::get($objectId);
		if (!$asset)
			return null;
		$tracked_asset = TrackedAsset::model()->findByAttributes(array('id_asset'=>$asset->id));
		if ($tracked_asset)
			return $tracked_asset->idTracker->id_localization_tag;
		return null;
	}

	public static function getTag($objectId) {
		$asset = AssetManager::get($objectId);
		if (!$asset)
			return null;
		$tracked_asset = TrackedAsset::model()->findByAttributes(array('id_asset'=>$asset->id));
		if ($tracked_asset)
			return $tracked_asset->idTracker;
		return null;
	}

}