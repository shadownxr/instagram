<?php

use ArkonInstagram\Curl\InstagramCurl;
use ArkonInstagram\Encryption\Encryption;

class ArkonInstagramTokenRefreshModuleFrontController extends ModuleFrontController {

    public function initContent(){
        if(Tools::getValue('token') !== '90234gb8qf2gmi0ga2FGA'){
            http_response_code(500);
            die(json_encode(
                [
                    'message' => 'Invalid token',
                    'status' => 400
                ]
            ));
        }

        $message = $this->refreshAccessToken();

        switch ($message) {
            case 'Token refreshed':
                header('Content-Type: application/json');
                http_response_code(200);

                die(json_encode(
                    [
                        'message' => $message,
                        'status' => 200
                    ]
                ));
            
            case 'Unable to refresh access token':
                header('Content-Type: application/json');
                http_response_code(500);

                die(json_encode(
                    [
                        'error' => $message,
                        'status' => 500
                    ]
                ));

            case 'Token not one month old':
                header('Content-Type: application/json');
                http_response_code(200);

                die(json_encode(
                    [
                        'message' => $message,
                        'status' => 500
                    ]
                ));
        }
    }

    private function refreshAccessToken(){
        $response = DB::getInstance()->executeS('SELECT token_expires, creation_date FROM `' . _DB_PREFIX_ .'arkon_instagram_configuration` WHERE id_instagram='.INSTAGRAM_CONFIG_ID);

        $expiration_time = (int)$response[0]['token_expires'] + idate('U', strtotime($response[0]['creation_date']));
        $today_time = date("U");

        $configuration = new InstagramConfiguration(INSTAGRAM_CONFIG_ID);
        $access_token = Encryption::decrypt($configuration->access_token, $configuration->access_token_iv);

        $month_in_seconds = 2629743;

        if (($expiration_time - $today_time) < $month_in_seconds) {
            $url = 'https://graph.instagram.com/refresh_access_token?access_token=' . $access_token
                . '&grant_type=ig_refresh_token';

            $data = InstagramCurl::fetch($url);
            if(!empty($data)){
                $iv = '';
                $access_token = Encryption::encrypt($data['access_token'], true, $iv);

                DB::getInstance()->update('arkon_instagram_configuration', array(
                    'access_token' => $access_token,
                    'access_token_iv' => $iv,
                    'token_expires' => $data['expires_in'],
                ));
                return 'Token refreshed';
            } else {
                return 'Unable to refresh access token';
            }
        }
        return 'Token not one month old';
    }
}