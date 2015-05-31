<?php

class Combain
{
	const API_KEY = 'xqtddmqu329nkwmhs1qb';
	const ENDPOINT = 'http://cps.combain.com?';

	public static function getPosition($objectId, $positionInfo) {
		
		$position = self::contactService($objectId, $positionInfo);
		return $position;
		
	}

	private static function contactService($objectId, $positionInfo) {
		$params = self::getQueryParams($positionInfo);
		$response = self::query($params);
		$position = self::parseResponse($objectId, $response);
		return $position;
	}

	private static function parseResponse($objectId, $response) {
		$position = array();
		$position['localizationSystem'] = LocalizationSystem::WIFI;
		$position['positionType'] = Position::TYPE_ABSOLUTE;
		$position['objectId'] = $objectId;

		$tmp = explode(';', $response);

		$result = array();
		foreach ($tmp as $e) {
			$pieces = explode('=', $e);
			$result[$pieces[0]] = $pieces[1];
		}

		if (isset($result['status']) && $result['status'] == '0') {
			$position['position'] = array(
				'lat' => $result['lat'],
				'lon' => $result['lon'],
			);
			$position['accuracy'] = $result['acc'];
			return $position;
		}
		else {
			echo Yii::log('Error while contacting combin service.', 'error', 'api');
			return array(
				'error' => ErrorMessage::ERR_COMBAIN,
				'message' => isset($result['status']) ? $result['status'] : null,
			);
		}
	}

	private static function query($params) {
		$url = self::ENDPOINT . $params;

        $ch = curl_init($url);
		
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, false);
        
        $answer = curl_exec($ch);
        return $answer;
	}

	private static function getQueryParams($positionInfo) {
		$params = 'key='.self::API_KEY.'&wifi=';
		$counter = 1;
		foreach ($positionInfo as $key => $entry) {
				$positio_entry = $entry->BSSID.','.str_replace(' ', '', $entry->SSID).','.$entry->RSSI.';';
				$params = $params.$positio_entry;
		}
		return $params;
	}

}