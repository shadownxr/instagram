<?php

use ArkonInstagram\Encryption;
use Curl\InstagramCurl;

class ArkonInstagramTokenRefreshModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
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

    private function refreshAccessToken()
    {
        $response = DB::getInstance()->executeS('SELECT token_expires, creation_date FROM `' . _DB_PREFIX_ . 'arkon_instagram_configuration` WHERE id_instagram=' . INSTAGRAM_CONFIG_ID);

        $expiration_time = (int)$response[0]['token_expires'] + idate('U', strtotime($response[0]['creation_date']));
        $today_time = date("U");

        $access_token = $this->getAccessToken();

        $month_in_seconds = 2629743;

        if (($expiration_time - $today_time) < $month_in_seconds) {
            $url = 'https://graph.instagram.com/refresh_access_token?access_token=' . $access_token
                . '&grant_type=ig_refresh_token';

            $data = InstagramCurl::fetch($url);
            $iv = '';

            if (!empty($data)) {
                $response = DB::getInstance()->update('arkon_instagram_configuration', array(
                    'access_token' => Encryption::encrypt($data['access_token'], true, $iv),
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

    private function getAccessToken(): string
    {
        $response = DB::getInstance()->executeS('SELECT access_token, access_token_iv FROM `' . _DB_PREFIX_ . 'arkon_instagram_configuration` WHERE id_instagram=' . INSTAGRAM_CONFIG_ID);
        var_dump($response);
        return Encryption::decrypt($response[0]['access_token'], $response[0]['access_token_iv']);
    }
}