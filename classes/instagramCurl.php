<?php
include_once(_PS_MODULE_DIR_. 'instagram/classes/define.php');

class InstagramCurl{
    public static function fetch(string $url, $data = false): array {
        $ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(is_array($data)){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

		$response = curl_exec($ch);
		curl_close($ch);

		$response_array = json_decode($response, true);

        return $response_array;
    }
}