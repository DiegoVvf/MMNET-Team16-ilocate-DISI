<?php

class AssetManager
{
	public static function get($objectId) {
		$asset = Asset::model()->findByAttributes(array('id_asset'=>$objectId));
		if ($asset)
			return $asset;
		return null;
	}

	public static function getTrackedAsset($objectId) {
		$asset = self::get($objectId);
		$trackedAsset = TrackedAsset::model()->findByAttributes(array('id_asset' => $asset->id));
		return $trackedAsset;
	}

}