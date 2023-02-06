<?php
require_once(_PS_MODULE_DIR_. 'instagram/classes/InstagramDisplaySettings.php');

class InstagramAdminSettingsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        
        $this->context = Context::getContext();
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        
    }

    public function postProcess(){
        if(Tools::isSubmit('save_settings')){
            $settings = new instagramDisplaySettings(INSTAGRAM_CONFIG_ID);
            $prev_hook = $settings->hook;
            $settings->hook = Tools::getValue('display_hook');
            $settings->flex_direction = Tools::getValue('display_direction');
            $settings->image_size = Tools::getValue('image_size');
            $settings->image_margin = Tools::getValue('image_margin');
            $settings->image_border_radius = Tools::getValue('image_border_radius');
            $settings->show_title = Tools::getValue('show_title');
            $settings->show_description = Tools::getValue('show_description');
            $settings->description_alignment = Tools::getValue('description_alignment');
            $settings->max_images_fetched = Tools::getValue('max_images_fetched');
            $settings->images_per_gallery = Tools::getValue('images_per_gallery');
            $settings->gap = Tools::getValue('gap');

            $settings->title = 'Next';

            if(!Validate::isLoadedObject($settings)){
                if($settings->add()) {
                    $this->module->registerHook($settings->hook);
                }
            } else {
                if($settings->update()){
                    $this->module->unregisterHook($prev_hook);
                    $this->module->registerHook($settings->hook);
                }
            }
        }

        if(Tools::isSubmit('refresh')){
            $this->fetchImagesFromInstagram();
        }

        return parent::postProcess();
    }

    public function renderList(){
        $settings = new InstagramDisplaySettings(INSTAGRAM_CONFIG_ID);
        $display_hooks = $this->db_getDisplayHooks();

        $this->context->smarty->assign(array(
            'is_connected' => $this->db_checkIfAccessTokenExists(),
            'images_data' => $this->db_getImagesData(),
            'settings' => $settings,
            'display_hooks' => $display_hooks
        ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'instagram/views/templates/admin/settings.tpl');
    }

    private function db_getImagesData(){
        $response = DB::getInstance()->executeS('SELECT image_url, description FROM `' . _DB_PREFIX_ .'instagramimages`');
        return $response;
    }

    private function db_checkIfAccessTokenExists(): bool{
        return !empty(DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'instagram`'));
    }

    private function fetchImagesFromInstagram(){
        $data = $this->db_getUserIdAndAccessToken();
        $settings = new InstagramDisplaySettings(INSTAGRAM_CONFIG_ID);

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

            $this->db_deleteInstagramImages();

            foreach($images_url as $image){
                if($image_fetch_counter <= $settings->max_images_fetched){
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
        $response = DB::getInstance()->executeS('SELECT user_id, access_token FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram='.INSTAGRAM_CONFIG_ID);
        return $response;
    }
    
    private function db_deleteInstagramImages(): bool{
        $response = DB::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .'instagramimages`');
        if($response){
            $response = DB::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ .'instagramimages` AUTO_INCREMENT=1');
            return $response;
        } else {
            return $response;
        }
    }

    private function db_getDisplayHooks(): array{
        $response = DB::getInstance()->executeS('SELECT id_hook, name FROM `' . _DB_PREFIX_ .'hook` WHERE name LIKE "display%" AND name NOT LIKE "displayAdmin%"');
        return $response;
    }
}