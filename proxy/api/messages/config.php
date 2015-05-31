<?php
	/**
	 * Configuration file for message translation.
	 * 
	 * Identifies all the messages that have not yet been translated and stores them in the 
	 * correspondent module file.
	 * The only step needed by the admin is to check the message files in /messages/(en,it) and 
	 * to translate those messages that are missing a translation.
	 * In order to start the process the command 'yiic /path/og/config/file' needs to be run.
	 * 
	 * @author carlo caprini <carlo.caprini@u-hopper.com>
	 */
	 
	return array(
		'language'=>'en',
	    'sourcePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../..',
	    'messagePath'=>dirname(__FILE__),
	    'languages'=>array('en', 'it'),
	    'fileTypes'=>array('php'),
	    'overwrite'=>true,
	    'removeOld'=>true,
	    'exclude'=>array(
	        '.svn',
	        '/app/data/i18n',
	        '/htdocs',
	        '/app/tests',
	        '/app/messages',
	        '/app/config',
	    ),
	);
?>