<?php

class ApiController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    
    public function accessRules()
    {
        return array(
            array('allow', 
                'users'=>array('*'),
                'actions'=>array(
                    'locationbasedondata',
                    'error', 'index',
                    'getlocation', 'getobjectparameters', 
                    'getestimatedlocation', 'registerlocation', 'unregisterlocation', 
                    'quuppapositions','pub', 'sub', 'getquuppaparameters', 'camera'
                ),
            ),
            array('deny', 
                'users'=>array('*'),
            ),
        );
    }

    /*
     * Following an example of raw data to push to the endpoint:
        {
          "positionType":"relative",
          "localizationSystems": [
            {
              "localizationSystem_id":"wifi",
              "entries": [
                {
                  "SSID":"dj09rfdkfjdf",
                  "macAddress":"dj09rfdkfjdf",
                  "RSSI":"dj09rfdkfjdf",
                  "signalStrenght":123
                },
                {
                  "SSID":"dj09rfdkfjdf",
                  "macAddress":"dj09rfdkfjdf",
                  "RSSI":"dj09rfdkfjdf",
                  "signalStrenght":123
                }
              ]
            }
          ]
        }
    */
    public function actionLocationBasedOnData() {
        $postdata = file_get_contents("php://input");
        $data = json_decode($postdata,true);

        if (Yii::app()->params['openam.auth'] ) {
            if ( !isset($data['token']) ) {
                ApiManager::sendResponse(401, array('status'=>'nok', 'message'=>'Auth failed. Missing auth token.'));
            }
            $token = $data['token'];

        }

        if ( !isset($data['localizationSystems']) || !isset($data['objectId']) ) {
            ApiManager::sendResponse(400, array('status'=>'nok', 'message'=>'Structure of submitted data is not correct. Parameters localizationSystems and objectId must be specified.'));
        }

        $localizationSystems = $data['localizationSystems'];
        $objectId = $data['objectId'];

        $asset = AssetManager::get($objectId);
        if (!$asset) {
            ApiManager::sendResponse(404, array('status'=>'nok', 'message'=>'The specified asset is not registered.'));
        }

        $positionType = Position::TYPE_ABSOLUTE;
        if (isset($data['positionType']) ) {
            $positionType = $data['positionType'];
            if (!Position::isValid($positionType)) {
                ApiManager::sendResponse(400, array('status'=>'nok', 'message'=>'The submitted positionType is not allowed.'));
            }
        }

        $localizationSystemsInQuery = array();
        foreach ($localizationSystems as $localizationSystem) {
            if (!isset($localizationSystem['localizationSystem_id'])) {
                ApiManager::sendResponse(400, array('satus'=>'nok', 'message'=>'Parameter localizationSystem_id has not been defined.'));
            }
            $localizationSystemId = $localizationSystem['localizationSystem_id'];
            if (!LocalizationSystem::isValid($localizationSystemId)) {
                ApiManager::sendResponse(400, array('status'=>'nok', 'message'=>'Submitted value for localizationSystem_id is not supported.'));
            }
            $entries = $localizationSystem['entries'];
            if (is_object($entries)) {
                $entries = json_decode($entries);
            }
            $localizationSystemsInQuery[$localizationSystemId] = $localizationSystem['entries'];
        }

        $positions = array();

        $quuppaTagId = TagResolver::getTagId($objectId);
        if ($quuppaTagId) {
            $quuppaPosition = IndoorLocalization::getPosition($objectId, $quuppaTagId, $positionType);
            if ($quuppaPosition) $positions[LocalizationSystem::QUUPPA] = $quuppaPosition;
        }
        if (array_key_exists(LocalizationSystem::WIFI, $localizationSystemsInQuery)) {
            $wifiPosition = Combain::getPosition($objectId, $localizationSystemsInQuery[LocalizationSystem::WIFI], $positionType);
            if ( !isset($wifiPosition['error']) ) {
                // ApiManager::errorResponse($wifiPosition['error'], $wifiPosition['message'], null);
                $positions[LocalizationSystem::WIFI] = $wifiPosition;
            }
        }
        if (array_key_exists(LocalizationSystem::GPS, $localizationSystemsInQuery) && $localizationSystemsInQuery[LocalizationSystem::GPS][0]) {
            // if (!isset($localizationSystemsInQuery[LocalizationSystem::GPS][0]) || 
            //  !isset($localizationSystemsInQuery[LocalizationSystem::GPS][0]) || 
            //  )
            $gps_entry = $localizationSystemsInQuery[LocalizationSystem::GPS][0];
            $gpsPosition = new Position(LocalizationSystem::GPS, $objectId);
            $gpsPosition->setAbsolutePosition($gps_entry['lat'], $gps_entry['lon'], false, $gps_entry['accuracy']);

            // if ($objectId = 'cc:fa:00:e7:0c:15') {
            //     ApiManager::successResponse(
            //         'Object location correctly computed using the localization systems [gps].', 
            //         array(
            //             'position' => array(
            //                 'lat' => $gps_entry->lat,
            //                 'lon' => $gps_entry->lon
            //             ),
            //             'accuracy' => $gps_entry->accuracy,
            //             'indoor' => false
            //         )
            //     );
            // }
            // $positions[LocalizationSystem::GPS] = array('lat'=>$gps_entry['lat'], 'lon'=>$gps_entry['lon'], 'accuracy'=>$gps_entry['accuracy']);
            $positions[LocalizationSystem::GPS] = $gpsPosition;
        }
        if (array_key_exists(LocalizationSystem::QRCODE, $localizationSystemsInQuery)) {
            $qrcodePosition = Qrcode::getPosition($objectId, $localizationSystemsInQuery[LocalizationSystem::QRCODE][0]['id']);
            if ( $qrcodePosition ) {
                $positions[LocalizationSystem::QRCODE] = $qrcodePosition;
            }
        }

        if ( Yii::app()->params['publish.to.mqtt']) {
            foreach ($positions as $localizationSystem => $position) {
                Mqtt::personLocation(json_encode($position->dumpRepr()));
            }
        }

        if (count($positions) == 0) {
            ApiManager::sendResponse(400, array('status'=>'nok', 'message'=>'Could not determine the position of the target object.'));
        }
        else {
            $position = PositionEngine::getPosition($objectId, $positions, $positionType);

            $used_localization_systems = null;
            foreach ($positions as $key => $value) {
                if ($used_localization_systems == null) {
                    $used_localization_systems = $key;
                }
                else {
                    $used_localization_systems = $used_localization_systems.', '.$key;
                }
            }

            ApiManager::successResponse(
                'Object location correctly computed using the localization systems ['.$used_localization_systems.'].', 
                $position
            );
        }
    }

    public function actionCamera() {
        $postdata = file_get_contents("php://input");
        $data = json_decode($postdata, true);
        if (!is_array($data)) ApiManager::errorResponse(ErrorMessage::ERR_SUBMITTED_DATA, null, array());
        Mqtt::personLocation(json_encode($data));
        ApiManager::successResponse('Camera data was successfully posted.', $data);
    }

    public function actionGetquuppaparameters() {
        if (isset($_GET['object_id']) ) {
            $object_id = $_GET['object_id'];
            $tagId = TagResolver::getTagId($object_id);
            $params = QuuppaPosition::model()->findByAttributes(array('id_tag' => $tagId));
            if ($params) {
                ApiManager::successResponse(
                    'Quuppa params.', 
                    $params->dump
                );
            }
        }
        ApiManager::errorResponse(ErrorMessage::ERR_QUUPPA_NOT_ASSIGNED, 'object_id', array()); 
    }

    public function actionPub() {
        require(dirname(__FILE__).'/../../common/extensions/phpMQTT/phpMQTT.php');
        $mqtt = new phpMQTT("5.249.155.8", 1883, "carlo");
        if(!$mqtt->connect()){
            dump('could not connect');
            $mqtt->disconnect();
            $mqtt->close();
            exit(1);
        }
        dump('connected');
        // $payload = array();
        // $payload = array('payload'=>'ciao carlo');
        // $payload['Id'] = 123123;
        // $payload['ShowAccuracy'] = false;
        // $payload['AssetName'] = 'asd';
        // $payload['Category'] = 'prova';
        // $payload['Position'] = array('x'=>10.0, 'y'=>12.0, 'z'=>34.1, 'accuracy'=>0.0);
        // $payload['AssetColor'] = array('r'=>255, 'g'=>0, 'b'=>0);

        $mqtt->publish("/carlo/test",'ciao');
        // if ($mqtt->publish("carlo",'ciao') ) {
        //  dump('published');
        // }
        // else {
        //  dump('not published');
        // }
        // $topics = array();
        // $topics['#newAsset'] = array("qos"=>0, "function"=>"procmsg");

        // $result = $mqtt->subscribe($topics,0);
        // while($mqtt->proc()){
        
        // }
        // dump($result);
        $mqtt->disconnect();
        $mqtt->close();
    }

    public function actionSub() {
        // $p = new PositionManager;
        // dump($p->getAbsolutePosition(0.0, 0.0));
        // dump(dirname(__FILE__).'/../../common/extensions/phpMQTT/phpMQTT.php');
        require(dirname(__FILE__).'/../../common/extensions/phpMQTT/phpMQTT.php');
        $mqtt = new phpMQTT("5.249.155.8", 1883, "carlo");
        if(!$mqtt->connect()){
            dump('could not connect');
            exit(1);
        }
        // $payload = array();
        // $payload['Id'] = 123123;
        // $payload['ShowAccuracy'] = false;
        // $payload['AssetName'] = 'asd';
        // $payload['Category'] = 'prova';
        // $payload['Position'] = array('x'=>10.0, 'y'=>12.0, 'z'=>34.1, 'accuracy'=>0.0);
        // $payload['AssetColor'] = array('r'=>255, 'g'=>0, 'b'=>0);

        // dump('connected');
        // if ($mqtt->publish("carlo/test",'prova',0) ) {
        //  dump('published');
        // }
        // else {
        //  dump('not published');
        // }
        // $topics = array();
        $topics['carlo/test'] = array("qos"=>0, "function"=>"procmsg");

        $result = $mqtt->subscribe($topics,0);
        dump($result);
        while($mqtt->proc()){
        
        }
        $mqtt->close();
    }

    function procmsg($topic,$msg){
        echo "Msg Recieved:$msg\n";
    }

    public function actionQuuppaPositions($deployment) {
        $postdata = file_get_contents("php://input");
        $data = json_decode($postdata);
        $tags = $data->tags;
        foreach ($tags as $tag) {
            $quuppaPosition = QuuppaPosition::model()->findByAttributes(array('id_tag' => $tag->id, 'id_deployment' => intval($deployment)));
            if (!$quuppaPosition) {
                $quuppaPosition = new QuuppaPosition;
            }
            $quuppaPosition->id_tag = $tag->id;
            $quuppaPosition->id_deployment = $deployment;
            $quuppaPosition->x = $tag->position[0];
            $quuppaPosition->y = $tag->position[1];
            $quuppaPosition->z = $tag->position[2];
            $quuppaPosition->dump = json_encode($tag);
            $quuppaPosition->save();
        }
        ApiManager::successResponse(
            'Quuppa position pushed for deployment ['.$deployment.'].', 
            null
        );
    }

    public function actionGetLocation() {
        /**
         * Mandatory, it identifies the localization system to be used. 
         * At the moment the following values are supported: ‘eertls’, ‘quuppa’, ‘gnss’, ‘gps’, ‘wifi’
         */
        if (isset($_GET['obj_id']) ) {
            $obj_id = $_GET['obj_id'];
            $asset = AssetManager::get($obj_id);
            if (!$asset) {
                ApiManager::errorResponse(ErrorMessage::ERR_ASSET_NOT_EXIST, $obj_id);
            }
            /**
             * Optional, defines whether the positioning information should be returned 
             * as absolute or relative to the local reference point. 
             * Values supported: ‘absolute’, ‘relative’. 
             * When the parameter is not specified, ‘absolute’ is used as default value.
             */
            // $positionType = 'absolute';
            // if (isset($_GET['positionType']) ) { 
            //  $positionType = $_GET['positionType'];
            //  if (!$this->isPositionTypeValid($positionType)) {
            //      ApiManager::errorResponse(ErrorMessage::ERR_VALUE_NOT_VALID, 'positionType', implode($this->getAcceptedPositionValues(), ', '));
            //  }
            // }
            /**
             * Optional, defines the maximum age in millisecond for a stored information to be considered valid.
             * If no information that satisfies this constraint is available, then an empty result is returned.
             */
            // if (isset($_GET['maxAge']) ) { 
                
            // }
            /**
             * Timestamp when this response was generated as ISO 8601:1988 (E) format 
             * (Optional, can be used at the application level).
             */
            // if (isset($_GET['responseTimestampEpoch']) ) { 
                
            // }
            // $tag_id = TagResolver::getTagId($asset->id);
            // if ($tag_id) {
            $position1 = array(
                'position' => array(
                    'lat' => 43.774469,
                    'lon' => 11.252209,
                ),
                'accuracy' => 0.3,
                'objectId' => $obj_id,
            );
            $position2 = array(
                'position' => array(
                    'lat' => 43.774389,
                    'lon' => 11.252129,
                ),
                'accuracy' => 0.3,
                'objectId' => $obj_id,
            );
            $position = ($obj_id == 'e3:a6:a6') ? $position1 : $position2;
            ApiManager::successResponse('Location for object ['.$obj_id.'] correctly fetched.', $position);
            // }
            // else {
            //  $response = array();
            //  for ($i=1; $i < 4; $i++) { 
            //      $response[] = $this->getObjectInformation($localizationSystemId, $i, $positionType);
            //  }
            //  ApiManager::successResponse('Info about indoor location in system ['. $localizationSystemId . '] correctly fetched.', $response);
            // }
            // else {
            //  ApiManager::errorResponse(ErrorMessage::ERR_QUUPPA_NOT_ASSIGNED, null);
            // }
        }
        else {
            if (!isset($_GET['obj_id']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'obj_id');
            else 
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'localizationSystem_id');
        }
    }

    public function actionGetObjectParameters() {
        /**
         * Mandatory. 
         * localizationSystem_id : it identifies the localization system to be used. 
         *      At the moment the following values are supported: ‘eertls’, ‘quuppa’, ‘gnss’, ‘gps’, ‘wifi’
         * obj_id : only information associated to the requested object is returned. 
         *      If this parameter is not specified, information on all objects being tracked are returned.
         */
        if (isset($_GET['localizationSystem_id']) && isset($_GET['obj_id']) ) {
            $localizationSystemId = $_GET['localizationSystem_id'];
            if (!$this->isLocalizationSystemIdValid($localizationSystemId)) {
                ApiManager::errorResponse(ErrorMessage::ERR_VALUE_NOT_VALID, 'localizationSystem_id', implode($this->getAcceptedLocalizationSystemIds(), ', '));
            }
            $obj_id = $_GET['obj_id'];
            /**
             * Optional, defines whether the positioning information should be returned 
             * as absolute or relative to the local reference point. 
             * Values supported: ‘absolute’, ‘relative’. 
             * When the parameter is not specified, ‘absolute’ is used as default value.
             */
            $positionType = 'absolute';
            if (isset($_GET['positionType']) ) { 
                $positionType = $_GET['positionType'];
                if (!$this->isPositionTypeValid($positionType)) {
                    ApiManager::errorResponse(ErrorMessage::ERR_VALUE_NOT_VALID, 'positionType', implode($this->getAcceptedPositionValues(), ', '));
                }
            }
            ApiManager::successResponse(
                'Info about indoor location in system [' . $localizationSystemId . '] correctly fetched.', 
                $this->getObjectInformation($localizationSystemId, $obj_id, $positionType)
            );
        }
        else {
            if (!isset($_GET['localizationSystem_id']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'localizationSystem_id');
            else 
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'obj_id');
        }
    }

    public function actionGetEstimatedLocation() {
        /**
         * Mandatory, only information associated to the requested object is returned. 
         * if this parameter is not specified, information on all objects being tracked are returned.
         */
        if (isset($_GET['obj_id']) ) {
            $obj_id = $_GET['obj_id'];
            /**
             * Optional, defines whether the positioning information should be returned 
             * as absolute or relative to the local reference point. 
             * Values supported: ‘absolute’, ‘relative’. 
             * When the parameter is not specified, ‘absolute’ is used as default value.
             */
            $positionType = 'absolute';
            if (isset($_GET['positionType']) ) { 
                $positionType = $_GET['positionType'];
                if (!$this->isPositionTypeValid($positionType)) {
                    ApiManager::errorResponse(ErrorMessage::ERR_VALUE_NOT_VALID, 'positionType', implode($this->getAcceptedPositionValues(), ', '));
                }
            }
            $location = array(
                'obj_id' => $obj_id,
                'positionType' => $positionType,
                'position' => 
                    ($positionType == 'absolute') ? 
                    array(
                        'lat' => 21.3,
                        'lon' => 13.2,
                    ) : 
                    array(
                        'x' => 213,
                        'y' => 132,
                        'z' => 873,
                    ),
                'positionAccuracy' => 123,
            );
            ApiManager::successResponse(
                'Indoor location of object [' . $obj_id . '] has been estimated.', 
                $location
            );
        }
        else {
            ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'obj_id');
        }
    }

    public function actionRegisterLocation() {
        if (isset($_POST['pushEndPoint_id']) && 
                isset($_POST['pushRegistrationID_id']) && 
                isset($_POST['localizationSystem_id']) && 
                isset($_POST['callback']) && 
                isset($_POST['obj_id']) ) {
            $localizationSystem_id = $_POST['localizationSystem_id'];
            if (!$this->isLocalizationSystemIdValid($localizationSystem_id)) {
                ApiManager::errorResponse(ErrorMessage::ERR_VALUE_NOT_VALID, 'localizationSystem_id', implode($this->getAcceptedLocalizationSystemIds(), ', '));
            }
            $pushEndPoint_id = $_POST['pushEndPoint_id'];
            $pushRegistrationID_id = $_POST['pushRegistrationID_id'];
            $obj_id = $_POST['obj_id'];
            $callback = $_POST['callback'];
            $timePeriod = null;
            if (isset($_POST['timePeriod']) ) {
                $timePeriod = $_POST['timePeriod'];
            }
            $message = "Callback [{$callback}] with pushEndPoint_id [{$pushEndPoint_id}] ".
                "and pushRegistrationID_id [{$pushRegistrationID_id}] ".
                "successfully registered for ".
                "localizationSystem [{$localizationSystem_id}], ".
                "and obj_id [{$obj_id}].";
            if ($timePeriod) {
                $message .= " Time period has been set to [{$timePeriod}].";
            }
            ApiManager::successResponse(
                $message, 
                1
            );
        }
        else {
            if (!isset($_POST['pushEndPoint_id']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'pushEndPoint_id');
            else if (!isset($_POST['pushRegistrationID_id']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'pushRegistrationID_id');
            else if (!isset($_POST['localizationSystem_id']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'localizationSystem_id');
            else if (!isset($_POST['callback']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'callback');
            else
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'obj_id');
        }
    }

    public function actionUnregisterLocation() {
        if (isset($_POST['pushEndPoint_id']) && 
                isset($_POST['pushRegistrationID_id']) && 
                isset($_POST['localizationSystem_id']) && 
                isset($_POST['obj_id']) ) {
            $localizationSystem_id = $_POST['localizationSystem_id'];
            if (!$this->isLocalizationSystemIdValid($localizationSystem_id)) {
                ApiManager::errorResponse(ErrorMessage::ERR_VALUE_NOT_VALID, 'localizationSystem_id', implode($this->getAcceptedLocalizationSystemIds(), ', '));
            }
            $pushEndPoint_id = $_POST['pushEndPoint_id'];
            $pushRegistrationID_id = $_POST['pushRegistrationID_id'];
            $obj_id = $_POST['obj_id'];
            $message = "Callback for pushEndPoint_id [{$pushEndPoint_id}] ".
                "and pushRegistrationID_id [{$pushRegistrationID_id}] ".
                "successfully unregistered for ".
                "localizationSystem [{$localizationSystem_id}], ".
                "and obj_id [{$obj_id}].";
            ApiManager::successResponse(
                $message, 
                1
            );
        }
        else {
            if (!isset($_POST['pushEndPoint_id']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'pushEndPoint_id');
            else if (!isset($_POST['pushRegistrationID_id']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'pushRegistrationID_id');
            else if (!isset($_POST['localizationSystem_id']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'localizationSystem_id');
            else if (!isset($_POST['callback']))
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'callback');
            else
                ApiManager::errorResponse(ErrorMessage::ERR_MISSING_PARAMETER, 'obj_id');
        }
    }

    private function getObjectInformation($localizationSystemId, $obj_id, $positionType = 'absolute') {
        return array(
            'obj_id' => $obj_id,
            'localizationSystem_id' => $localizationSystemId,
            'positionTimestampEpoch' => 123123123123,
            'positionType' => $positionType,
            'position' => 
                ($positionType == 'absolute') ? 
                array(
                    'lat' => 21.3,
                    'lon' => 13.2,
                ) : 
                array(
                    'x' => 213,
                    'y' => 132,
                    'z' => 873,
                ),
            'positionAccuracy' => 123,
        );
    }

    private function isLocalizationSystemIdValid($localizationSystemId) {
        $acceptedValues = $this->getAcceptedLocalizationSystemIds();
        if (in_array($localizationSystemId, $acceptedValues)) return true;
        return false;
    }

    private function getAcceptedLocalizationSystemIds() {
        return array('eerls', 'quuppa', 'gnss', 'gps', 'wifi');
    }

    private function isPositionTypeValid($positionType) {
        $acceptedValues = $this->getAcceptedPositionValues();
        if (in_array($positionType, $acceptedValues)) return true;
        return false;
    }

    private function getAcceptedPositionValues() {
        return array('absolute', 'relative');
    }

    public function actionIndex() {
        $this->render('index');
    }
    
    public function actionError() {
        ApiManager::errorResponse(ErrorMessage::ERR_BAD_REQUEST, null);
    }

}