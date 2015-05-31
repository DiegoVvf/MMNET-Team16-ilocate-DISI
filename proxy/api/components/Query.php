<?php

class Query 
{

    public static function run($url, $post_data=null, $headerData=null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ( $headerData ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);
        }
        if ( $post_data == null ) {
            curl_setopt($ch, CURLOPT_POST, false);
        } 
        else {
            foreach ( $post_data as $key => $value) {
                $post_items[] = $key . '=' . urlencode($value);
            }
            $post_string = implode ('&', $post_items);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        }
        $answer = curl_exec($ch);
        echo Yii::log('Query: .'.$url.'. Response: .'.$answer, 'debug', 'api.query');
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode != 200) {
            $error = 'Error running query '.$url.' .';
            $error .= 'Response was .'.$answer.' .';
            $error .= 'Code error was '.$httpcode.'.';
            echo Yii::log($error, 'error', 'api.query');
            return null;
        }
        return json_decode($answer, true);
    }

}