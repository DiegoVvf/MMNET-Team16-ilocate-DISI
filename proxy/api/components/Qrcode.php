<?php

/**
 * This is the module responsible for fetching the position of an asset given the identification 
 * number of a QRcode.
 *
 * The module returns:
 *  - a Position object: if it has been possible to determine the position of the specified 
 *      QRcode identification number;
 *  - null: if the position of the QRcode could not be determined.
 *
 * The QRcode position service endpoint can be defined in the common configuration file.
 */

class Qrcode
{
    public static function getPosition($objectId, $qrcodeId) {
        // $qrcodeId = '637';
        $url = Yii::app()->params['qrcode.position.service'].'?id=' . $qrcodeId;
        $position = self::contactService($objectId, $url);
        return $position;
    }

    private static function contactService($objectId, $url) {
        $response = Query::run($url);
        if ($response) return self::parseResponse($objectId, $response);
        else return null;
    }

    private static function parseResponse($objectId, $response) {
        if ( !isset($response['position']) || !isset($response['position']['x']) ) {
            echo Yii::log('Response from Qrcode service is not well-formatted.', 'error', 'api.qrcode');
            return null;
        }

        $metadata = array(
            'qrdescription'=>$response['qrdescription'],
            'qrname'=>$response['qrname'],
        );

        $position = new Position(LocalizationSystem::QRCODE, $objectId, $metadata);
        $position->setRelativePosition($response['position']['x'], $response['position']['y'], $response['position']['z'], true);
        return $position;
    }

}