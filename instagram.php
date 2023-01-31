<?php 
/**
* NOTICE OF LICENSE
*
* This file is licensed under the Software License Agreement.
*
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* You must not modify, adapt or create derivative works of this source code
*
* @author Arkonsoft
* @copyright 2017-2023 Arkonsoft
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(_PS_MODULE_DIR_. 'instagram/defines.php');
include_once(_PS_MODULE_DIR_. 'instagram/instagramCurl.php');
require_once(_PS_MODULE_DIR_. 'instagram/classes/instagramDisplaySettings.php');
require_once(_PS_MODULE_DIR_. 'instagram/classes/instagramImages.php');

class Instagram extends Module {
    private string $message = '';
    private string $message_type = '';
    private string $instagram_code = '';
    private string $redirect_uri = '';

    public function __construct(){
        $this->name = 'instagram';
        $this->tab = 'social_media';
        $this->version = '1.0.0';
        $this->author = 'Arkonsoft';
        $this->author_uri = 'https://arkonsoft.pl/';
        $this->need_instance = 1;
        $this->bootstrap = 1;
        $this->dependencies = [];

        parent::__construct();

        $this->displayName = $this->l('Instagram Feed API');
        $this->description = $this->l('Module allows to display feed from Instagram on the front page');

        $this->confirmUninstall = $this->l('Are you sure? All data will be lost!');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    public function install(){
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        Configuration::updateValue('INSTAGRAM_APP_ID', '1234567890');
        Configuration::updateValue('INSTAGRAM_APP_SECRET', '1234567890');

        include(dirname(__FILE__).'/sql/install.php');

        if(parent::install()){
            $this->installTab();
            $this->initDefaultDisplaySettings();
            $this->registerHook('actionFrontControllerSetMedia');
            $this->registerHook('actionAdminControllerSetMedia');
            return true;
        }

        return false;
    }

    public function uninstall(){
        Configuration::deleteByName('INSTAGRAM_APP_ID');
        Configuration::deleteByName('INSTAGRAM_APP_SECRET');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall()
            && $this->uninstallTab()
            && $this->unregisterHook('actionFrontControllerSetMedia')
            && $this->unregisterHook('actionAdminControllerSetMedia');
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && $this->installTab()
        ;
    }

    public function disable($force_all = false)
    {
        return parent::disable($force_all)
            && $this->uninstallTab()
        ;
    }


    
    public function getContent()
    {
        $this->processConfiguration();
        $this->processDeletion();

        $instagram_app_id = Configuration::get('INSTAGRAM_APP_ID');
        $instagram_app_secret = Configuration::get('INSTAGRAM_APP_SECRET');

        $user = $this->getUserInfo();
        $username = '';

        if(!empty($user)){
            $username = $user['username'];
        }

        $this->context->smarty->assign(array(
            'module_dir' => $this->_path,
            'username' => $username,
            'instagram_app_id' => $instagram_app_id,
            'instagram_app_secret' => $instagram_app_secret,
            'instagram_code' => $this->instagram_code,
            'message' => $this->message,
            'message_type' => $this->message_type,
        ));

        $this->message = '';
        $this->message_type = '';

        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
    }

    private function processConfiguration(){
        if(Tools::isSubmit('add_account')){
            $instagram_app_id = Tools::getValue('instagram_app_id');
            $instagram_app_secret = Tools::getValue('instagram_app_secret');
            $this->instagram_code = Tools::getValue('instagram_code');
            $this->redirect_uri = Tools::getValue('redirect_uri');

            Configuration::updateValue('INSTAGRAM_APP_ID', $instagram_app_id);
            Configuration::updateValue('INSTAGRAM_APP_SECRET', $instagram_app_secret);
            
            $data = $this->fetchLongAccessToken();
            if(is_array($data)){
                $this->db_updateAccessToken($data);
            }
            $this->fetchImagesFromInstagram();
        }
    }

    private function processDeletion(){
        if(Tools::isSubmit('delete_account')){
            $response = $this->db_deleteAccessToken();
            if($response){
                $response = $this->db_deleteInstagramImages();
            
                if($response){
                    $this->message = "Account deleted successfully";
                    $this->message_type = CONFIRMATION_MESSAGE;
                    return;
                } else {
                    $this->message = "Unable to delete account";
                    $this->message_type = ERROR_MESSAGE;
                    return;
                }
            }
        }
    }

    private function installTab(){
        $response = true;
        $tabparent = "InstagramAdminConfig";
        $id_parent = Tab::getIdFromClassName($tabparent);

        if(!$id_parent){
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = "InstagramAdminConfig";
            $tab->name = array();
            foreach (Language::getLanguages() as $lang){
                $tab->name[$lang["id_lang"]] = "Instagram Settings";
            }
            $tab->id_parent = 0;
            $tab->module = $this->name;
            $response &= $tab->add();
            $id_parent = (int)$tab->id;
        }

        $subtabs = array(
            array(
                'class'=>'InstagramAdminSettings',
                'name'=>$this->l('Settings'),
                'id_parent'=>$id_parent
            ),
        );

        foreach($subtabs as $subtab){
            $idtab = (int)Tab::getIdFromClassName($subtab['class']);
            if($idtab <= 0){
                $tab = new Tab();
                $tab->active = 1;
                $tab->class_name = $subtab['class'];
                $tab->name = array();
                foreach (Language::getLanguages() as $lang){
                    $tab->name[(int)$lang["id_lang"]] = $subtab['name'];
                }
                $tab->id_parent = (int)$subtab['id_parent'];
                $tab->module = $this->name;
                $response &= $tab->add();
            }
        }
        return $response;
    }

    private function uninstallTab() {
        $list_tab = array('InstagramAdminSettings');

        foreach($list_tab as $id_tab){
            $id_tab = (int)Tab::getIdFromClassName($id_tab);
            if ($id_tab)
            {
                $tab = new Tab($id_tab);
                $tab->delete();
            }
        }

        $id_tabP = (int)Tab::getIdFromClassName('InstagramAdminConfig');

        if ($id_tabP){
            $tabP = new Tab($id_tabP);
            $tabP->delete();
        }

        return true;
    }
    
    protected function getConfigFormValues()
    {
        return array(
            'INSTAGRAM_APP_SECRET' => Configuration::get('INSTAGRAM_APP_SECRET'),
            'INSTAGRAM_APP_ID' => Configuration::get('INSTAGRAM_APP_ID'),
        );
    }

    public function hookActionAdminControllerSetMedia(){
        $this->context->controller->addJS(_PS_MODULE_DIR_ . "instagram/views/js/controllers/instagramadminsettings.js");
    }

    public function __call($name, $arguments){
        $display_style = new InstagramDisplaySettings(INSTAGRAM_CONFIG_ID);

        $this->context->smarty->assign(array(
            'images_data' => $this->db_getImagesData(),
            'display_style' => $display_style
        ));
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
        return $this->fetch(_PS_MODULE_DIR_.'instagram/views/templates/front/display.tpl');
    }

    private function fetchLongAccessToken(){
        $url = 'https://api.instagram.com/oauth/access_token';

        $data = array(
			'client_id' => Configuration::get('INSTAGRAM_APP_ID'),
			'client_secret' => Configuration::get('INSTAGRAM_APP_SECRET'),
			'grant_type' => 'authorization_code',
			'redirect_uri' => $this->redirect_uri,
			'code' => $this->instagram_code,
		);

        $fetch_data = InstagramCurl::fetch($url, $data);

        $short_access_token = '';
        $long_access_token = '';
        $user_id = '';
        $token_expire_date = '';

        if(array_key_exists('access_token', $fetch_data) && array_key_exists('user_id',$fetch_data)){
            $short_access_token = $fetch_data['access_token'];
            $user_id = $fetch_data['user_id'];
        } else if(array_key_exists('error_type', $fetch_data) && array_key_exists('error_message', $fetch_data)){
            $this->message = $fetch_data['error_message'];
            $this->message_type = ERROR_MESSAGE;
            return '';
        } else {
            $this->message = 'Can\'t get Short Access Token';
            $this->message_type = ERROR_MESSAGE;
            return '';
        }

        $url = 'https://graph.instagram.com/access_token?client_secret='.Configuration::get('INSTAGRAM_APP_SECRET')
                .'&access_token='.$short_access_token
                .'&grant_type=ig_exchange_token';

        $fetch_data = InstagramCurl::fetch($url);
        
        if(array_key_exists('access_token', $fetch_data) && array_key_exists('expires_in',$fetch_data)){
            $long_access_token = $fetch_data['access_token'];
            $token_expire_date = $fetch_data['expires_in'];
        } else if(array_key_exists('error_type', $fetch_data) && array_key_exists('error_message', $fetch_data)){
            $this->message = $fetch_data['error_message'];
            $this->message_type = ERROR_MESSAGE;
            return;
        } else {
            $this->message = 'Can\'t get Long Access Token';
            $this->message_type = ERROR_MESSAGE;
            return;
        }

        $this->message = 'Account successfully added';
        $this->message_type = CONFIRMATION_MESSAGE;

        return array(
            'access_token' => $long_access_token,
            'token_expires' => $token_expire_date,
            'user_id' => $user_id
        );
    }

    private function db_checkIfAccessTokenExists(): bool{
        return !empty(DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'instagram`'));
    }

    private function db_updateAccessToken($data){
        $response = false;
        if(array_key_exists('access_token', $data) && array_key_exists('token_expires', $data) && array_key_exists('user_id', $data)){
            if(!$this->db_checkIfAccessTokenExists()){
                $response = DB::getInstance()->execute(
                    'INSERT INTO `' . _DB_PREFIX_ . 
                    'instagram` (`id_instagram`, `user_id`, `access_token`, `token_expires`) 
                    VALUES ("'.INSTAGRAM_CONFIG_ID.'", "'.pSQL($data['user_id']).'", "'.pSQL($data['access_token']).'", "'.pSQL($data['token_expires']).'")'
                );
                return $response;
            } else {
                $response = DB::getInstance()->update('instagram', array(
                    'access_token' => $data['access_token'],
                    'token_expires' => $data['token_expires'],
                ));
                return $response;
            }
        } else {
            return false;
        }
        return true;
    }

    private function db_getAccessToken(): string{
        $response = DB::getInstance()->executeS('SELECT access_token FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram='.INSTAGRAM_CONFIG_ID);
        return $response[0]['access_token'];
    }

    private function db_getUserIdAndAccessToken(): array{
        $response = DB::getInstance()->executeS('SELECT user_id, access_token FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram='.INSTAGRAM_CONFIG_ID);
        return $response;
    }

    private function db_deleteAccessToken(): bool{
        $response = DB::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram='.INSTAGRAM_CONFIG_ID);
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

    private function db_getImagesData(){
        $response = DB::getInstance()->executeS('SELECT image_url, description FROM `' . _DB_PREFIX_ .'instagramimages`');
        return $response;
    }

    private function refreshAccessToken(){
        $response = DB::getInstance()->executeS('SELECT token_expires, creation_date FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram='.INSTAGRAM_CONFIG_ID);

        $expiration_time = (int)$response[0]['token_expires'] + idate('U',strtotime($response[0]['creation_date']));
        $today_time = date("U");

        $access_token = $this->db_getAccessToken();

        $month_in_seconds = 2629743;

        if(($expiration_time - $today_time) < $month_in_seconds){
            $url = 'https://graph.instagram.com/refresh_access_token?access_token='.$access_token
                .'&grant_type=ig_refresh_token';

            InstagramCurl::fetch($url);
        }
    }

    private function fetchImagesFromInstagram(): bool{
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

    private function getUserInfo(){
        $data = $this->db_getUserIdAndAccessToken();
        if(!empty($data)){
            $fields = 'username,media_count';
            $url = 'https://graph.instagram.com/'.$data[0]['user_id'].'?access_token='.$data[0]['access_token'].'&fields='.$fields;

            $user_info = InstagramCurl::fetch($url);

            return $user_info;
        } else {
            return false;
        }
    }

    private function initDefaultDisplaySettings(){
        $settings = new InstagramDisplaySettings(INSTAGRAM_CONFIG_ID);
        $settings->hook = 'displayHeader';
        $settings->image_height = 300;
        $settings->image_width = 300;
        $settings->flex_direction = 'row';
        $settings->title = 'Example title';
        $settings->image_margin = 0;
        $settings->image_border_radius = 0;
        $settings->show_title = false;
        $settings->show_description = false;
        $settings->description_alignment = 'column';
        $settings->max_images_fetched = 6;
        //Later if needed
        $settings->max_images_visible = 6;

        if($settings->add()){
            $this->registerHook($settings->hook);
        }
    }
}