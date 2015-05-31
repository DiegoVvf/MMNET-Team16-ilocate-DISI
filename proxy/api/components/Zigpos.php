<?php

class Zigpos
{

	const ENDPOINT = 'http://zigpos.com:8083/rest/';

	public static function getPosition($objectId) {
		// $url = 'positions/'.$objectId;
		$url = 'positions/22199712880007';

		$position = self::contactService($objectId, $url);
		return $position;
		
	}

	private static function contactService($objectId, $url) {
		$response = self::query($url);
		// return $response;
		$position = self::parseResponse($objectId, $response);
		return $position;
	}

	private static function parseResponse($objectId, $response) {
		// if(is_array($response)) {
			$position = array();
			$position['localizationSystem'] = LocalizationSystem::EERTLS;
			$position['positionType'] = Position::TYPE_RELATIVE;
			$position['objectId'] = $objectId;
			$position['position'] = array(
				'x' => $response->x,
				'y' => $response->y,
				'z' => $response->z,
			);
			$position['accuracy'] = $response->accuracy;
			$position['indoor'] = true;
			return $position;
		// }
		// else {
		// 	return array(
		// 		'error' => ErrorMessage::ERR_ZIGPOS,
		// 		'message' => null,
		// 	);
		// }
	}

	private static function query($url) {
		$url = self::ENDPOINT . $url;

        $ch = curl_init($url);
		
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, false);
        
        $answer = curl_exec($ch);
        return json_decode($answer);
	}

}