<?php
/**
 * main.php
 *
 * This file holds api configuration settings.
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 7/22/12
 * Time: 5:48 PM
 */
$apiConfigDir = dirname(__FILE__);

$root = $apiConfigDir . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';

$params = require_once($apiConfigDir . DIRECTORY_SEPARATOR . 'params.php');

// require_once($root . '/common/extensions/facebook/facebook.php');

// Setup some default path aliases. These alias may vary from projects.
Yii::setPathOfAlias('root', $root);
Yii::setPathOfAlias('common', $root . DIRECTORY_SEPARATOR . 'common');
Yii::setPathOfAlias('api', $root . DIRECTORY_SEPARATOR . 'api');
Yii::setPathOfAlias('www', $root. DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'www');
Yii::setPathOfAlias('MQTT', $root. DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'extensions/phpMQTT');

$mainLocalFile = $apiConfigDir . DIRECTORY_SEPARATOR . 'main-local.php';
$mainLocalConfiguration = file_exists($mainLocalFile)? require($mainLocalFile): array();

$mainEnvFile = $apiConfigDir . DIRECTORY_SEPARATOR . 'main-env.php';
$mainEnvConfiguration = file_exists($mainEnvFile) ? require($mainEnvFile) : array();

return CMap::mergeArray(
    array(
        'name' => 'i-locate API',
        // @see http://www.yiiframework.com/doc/api/1.1/CApplication#basePath-detail
        'basePath' => 'api',
        // set parameters
        'params' => $params,
        // preload components required before running applications
        // @see http://www.yiiframework.com/doc/api/1.1/CModule#preload-detail
        'preload' => array('log', 'bootstrap'),
        // @see http://www.yiiframework.com/doc/api/1.1/CApplication#language-detail
        // 'sourceLanguage'=>'00',
        // 'language' => 'en',
        // 'language' => 'it',
        // uncomment if a theme is used
        /*'theme' => '',*/
        // setup import paths aliases
        // @see http://www.yiiframework.com/doc/api/1.1/YiiBase#import-detail
        // 'catchAllRequest'=>array('site/maintenance'),
        // 'catchAllRequest' => file_exists(dirname(__FILE__).'/../../common/config/.maintenance') ? array('site/maintenance') : null,
        'import' => array(
            'common.components.*',
            'common.extensions.*',
            'common.models.*',
            // uncomment if behaviors are required
            // you can also import a specific one
            /* 'common.extensions.behaviors.*', */
            // uncomment if validators on common folder are required
            /* 'common.extensions.validators.*', */
            'application.components.*',
            'application.controllers.*',
            'application.models.*'
        ),
        'modules' => array(
            'gii' => array(
                'class' => 'common.lib.Yii.gii.GiiModule',
                'password' => 'admin',
                'generatorPaths' => array(
                    'bootstrap.gii'
                ),
            ),
        ),
        /* uncomment and set if required */
        // @see http://www.yiiframework.com/doc/api/1.1/CModule#setModules-detail
        /* 'modules' => array(), */
        'components' => array(
            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                        'categories'=>'system.*',
                        'logFile'=>'system.log',
                    ),
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning, info',
                        'categories'=>'api.*',
                        'logFile'=>'api.log',
                    ),
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'debug',
                        'categories'=>'api.*',
                        'logFile'=>'debug.log',
                    ),
                ),
            ),
            'user' => array(
                'class' => 'common.components.WebUser',
                'allowAutoLogin'=>true,
            ),
            // 'facebook'=>array( 
   //              'class' => 'ext.yii-facebook-opengraph.SFacebook', 
   //              'appId'=>'My_Facebook_AppID', // needed for JS SDK, Social Plugins and PHP      SDK
   //              'secret'=>'My_Facebook_App_Secret',
   //          ),
            'db' => array(
                'connectionString' => $params['db.connectionString'],
                'username' => $params['db.username'],
                'password' => $params['db.password'],
                'schemaCachingDuration' => YII_DEBUG ? 0 : 86400000, // 1000 days
                'enableParamLogging' => YII_DEBUG,
                'charset' => 'utf8',
            ),
            'errorHandler' => array(
                // @see http://www.yiiframework.com/doc/api/1.1/CErrorHandler#errorAction-detail
                'errorAction'=>'api/error',
            ),
            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'urlSuffix' => '/',
                'rules' => array(
                    array('api/locationbasedondata', 'pattern'=>'location', 'verb'=>'POST'),
                    array('api/camera', 'pattern'=>'cameraupdate', 'verb'=>'POST'),
                    array('api/locationbasedondata', 'pattern'=>'locationbasedondata', 'verb'=>'POST'),
                    array('api/getlocation', 'pattern'=>'getlocation', 'verb'=>'GET'),
                    array('api/quuppapositions', 'pattern'=>'quuppapositions/<deployment:[-\.\w]+>', 'verb'=>'POST'),
                    array('api/registerlocation', 'pattern'=>'registerlocation', 'verb'=>'POST'),
                    array('api/unregisterlocation', 'pattern'=>'unregisterlocation', 'verb'=>'POST'),
                    array('api/getobjectparameters', 'pattern'=>'getobjectparameters', 'verb'=>'GET'),
                    array('api/getestimatedlocation', 'pattern'=>'getestimatedlocation', 'verb'=>'GET'),
                    array('api/getquuppaparameters', 'pattern'=>'get/quuppa/parameters', 'verb'=>'GET'),
                ),
            ),
            'bootstrap' => array(
                'class' => 'common.extensions.bootstrap.components.Bootstrap',
                'responsiveCss' => true,
            ),
            /* make sure you have your cache set correctly before uncommenting */
            /* 'cache' => $params['cache.core'], */
            /* 'contentCache' => $params['cache.content'] */
        ),
    ),
    CMap::mergeArray($mainEnvConfiguration, $mainLocalConfiguration)
);