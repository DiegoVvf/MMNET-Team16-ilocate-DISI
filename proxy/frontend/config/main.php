<?php
/**
 * main.php
 *
 * This file holds tapoi configuration settings.
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 7/22/12
 * Time: 5:48 PM
 */
$frontendConfigDir = dirname(__FILE__);	

$root = $frontendConfigDir . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';

$params = require_once($frontendConfigDir . DIRECTORY_SEPARATOR . 'params.php');

// Setup some default path aliases. These alias may vary from projects.
Yii::setPathOfAlias('root', $root);
Yii::setPathOfAlias('common', $root . DIRECTORY_SEPARATOR . 'common');
Yii::setPathOfAlias('frontend', $root . DIRECTORY_SEPARATOR . 'frontend');
Yii::setPathOfAlias('www', $root. DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'www');

$mainLocalFile = $frontendConfigDir . DIRECTORY_SEPARATOR . 'main-local.php';
$mainLocalConfiguration = file_exists($mainLocalFile)? require($mainLocalFile): array();

$mainEnvFile = $frontendConfigDir . DIRECTORY_SEPARATOR . 'main-env.php';
$mainEnvConfiguration = file_exists($mainEnvFile) ? require($mainEnvFile) : array();

return CMap::mergeArray(
	array(
		'name' => 'i-locate',
		// @see http://www.yiiframework.com/doc/api/1.1/CApplication#basePath-detail
		'basePath' => 'frontend',
		// set parameters
		'params' => $params,
		// preload components required before running applications
		// @see http://www.yiiframework.com/doc/api/1.1/CModule#preload-detail
		'preload' => array('log', 'bootstrap'),
		// @see http://www.yiiframework.com/doc/api/1.1/CApplication#language-detail
		'sourceLanguage'=>'00',
		'language' => 'en',
		// 'language' => 'it',
		// uncomment if a theme is used
		/*'theme' => '',*/
		// setup import paths aliases
		// @see http://www.yiiframework.com/doc/api/1.1/YiiBase#import-detail
		// 'catchAllRequest'=>array('site/maintenance'),
		'catchAllRequest' => file_exists(dirname(__FILE__).'/../../admin/config/.maintenance') ? array('site/maintenance') : null,
		'import' => array(
			'common.components.*',
			'common.extensions.*',
			'common.models.*',
			'common.lib.Yii.caching.*',
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
			// 'log'=>array(
	            // 'class'=>'CLogRouter',
	            // 'routes'=>array(
	                // array(
	                    // 'class'=>'CFileLogRoute',
	                    // 'levels'=>'trace, info',
	                    // 'categories'=>'system.*',
	                // ),
	            // ),
	        // ),
			'user' => array(
				'class' => 'common.components.WebUser',
				'allowAutoLogin'=>true,
			),
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
				'errorAction'=>'site/error',
			),
			'urlManager' => array(
				'urlFormat' => 'path',
				'showScriptName' => false,
				'urlSuffix' => '/',
				'rules' => array(
					// array('api/registeruser', 'pattern'=>'registeruser', 'verb'=>'POST'),
				),
			),
			'bootstrap' => array(
				'class' => 'common.extensions.bootstrap.components.Bootstrap',
				'responsiveCss' => true,
			),
			// 'messages' => array(
		        // 'onMissingTranslation' => array('Helper', 'notifyMissingTranslation'),
		    // ),
		    'mailer' => array(
				'class' => 'application.extensions.mailer.EMailer',
			),
			/* make sure you have your cache set correctly before uncommenting */
			/* 'cache' => $params['cache.core'], */
			/* 'contentCache' => $params['cache.content'] */
			// 'cache' => array(
   //              'class' => 'CRedisCache',
   //              'hostname' => $params['cache.hostname'],
   //              'port' => $params['cache.port'],
   //              'database' => $params['cache.database'],
   //      	),
		),
	),
	CMap::mergeArray($mainEnvConfiguration, $mainLocalConfiguration)
);