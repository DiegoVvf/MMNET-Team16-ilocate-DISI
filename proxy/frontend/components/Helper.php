<?php
	 
class Helper
{
	public static function notifyMissingTranslation($event)
    {
    	if (Yii::app()->params['env.code'] == 'private') {
	        $body = implode(" ", array(
	            "Language: {$event->language}",
	            "Category: {$event->category}",
	            "Message: {$event->message}",
	        ));
			$translation = array();
			$translation['language'] = $event->language;
			$translation['category'] = $event->category;
			$translation['message'] = $event->message;
			if (!file_exists('missing-translations.txt'))
				file_put_contents('missing-translations.txt', '');
			$json = file_get_contents('missing-translations.txt');
			$content = json_decode($json, true);
			if ($content) {
				if (!array_key_exists($event->message, $content)) {
					$content[$event->message] = $translation;
				}
			}
			else {
				$content = array();
				$content[$event->message] = $translation;
			}
			$file = fopen('missing-translations.txt', 'w');
			fwrite($file, json_encode($content));
			fclose($file);
		}
    }
}