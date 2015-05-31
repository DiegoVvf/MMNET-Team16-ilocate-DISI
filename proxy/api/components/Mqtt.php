<?php

require(dirname(__FILE__).'/../../common/extensions/phpMQTT/phpMQTT.php');

class Mqtt
{

    public static function assetLocation($payload) {
        $queue = 'pilot/'.Yii::app()->params['mqtt.pilot'].'/'.Yii::app()->params['mqtt.asset'].'/location';
        self::publish($queue, $payload);
    }

    public static function personLocation($payload) {
        $queue = 'pilot/'.Yii::app()->params['mqtt.pilot'].'/'.Yii::app()->params['mqtt.person'].'/location';
        self::publish($queue, $payload);
    }

    private static function publish($queue, $payload) {
        $domain = Yii::app()->params['mqtt.domain'];
        $port = Yii::app()->params['mqtt.port'];
        $name = Yii::app()->params['mqtt.name'];
        $mqtt = new phpMQTT($domain, $port, $name);
        if(!$mqtt->connect()){
            // dump('could not connect');
            $mqtt->disconnect();
            $mqtt->close();
            exit(1);
        }

        $mqtt->publish($queue, $payload);

        $mqtt->disconnect();
        $mqtt->close();
    }

}