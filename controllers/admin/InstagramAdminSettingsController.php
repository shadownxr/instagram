<?php
require_once(_PS_MODULE_DIR_. 'instagram/classes/InstagramDisplaySettings.php');

class InstagramAdminSettingsController extends ModuleAdminController
{
    public function __construct()
    {
        //$this->className = 'InstagramDisplaySettings';
        //$this->table = 'instagramdisplaysettings';
        //$this->identifier = InstagramDisplaySettings::$definition['primary'];
        /*$this->fields_list = array(
            'id' => array(
                'title' => 'ID',
                'type'  => 'int',
                'width' => 'auto',
                'orderby' => false,
            ),
            'title' => array(
                'title' => 'Title',
                'width' => 'auto',
                'orderby' => false,
            ),
            'flex_direction' => array(
                'title' => 'Flex Direction',
                'width' => 'auto',
                'orderby' => false,
            ),
        );*/
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
            $id_settings = 1;
            $obj = new instagramDisplaySettings($id_settings);
            $prev_hook = $obj->hook;
            $obj->hook = Tools::getValue('display_hook');
            $obj->flex_direction = Tools::getValue('display_direction');
            $obj->image_width = Tools::getValue('image_width');
            $obj->image_height = Tools::getValue('image_height');
            $obj->image_margin = Tools::getValue('image_margin');
            $obj->image_border_radius = Tools::getValue('image_border_radius');
            $obj->show_title = Tools::getValue('show_title');
            $obj->show_description = Tools::getValue('show_description');
            $obj->description_alignment = Tools::getValue('description_alignment');
            $obj->max_images_fetched = Tools::getValue('max_images_fetched');
            //Later if needed
            $obj->max_images_visible = Tools::getValue('max_images_visible');

            $obj->title = 'Next';

            if(!Validate::isLoadedObject($obj)){
                if($obj->add()) {
                    $this->module->registerHook($obj->hook);
                } else {
                    echo False;
                }
            } else {
                if($obj->update()){
                    $this->module->unregisterHook($prev_hook);
                    $this->module->registerHook($obj->hook);
                } else {
                    echo False;
                }
            }
        }

        if(Tools::isSubmit('refresh')){
            $this->fetchImagesFromInstagram();
        }

        return parent::postProcess();
    }

    public function renderList(){
        $id_settings = 1;
        $values = new instagramDisplaySettings($id_settings);
        $display_style = new InstagramDisplaySettings(1);
        $display_hooks = $this->db_getDisplayHooks();

        $this->context->smarty->assign(array(
            'is_connected' => $this->db_checkIfAccessTokenExists(),
            'images_data' => $this->db_getImagesData(),
            'display_style' => $display_style,
            'set_values' => $values,
            'display_hooks' => $display_hooks
        ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'instagram/views/templates/admin/settings.tpl');
    }

    private function db_getImagesData(){
        $res = DB::getInstance()->executeS('SELECT image_url, description FROM `' . _DB_PREFIX_ .'instagramimages`');
        return $res;
    }

    private function db_checkIfAccessTokenExists(): bool{
        return !empty(DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'instagram`'));
    }

    private function fetchImagesFromInstagram(){
        $data = $this->db_getUserIdAndAccessToken();
        $obj = new InstagramDisplaySettings(1);

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
                if($image_fetch_counter <= $obj->max_images_fetched){
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
        $res = DB::getInstance()->executeS('SELECT user_id, access_token FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram=1');
        return $res;
    }
    
    private function db_deleteInstagramImages(): bool{
        $res = DB::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .'instagramimages`');
        $res2 = DB::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ .'instagramimages` AUTO_INCREMENT=1');
        return $res && $res2;
    }

    private function db_getDisplayHooks(): array{
        $res = DB::getInstance()->executeS('SELECT id_hook, name FROM `' . _DB_PREFIX_ .'hook` WHERE name LIKE "display%" AND name NOT LIKE "displayAdmin%"');
        return $res;
    }
}