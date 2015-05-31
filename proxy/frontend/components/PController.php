<?php
/**
 * Controller.php
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 7/23/12
 * Time: 12:55 AM
 */
class PController extends CController {

	public $breadcrumbs = array();
	public $menu = array();
	
	/**
	 * Init function.
	 * 
	 * Handle language selection.
	 */
	function init()
    {
        parent::init();
		$app = Yii::app();
		$app->language = 'it';
        if (isset($_GET['lang']))
        {
            $app->language = $_GET['lang'];
            $app->session['lang'] = $app->language;
        }
        else if (isset($app->session['lang']))
        {
            $app->language = $app->session['lang'];
        }
    }
}
