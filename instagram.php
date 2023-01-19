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

include_once(_PS_MODULE_DIR_. 'instagram/classes/instagramCurl.php');

class Instagram extends Module {
    private string $message = '';
    private string $message_type = '';
    private string $instagram_code = '';

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
        $this->description = $this->l('Module allows to add custom blocks to every front-office hook');

        $this->confirmUninstall = $this->l('Are you sure? All data will be lost!');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];

        //$this->admin_controller = 'AdminArkonCustomBlocksSettings';
    }

    public function install(){
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        Configuration::updateValue('INSTAGRAM_APP_ID', '1234567890');
        Configuration::updateValue('INSTAGRAM_APP_SECRET', '1234567890');
        Configuration::updateValue('INSTAGRAM_REDIRECT_URL', 'http://www.google.com/');

        include(dirname(__FILE__).'/sql/install.php');

        return (
            parent::install()
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('displayHeader')
         );
    }

    public function uninstall(){
        Configuration::deleteByName('INSTAGRAM_APP_ID');
        Configuration::deleteByName('INSTAGRAM_APP_SECRET');
        Configuration::deleteByName('INSTAGRAM_REDIRECT_URL');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    public function getContent()
    {
        /*if (((bool)Tools::isSubmit('add_account')) == true) {
            $this->postProcess();
        }*/

        $this->processConfiguration();
        $this->processDeletion();

        $instagram_app_id = Configuration::get('INSTAGRAM_APP_ID');
        $instagram_app_secret = Configuration::get('INSTAGRAM_APP_SECRET');

        $user = $this->getUserInfo();
        $username = '';

        if(is_array($user)){
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

        //$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
    }

    private function processConfiguration(){
        if(Tools::isSubmit('add_account')){
            $instagram_app_id = Tools::getValue('instagram_app_id');
            $instagram_app_secret = Tools::getValue('instagram_app_secret');
            $this->instagram_code = Tools::getValue('instagram_code');

            Configuration::updateValue('INSTAGRAM_APP_ID', $instagram_app_id);
            Configuration::updateValue('INSTAGRAM_APP_SECRET', $instagram_app_secret);
            
            $data = $this->fetchLongAccessToken();
            if(is_array($data)){
                $this->db_updateAccessToken($data);
            }
        }
    }

    private function processDeletion(){
        if(Tools::isSubmit('delete_account')){
            $res = $this->db_deleteAccessToken();

            if($res){
                $this->message = "Account deleted successfully";
                $this->message_type = "success";
            } else {
                $this->message = "Unable to delete account";
                $this->message_type = "error";
            }
        }
    }

    protected function getConfigFormValues()
    {
        return array(
            'INSTAGRAM_AUTHORIZATION_CODE' => Configuration::get('INSTAGRAM_AUTHORIZATION_CODE'),
            'INSTAGRAM_APP_SECRET' => Configuration::get('INSTAGRAM_APP_SECRET'),
            'INSTAGRAM_APP_ID' => Configuration::get('INSTAGRAM_APP_ID'),
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }

        $data = $this->fetchLongAccessToken();
        /*$data = array(
            'user_id'=>'17841457282774580',
            'access_token'=>'Nowy Token',
            'token_expires'=>'5118381'
        );*/
        //$res = $this->updateAccessToken($data);
        
        $res = $this->db_updateAccessToken($data);
        if($res){
            echo "Update successfull";
        } else {
            echo "Update failed";
        }

        $this->displayError($this->trans('Error', [], 'Admin.Notifications.Error'));
    }

    public function hookDisplayHeader(){
        $this->getUserInfo();
        $this->context->smarty->assign(array('images_url' => $this->getImagesUrl()));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'instagram/views/templates/front/displayHeader.tpl');
    }

    private function fetchLongAccessToken(){
        $url = 'https://api.instagram.com/oauth/access_token';
        $data = array(
			'client_id' => Configuration::get('INSTAGRAM_APP_ID'),
			'client_secret' => Configuration::get('INSTAGRAM_APP_SECRET'),
			'grant_type' => 'authorization_code',
			'redirect_uri' => 'https://www.google.com/',
			'code' => Configuration::get('INSTAGRAM_AUTHORIZATION_CODE'),
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
            $this->message_type = 'error';
            return '';
        } else {
            $this->message = 'Can\'t get Short Access Token';
            $this->message_type = 'error';
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
            $this->message_type = 'error';
            return;
        } else {
            $this->message = 'Can\'t get Long Access Token';
            $this->message_type = 'error';
            return;
        }

        $this->message = 'Account successfully added';
        $this->message_type = 'confirmation';

        return array(
            'access_token' => $long_access_token,
            'token_expires' => $token_expire_date,
            'user_id' => $user_id
        );
    }

    private function checkIfAccessTokenExists(): bool{
        return !empty(DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'instagram`'));
    }

    private function db_updateAccessToken($data){
        $res = false;
        if(array_key_exists('access_token', $data) && array_key_exists('token_expires', $data) && array_key_exists('user_id', $data)){
            if(!$this->checkIfAccessTokenExists()){
                $id = 1;
                $res = DB::getInstance()->execute(
                        'INSERT INTO `' . _DB_PREFIX_ . 
                        'instagram` (`id_instagram`, `user_id`, `access_token`, `token_expires`) 
                        VALUES ("'.(int)$id.'", "'.pSQL($data['user_id']).'", "'.pSQL($data['access_token']).'", "'.pSQL($data['token_expires']).'")');
            } else {
                $res = DB::getInstance()->update('instagram', array(
                    'access_token' => $data['access_token'],
                    'token_expires' => $data['token_expires'],
                ));
            }
        } else {
            return false;
        }
        return true;
    }

    private function db_getUserId(): string{
        $res = DB::getInstance()->executeS('SELECT user_id FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram=1');
        return $res[0]['user_id'];
    }
    private function db_getAccessToken(): string{
        $res = DB::getInstance()->executeS('SELECT access_token FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram=1');
        return $res[0]['access_token'];
    }

    private function db_getUserIdAndAccessToken(): array{
        $res = DB::getInstance()->executeS('SELECT user_id, access_token FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram=1');
        return $res;
    }

    private function db_deleteAccessToken(): bool{
        $res = DB::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram=1');
        return $res;
    }

    private function refreshAccessToken(){
        $res = DB::getInstance()->executeS('SELECT token_expires, creation_date FROM `' . _DB_PREFIX_ .'instagram` WHERE id_instagram=1');

        $expiration_time = (int)$res[0]['token_expires'] + idate('U',strtotime($res[0]['creation_date']));
        $today_time = date("U");

        $access_token = $this->getAccessToken();

        $month_in_seconds = 2629743;

        if(($expiration_time - $today_time) < $month_in_seconds){
            $url = 'https://graph.instagram.com/refresh_access_token?access_token='.$access_token
                .'&grant_type=ig_refresh_token';

            InstagramCurl::fetch($url);
        }
    }

    private function getImagesUrl(){
        $data = $this->db_getUserIdAndAccessToken();
        if(!empty($data)){
            $images_url = [];

            $fields = 'id,timestamp';
            $url = 'https://graph.instagram.com/'.$data[0]['user_id'].'/media?access_token='.$data[0]['access_token'].'&fields='.$fields;
            $images_id = InstagramCurl::fetch($url);

            $fields = 'media_url,media_type,caption';

            foreach($images_id['data'] as $image_id){
                $url = 'https://graph.instagram.com/'.$image_id['id'].'?access_token='.$data[0]['access_token'].'&fields='.$fields;
                $images_url[] = InstagramCurl::fetch($url);
            }

            return $images_url;
        } else {
            return;
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
            return;
        }
    }
}