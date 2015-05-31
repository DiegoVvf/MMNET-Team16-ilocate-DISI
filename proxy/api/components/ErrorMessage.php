<?php

class ErrorMessage
{
	const ERR_SUBMITTED_DATA = 'error.submitted.data';

	const ERR_MISSING_PARAMETER = 'error.missing.parameter';
	const ERR_VALUE_NOT_VALID = 'error.value.not.valid';
	const ERR_INTERNAL = 'error.internal.server';
	const ERR_BAD_REQUEST = 'error.bad.request';

	const ERR_QUUPPA_NOT_ASSIGNED = 'error.quuppa.not.assigned';
	const ERR_ASSET_NOT_EXIST = 'error.asset.not.exist';

	const ERR_COMBAIN = 'error.combain';
	const ERR_ZIGPOS = 'error.zigpos';
	
	public function getError($error_code, $param = null, $acceptedValues = null) {
		return array(
			'status' => 'NOK',
			'message' => ErrorMessage::getErrorMessage($error_code, $param, $acceptedValues),
			'error.code' => $error_code
		);
	}
	
	private static function getErrorMessage($error, $param = null, $acceptedValues = null) {
		$message = '';
		switch ($error) {
			case self::ERR_SUBMITTED_DATA:
				$message = 'The format of the submitted data is not valid.';
				break;
			case self::ERR_MISSING_PARAMETER:
				$message = 'Parameter [' . $param . '] missing.';
				break;
			case self::ERR_VALUE_NOT_VALID:
				$message = 'Value for parameter [' . $param . '] is not valid. Accepted values are ['.$acceptedValues.'].';
				break;
			case self::ERR_BAD_REQUEST:
				if ($param)
					$message = $param;
				else 
					$message = 'Bad request.';
				break;
			case self::ERR_INTERNAL:
				$message = 'Internal server error.';
				break;
			case self::ERR_QUUPPA_NOT_ASSIGNED:
				$message = 'The specified asset has not be assigned to any QUUPPA tag.';
				break;
			case self::ERR_ASSET_NOT_EXIST:
				$message = 'No asset with the specified object id [' . $param . '] exist.';
				break;
			case self::ERR_COMBAIN:
				$message = 'Error whicle contacting Combain service.';
				if ($param)
					$message = $message . ' Code [' . $param . '].';
				break;
			case self::ERR_ZIGPOS:
				$message = 'Error whicle contacting Zigpos service.';
				break;
		}
		return $message;
	}
	
	public static function getHtmlErrorCode($error) {
		$code = null;
		switch ($error) {
			case self::ERR_SUBMITTED_DATA:
				$code = 400;
				break;
			case self::ERR_MISSING_PARAMETER:
				$code = 400;
				break;
			case self::ERR_BAD_REQUEST:
				$code = 500;
				break;
			case self::ERR_INTERNAL:
				$code = 500;
				break;
			case self::ERR_QUUPPA_NOT_ASSIGNED:
				$code = 500;
				break;
			case self::ERR_ASSET_NOT_EXIST:
				$code = 500;
				break;
			case self::ERR_COMBAIN:
				$code = 500;
				break;
			case self::ERR_ZIGPOS:
				$code = 500;
				break;
		}
		return $code;
	}
}