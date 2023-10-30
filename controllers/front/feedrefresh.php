<?php

class ArkonInstagramFeedRefreshModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        if(Tools::getValue('token') !== '90234gb8qf2gmi0ga2FGA'){
            http_response_code(500);
            die(json_encode(
                [
                    'message' => 'Invalid token',
                    'status' => 400
                ]
            ));
        }

        $message = $this->module->fetchImagesFromInstagram();

        header('Content-Type: application/json');
        if ($message) {
            $this->module->deleteLocalImages();
            $this->module->saveImagesLocally();

            http_response_code(200);
            die(json_encode(
                [
                    'message' => 'Images refreshed successfully',
                    'status' => 200
                ]
            ));
        } else {
            http_response_code(500);
            die(json_encode(
                [
                    'message' => 'Unable to refresh images',
                    'status' => 400
                ]
            ));
        }
    }
}