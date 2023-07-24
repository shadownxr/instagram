<?php

class ArkonInstagramFeedRefreshModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $message = $this->module->fetchImagesFromInstagram();

        header('Content-Type: application/json');
        if ($message) {
            http_response_code(200);
            die(json_encode(
                [
                    'message' => 'Images refreshed successfully',
                    'status' => 200
                ]
            ));
        } else {
            http_response_code(400);
            die(json_encode(
                [
                    'message' => 'Unable to refresh images',
                    'status' => 400
                ]
            ));
        }
    }
}