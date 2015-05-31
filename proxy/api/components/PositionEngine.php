<?php

/**
 * Combines the position information of multiple localization systems 
 */

class PositionEngine
{

	public static function getPosition($objectId, $positions, $positionType = Position::TYPE_ABSOLUTE) {
		$position = array();

		if ( self::isIndoor($positions) ) {
			$position = self::getIndoorPosition($objectId, $positions, $positionType);
		}
		else {
			$position = self::getOutdoorPosition($objectId, $positions, $positionType);
		}
		// self::notifyOnMQTT($position);
		return $position;
	}

	private static function isIndoor($positions) {
		if (!isset($positions[LocalizationSystem::GPS])) {
			return true;
		}
		else {
			$gps = $positions[LocalizationSystem::GPS];
			if ($gps && $gps->accuracy > 30) {
				return true;
			}
		}
		return false;
	}

	private static function getIndoorPosition($objectId, $positions, $positionType) {
		$position = null;
		$position['objectId'] = $objectId;
		$position['positionType'] = $positionType;
		$position['indoor'] = true;
		if (isset($positions[LocalizationSystem::QRCODE])) {
			return $positions[LocalizationSystem::QRCODE]->dumpRepr();
		}
		else if (isset($positions[LocalizationSystem::QUUPPA])) {
			$position = array();
			$position['position'] = array(
				'lat' => 43.774469,
				'lon' => 11.252209,
			);
			$position['accuracy'] = '0.3';
			$position['indoor'] = true;
			$position['source'] = LocalizationSystem::QUUPPA;
		}
		else if (isset($positions[LocalizationSystem::EERTLS])) {
			$position = array();
			$position['position'] = array(
				'lat' => 43.774469,
				'lon' => 11.252209,
			);
			$position['accuracy'] = '0.3';
			$position['indoor'] = true;
			$position['source'] = LocalizationSystem::EERTLS;
		}
		else if (isset($positions[LocalizationSystem::WIFI])) {
			$position = array();
			$wifi = $positions[LocalizationSystem::WIFI];
			$position['position'] = array(
				'lat' => $wifi['position']['lat'],
				'lon' => $wifi['position']['lon'],
			);
			$position['accuracy'] = $wifi['accuracy'];
			$position['indoor'] = true;
			$position['source'] = LocalizationSystem::WIFI;
		}
		// self::saveLastKnownPosition($objectId, $position);
		return $position;
	}

	private static function getOutdoorPosition($objectId, $positions, $positionType) {
		$position = $positions[LocalizationSystem::GPS];
		self::saveLastKnownPosition($objectId, $position);
		return $position->dumpRepr();
	}

	private static function saveLastKnownPosition($objectId, $position) {
		$tracked_asset = AssetManager::get($objectId);
		$model = LastKnownPosition::model()->findByAttributes(array('id_tracked_asset' => $tracked_asset->id));
		if (!$model) {
			$model = new LastKnownPosition;
			$model->id_tracked_asset = AssetManager::getTrackedAsset($objectId)->id;
		}
		$model->lat = $position->lat;
		$model->lon = $position->lon;
		$model->accuracy = $position->accuracy;
		$model->type = ( $position->indoor == true ) ? 'indoor' : 'outdoor';
		$model->save();
	}

	public static function getLastKnownPosition($object_id) {
		$model = LastKnownPosition::model()->findByAttributes(array(''));
	} 

	private static function notifyOnMQTT($position) {
		$payload = array(
			'AssetId' => $position['objectId'],
			'Position' => array(
				'lat' => $position['position']['lat'],
				'lon' => $position['position']['lon'],
				'accuracy' => $position['accuracy'],
			),
			'ShowAccuracy' => true
		);
		Mqtt::publish("/updateAssetLoc", json_encode($payload));
	}

}