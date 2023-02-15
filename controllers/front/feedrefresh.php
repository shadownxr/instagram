<?php

class InstagramFeedRefreshModuleFrontController extends ModuleFrontController {
    public function initContent()
    {
        $message = $this->fetchImagesFromInstagram();
        if($message){
            header('Content-Type: application/json');
            http_response_code(200);

            die(json_encode(
                [
                    'message' => 'Images refreshed successfully',
                    'status' => 200
                ]
            ));
        } else {
            header('Content-Type: application/json');
            http_response_code(400);

            die(json_encode(
                [
                    'message' => 'Unable to refresh images',
                    'status' => 400
                ]
            ));
        }
    }
    private function fetchImagesFromInstagram(): bool{
        $data = $this->db_getUserIdAndAccessToken();
        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);

        if(!empty($data)){
            $images_url = [];
            $image_fetch_counter = 1;

            $fields = 'id,timestamp';
            $url = 'https://graph.instagram.com/'.$data[0]['user_id'].'/media?access_token='.$data[0]['access_token'].'&fields='.$fields;
            $images_id = InstagramCurl::fetch($url);

            $fields = 'media_url,media_type,caption';

            foreach($images_id['data'] as $image_id){
                $url = 'https://graph.instagram.com/'.$image_id['id'].'?access_token='.$data[0]['access_token'].'&fields='.$fields;
                $images_url[] = InstagramCurl::fetch($url);
            }

            foreach($images_url as $image){
                if($image_fetch_counter < $settings->max_images_fetched){
                    $img = new InstagramImages($image_fetch_counter);
                    $img->image_id = $image['id'];
                    $img->image_url = $image['media_url'];

                    if(array_key_exists('caption',$image)){
                        $img->description = $image['caption'];
                    } else {
                        $img->description = '';
                    }

                    if(Validate::isLoadedObject($img)){
                        $img->update();
                    } else {
                        $img->add();
                    }
                    ++$image_fetch_counter;
                } else {
                    break;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    private function db_getUserIdAndAccessToken(): array{
        $response = DB::getInstance()->executeS('SELECT user_id, access_token FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram='.INSTAGRAM_DESKTOP_CONFIG_ID);
        return $response;
    }
}