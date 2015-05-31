<?php

/**
 * This component manages the authentication of the API requests made by the app
 * and handle the responses.
 * 
 * @author carlo caprini <carlo.caprini@u-hopper.com>
 */
class ApiManager
{
	/**
	 * Send error response.
	 * 
	 * @param String error_code : code for ErrorMessage
	 * @param String param : parameter that caused the error
	 * @param Integer http_error : http error code
	 */
	public static function errorResponse($error_code, $param = null, $acceptedValues = null) {
		$response = ErrorMessage::getError($error_code, $param, $acceptedValues);
		ApiManager::sendResponse(ErrorMessage::getHtmlErrorCode($error_code), json_encode($response));
	}
	
	/**
	 * App auth check.
	 * 
	 * Successful authentication requires 'app_key' and 'app_secret' values to be
	 * set in the http heaeder of the request.
	 */
	public static function authenticateClient($auth_need_secret = true)
	{
	    if (!isset($_SERVER['HTTP_APP_KEY']) ) {
	    	Yii::log('Missing `app_key` header', 'error', 'client_auth');
			ApiManager::errorResponse(ErrorMessage::ERR_CLIENT_AUTH, null);
	    }
	    $app_key = $_SERVER['HTTP_APP_KEY'];
		$client = EnabledClient::model()->findByAttributes(array('key' => $app_key));
		if ($client) {
			if ($client->need_secret OR $auth_need_secret) {
				if ( !isset($_SERVER['HTTP_APP_SECRET'] )) {
					Yii::log('['. $_SERVER['HTTP_APP_KEY'] . '] Header `app_secret` is missing - `app_key` : [' . $app_key . '"]', 'error', 'client_auth');
					ApiManager::errorResponse(ErrorMessage::ERR_CLIENT_AUTH, null);
				}
				$app_secret = $_SERVER['HTTP_APP_SECRET'];
				if ($client->secret != $app_secret) {
					Yii::log('['. $_SERVER['HTTP_APP_KEY'] . '] Header `app_secret` is not valid for', 'error', 'client_auth');
					ApiManager::errorResponse(ErrorMessage::ERR_CLIENT_AUTH, 'app_secret');
				}
			}
		}
		else {
			Yii::log('Header `app_key` [' . $app_key . '] is not valid', 'error', 'client_auth');
			ApiManager::errorResponse(ErrorMessage::ERR_CLIENT_AUTH, 'app_key');
		}
		// if ($client->key == 'RIDE-SHARING-CLIENT-APPLICATION') {
			// header("Access-Control-Allow-Origin: *");
		// }
		return $client->id;
	}
	
	public static function authenticateUser($username, $password) {
		$user = User::model()->findByAttributes(array('username' => $username));
		if ($user) {
			if ($user->password != crypt($password, $user->password)) {
				Yii::log('['. $_SERVER['HTTP_APP_KEY'] . '] Parameter `password` ['. $password . '] is not valid for`user` [' . $username . ']', 'error', 'user');
				ApiManager::errorResponse(ErrorMessage::ERR_USER_AUTH, $username);
			}
		}
		else {
			Yii::log('['. $_SERVER['HTTP_APP_KEY'] . '] User with `username` [' . $username  . '] does not exists', 'error', 'user');
			ApiManager::errorResponse(ErrorMessage::ERR_USER_NOT_FOUND, $username);
		}
		return $user->id;
	}
	
	public static function successResponse($message, $response, $code = 200) {
		$response = array(
			'status' => 'OK',
			'message' => $message,
			'response' => $response
		);
		ApiManager::sendResponse($code, json_encode($response));
	}
	
	public static function sendResponse($status = 200, $body = '', $content_type = 'text/plain')
	{
		if (is_array($body)) {
			$body = json_encode($body);
		}
		
	    $status_header = 'HTTP/1.1 ' . $status . ' ' . ApiManager::_getStatusCodeMessage($status);
	    header($status_header);
	    header('Content-type: ' . $content_type);
		// header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
		header('Access-Control-Allow-Headers: Content-Type');
	    if($body != '')
	    {
	        echo $body;
	        // echo json_encode($body);
	    }
	    else
	    {
	        $message = '';
	        // this is purely optional, but makes the pages a little nicer to read
	        // for your users.  Since you won't likely send a lot of different status codes,
	        // this also shouldn't be too ponderous to maintain
	        switch($status)
	        {
	            case 401:
	                $message = 'You must be authorized to view this page.';
	                break;
	            case 404:
	                $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
	                break;
	            case 500:
	                $message = 'The server encountered an error processing your request.';
	                break;
	            case 501:
	                $message = 'The requested method is not implemented.';
	                break;
	        }
	        // servers don't always have a signature turned on 
	        // (this is an apache directive "ServerSignature On")
	        $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
	        // this should be templated in a real-world solution
	        $body = '
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	    <title>' . $status . ' ' . ApiManager::_getStatusCodeMessage($status) . '</title>
	</head>
	<body>
	    <h1>' . ApiManager::_getStatusCodeMessage($status) . '</h1>
	    <p>' . $message . '</p>
	    <hr />
	    <address>' . $signature . '</address>
	</body>
	</html>';
	        echo $body;
	    }
	    Yii::app()->end();
	}
	
	private function _getStatusCodeMessage($status)
	{
	    $codes = Array(
	        200 => 'OK',
	        400 => 'Bad Request',
	        401 => 'Unauthorized',
	        402 => 'Payment Required',
	        403 => 'Forbidden',
	        404 => 'Not Found',
	        500 => 'Internal Server Error',
	        501 => 'Not Implemented',
	    );
	    return (isset($codes[$status])) ? $codes[$status] : '';
	}
}