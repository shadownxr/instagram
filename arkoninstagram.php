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

require_once(_PS_MODULE_DIR_ . 'arkoninstagram/defines.php');
require_once(_PS_MODULE_DIR_ . 'arkoninstagram/classes/InstagramDisplaySettings.php');
require_once(_PS_MODULE_DIR_ . 'arkoninstagram/classes/InstagramImages.php');
require_once(_PS_MODULE_DIR_ . 'arkoninstagram/classes/InstagramConfiguration.php');
require_once(_PS_MODULE_DIR_ . 'arkoninstagram/classes/InstagramApiConfiguration.php');
require_once __DIR__ . '/vendor/autoload.php';

use ArkonInstagram\Curl\InstagramCurl;
use ArkonInstagram\Encryption\Encryption;

class ArkonInstagram extends Module
{
    private $message = '';
    private $message_type = '';

    public function __construct()
    {
        $this->name = 'arkoninstagram';
        $this->tab = 'social_media';
        $this->version = '1.0.0';
        $this->author = 'Arkonsoft';
        $this->author_uri = 'https://arkonsoft.pl/';
        $this->need_instance = 1;
        $this->bootstrap = 1;
        $this->dependencies = [];

        parent::__construct();

        $this->displayName = $this->l('Instagram Image Gallery');
        $this->description = $this->l('Module displays gallery of images from Instagram feed on the front office');

        $this->confirmUninstall = $this->l('Are you sure? All data will be lost!');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    public function install(): bool
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return parent::install()
            && InstagramImages::createTable()
            && InstagramDisplaySettings::createTable()
            && InstagramConfiguration::createTable()
            && InstagramApiConfiguration::createTable()
            && Encryption::generateKey()
            && $this->createImageFolder()
            && $this->initDefaultDisplaySettings()
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('actionAdminControllerSetMedia');
    }

    public function uninstall(): bool
    {
        return parent::uninstall()
            && InstagramImages::dropTable()
            && InstagramDisplaySettings::dropTable()
            && InstagramConfiguration::dropTable()
            && InstagramApiConfiguration::dropTable()
            && $this->unregisterHook('actionFrontControllerSetMedia')
            && $this->unregisterHook('actionAdminControllerSetMedia');
    }

    public function enable($force_all = false): bool
    {
        return parent::enable($force_all)
            && $this->installTab();
    }

    public function disable($force_all = false): bool
    {
        return parent::disable($force_all)
            && $this->uninstallTab();
    }

    public function getContent()
    {
        $this->processConfiguration();
        $this->processAuthorization();
        $this->processDeletion();

        $username = Configuration::get('ARKON_INSTAGRAM_USERNAME');

        if (!empty($user)) {
            $username = $user['username'];
        }

        $redirect_uri = $this->context->link->getModuleLink('arkoninstagram', 'auth');

        $instagram_app_id = '';
        $instagram_app_secret = '';

        $app_config = new InstagramApiConfiguration(INSTAGRAM_CONFIG_ID);

        if (Validate::isLoadedObject($app_config)) {
            $instagram_app_id = Encryption::decrypt($app_config->app_id, $app_config->app_id_iv);
            $instagram_app_secret = Encryption::decrypt($app_config->app_secret, $app_config->app_secret_iv);
        }

        $redirect_cookie = false;
        $cookie = new Cookie('Admin_Link');
        if ($cookie->exists()) {
            $redirect_cookie = true;
        }

        $this->context->smarty->assign(array(
            'username' => $username,
            'instagram_app_id' => $instagram_app_id,
            'instagram_app_secret' => $instagram_app_secret,
            'message' => $this->message,
            'message_type' => $this->message_type,
            'redirect_uri' => $redirect_uri,
            'redirect_cookie' => $redirect_cookie
        ));

        $this->message = '';
        $this->message_type = '';

        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
    }

    private function processAuthorization()
    {
        $code = Tools::getValue('code');
        if ($code) {
            $data = $this->fetchLongAccessToken($code);
            if (!is_array($data)) {
                return;
            }
            $this->updateAccessToken($data);

            if (!$this->fetchImagesFromInstagram()) {
                return;
            }

            $this->deleteLocalImages();
            $this->saveImagesLocally();
            $this->getUserInfo();
        }
    }

    private function processConfiguration()
    {
        if (Tools::isSubmit('add_config')) {
            $instagram_app_id = Tools::getValue('instagram_app_id');
            $instagram_app_secret = Tools::getValue('instagram_app_secret');
            $this->setAdminRedirectLink();

            $app_id_iv = '';
            $app_secret_iv = '';

            $app_config = new InstagramApiConfiguration(INSTAGRAM_CONFIG_ID);
            $app_config->force_id = true;
            $app_config->app_id = Encryption::encrypt($instagram_app_id, true, $app_id_iv);
            $app_config->app_id_iv = $app_id_iv;
            $app_config->app_secret = Encryption::encrypt($instagram_app_secret, true, $app_secret_iv);
            $app_config->app_secret_iv = $app_secret_iv;
            $app_config->save();
        }
    }

    private function setAdminRedirectLink()
    {
        $token = Tools::getAdminTokenLite('AdminModules');
        $link = $this->context->link->getAdminLink('AdminModules&token=' . $token . '&configure=arkoninstagram&tab_module=administration&module_name=arkoninstagram', false);

        $cookie = new Cookie('ADMIN_LINK');
        $cookie->admin_link = $link;
        $cookie->write();
    }

    private function processDeletion()
    {
        if (Tools::isSubmit('delete_account')) {
            if (!$this->deleteAccessToken() || !$this->deleteInstagramImages()) {
                $this->message = $this->l("Unable to delete account");
                $this->message_type = ERROR_MESSAGE;
            } else {
                $this->message = $this->l("Account deleted successfully");
                $this->message_type = CONFIRMATION_MESSAGE;
            }
        }
    }

    private function installTab(): bool
    {
        $response = true;
        $tabparent = "AdminArkonInstagramConfig";
        $id_parent = Tab::getIdFromClassName($tabparent);

        if (!$id_parent) {
            $tab = new Tab();
            $tab->active = true;
            $tab->class_name = "AdminArkonInstagramConfig";
            $tab->name = array();
            foreach (Language::getLanguages() as $lang) {
                $tab->name[$lang["id_lang"]] = $this->l("Instagram Settings");
            }
            $tab->id_parent = 0;
            $tab->module = $this->name;
            $response &= $tab->add();
            $id_parent = (int)$tab->id;
        }

        $subtabs = array(
            array(
                'class' => 'AdminArkonInstagramConfigShortcut',
                'name' => $this->l('Config'),
                'id_parent' => $id_parent
            ),
            array(
                'class' => 'AdminArkonInstagramSettings',
                'name' => $this->l('Settings'),
                'id_parent' => $id_parent
            ),
        );

        foreach ($subtabs as $subtab) {
            $idtab = (int)Tab::getIdFromClassName($subtab['class']);
            if ($idtab <= 0) {
                $tab = new Tab();
                $tab->active = true;
                $tab->class_name = $subtab['class'];
                $tab->name = array();
                foreach (Language::getLanguages() as $lang) {
                    $tab->name[(int)$lang["id_lang"]] = $subtab['name'];
                }
                $tab->id_parent = (int)$subtab['id_parent'];
                $tab->module = $this->name;
                $response &= $tab->add();
            }
        }
        return $response;
    }

    private function uninstallTab(): bool
    {
        $list_tab = [
            'AdminArkonInstagramSettings',
            'AdminArkonInstagramConfigShortcut'
        ];

        foreach ($list_tab as $id_tab) {
            $id_tab = (int)Tab::getIdFromClassName($id_tab);
            if ($id_tab) {
                $tab = new Tab($id_tab);
                $tab->delete();
            }
        }

        $id_tabP = (int)Tab::getIdFromClassName('AdminArkonInstagramConfig');

        if ($id_tabP) {
            $tabP = new Tab($id_tabP);
            $tabP->delete();
        }

        return true;
    }

    private function createImageFolder(): bool
    {
        $path = _PS_IMG_DIR_ . 'modules/arkoninstagram/';
        if(file_exists($path)){
            return true;
        }
        if(mkdir($path, 0777, true)){
            return true;
        }
        return false;
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'arkoninstagram/views/css/admin.css');
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'arkoninstagram/views/js/admin.js');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $url = $this->context->link->getModuleLink('arkoninstagram', 'ajax');
        Media::addJsDef(['instagram_ajax_url' => $url]);
        $this->context->controller->requireAssets(['font-awesome']);
        $this->context->controller->registerStylesheet('instagram_css', '/modules/arkoninstagram/views/css/front.css');
        $this->context->controller->registerJavascript('instagram_js', '/modules/arkoninstagram/views/js/front.js');
    }

    public function __call($name, $arguments)
    {
        if ($this->context->isMobile()) {
            $settings = new InstagramDisplaySettings(INSTAGRAM_MOBILE_CONFIG_ID);

            $this->context->smarty->assign([
                'images_data' => $this->getImagesData(),
                'settings' => $settings,
                'version' => 'mobile',
                'username' => Configuration::get('ARKON_INSTAGRAM_USERNAME')
            ]);
        } else {
            $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);

            $this->context->smarty->assign([
                'images_data' => $this->getImagesData(),
                'settings' => $settings,
                'version' => 'desktop',
                'username' => Configuration::get('ARKON_INSTAGRAM_USERNAME')
            ]);
        }

        return $this->fetch(_PS_MODULE_DIR_ . 'arkoninstagram/views/templates/front/display.tpl');
    }

    /**
     * @param string $code
     * @return array|false
     */
    private function fetchShortAccessToken(string $code)
    {
        $url = 'https://api.instagram.com/oauth/access_token';

        $redirect_uri = $this->context->link->getModuleLink('arkoninstagram', 'auth');

        $app_config = new InstagramApiConfiguration(INSTAGRAM_CONFIG_ID);

        $data = [
            'client_id' => Encryption::decrypt($app_config->app_id, $app_config->app_id_iv),
            'client_secret' => Encryption::decrypt($app_config->app_secret, $app_config->app_secret_iv),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirect_uri,
            'code' => $code,
        ];

        $fetch_data = InstagramCurl::fetch($url, $data);

        if (array_key_exists('access_token', $fetch_data) && array_key_exists('user_id', $fetch_data)) {
            $short_access_token = $fetch_data['access_token'];
            $user_id = $fetch_data['user_id'];
        } else if (array_key_exists('error_type', $fetch_data) && array_key_exists('error_message', $fetch_data)) {
            $this->message = $fetch_data['error_message'];
            $this->message_type = ERROR_MESSAGE;
            return false;
        } else {
            $this->message = $this->l('Can\'t get Short Access Token');
            $this->message_type = ERROR_MESSAGE;
            return false;
        }

        return [
            'short_access_token' => $short_access_token,
            'user_id' => $user_id,
        ];
    }

    /**
     * @param string $code
     * @return array|false
     */
    private function fetchLongAccessToken(string $code)
    {
        if ($data = $this->fetchShortAccessToken($code)) {
            $short_access_token = $data['short_access_token'];
            $user_id = $data['user_id'];

            $app_config = new InstagramApiConfiguration(INSTAGRAM_CONFIG_ID);

            $url = 'https://graph.instagram.com/access_token?client_secret=' . Encryption::decrypt($app_config->app_secret, $app_config->app_secret_iv)
                . '&access_token=' . $short_access_token
                . '&grant_type=ig_exchange_token';

            $fetch_data = InstagramCurl::fetch($url);

            if (array_key_exists('access_token', $fetch_data) && array_key_exists('expires_in', $fetch_data)) {
                $long_access_token = $fetch_data['access_token'];
                $token_expire_date = $fetch_data['expires_in'];
            } else if (array_key_exists('error_type', $fetch_data) && array_key_exists('error_message', $fetch_data)) {
                $this->message = $fetch_data['error_message'];
                $this->message_type = ERROR_MESSAGE;
                return false;
            } else {
                $this->message = $this->l('Can\'t get Long Access Token');
                $this->message_type = ERROR_MESSAGE;
                return false;
            }

            $this->message = $this->l('Account successfully added');
            $this->message_type = CONFIRMATION_MESSAGE;

            return [
                'access_token' => $long_access_token,
                'token_expires' => $token_expire_date,
                'user_id' => $user_id
            ];
        } else {
            return false;
        }
    }

    public function checkIfAccessTokenExists(): bool
    {
        $configuration = new InstagramConfiguration(INSTAGRAM_CONFIG_ID);

        return Validate::isLoadedObject($configuration);
    }


    public function updateAccessToken($data): bool
    {
        if (empty($data)) {
            return false;
        }
        $user_id_iv = '';
        $access_token_iv = '';

        $instagram_configuration = new InstagramConfiguration(INSTAGRAM_CONFIG_ID);

        $instagram_configuration->user_id = Encryption::encrypt((string)$data['user_id'], true, $user_id_iv);
        $instagram_configuration->user_id_iv = $user_id_iv;
        $instagram_configuration->access_token = Encryption::encrypt($data['access_token'], true, $access_token_iv);
        $instagram_configuration->access_token_iv = $access_token_iv;
        $instagram_configuration->token_expires = $data['token_expires'];

        if (Validate::isLoadedObject($instagram_configuration)) {
            return $instagram_configuration->update();
        }

        $instagram_configuration->id = INSTAGRAM_CONFIG_ID;
        $instagram_configuration->force_id = true;
        return $instagram_configuration->add();
    }

    public function deleteAccessToken(): bool
    {
        $configuration = new InstagramConfiguration(INSTAGRAM_CONFIG_ID);

        if (!Validate::isLoadedObject($configuration)) {
            return false;
        }

        return $configuration->delete();
    }

    public function deleteInstagramImages(): bool
    {
        $images = new PrestaShopCollection('InstagramImages');
        $images = $images->getResults();

        $result = true;

        foreach ($images as $image) {
            $result &= $image->delete();
        }

        return $result;
    }

    public function getImagesData(): array
    {
        $images = new PrestaShopCollection('InstagramImages');
        $images = $images->getResults();

        if (empty($images)) {
            return [];
        }

        return $images;
    }

    public function fetchImagesFromInstagram(): bool
    {
        $data = new InstagramConfiguration(INSTAGRAM_CONFIG_ID);
        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);

        if (!Validate::isLoadedObject($data) || !Validate::isLoadedObject($settings)) {
            return false;
        }

        if ($settings->max_images_fetched < 1) {
            $this->message = $this->l('Invalid number of images to fetch');
            $this->message_type = ERROR_MESSAGE;
            return false;
        }

        if (!$this->deleteInstagramImages()) {
            return false;
        }

        $fields = 'id,timestamp';
        $url = 'https://graph.instagram.com/' . Encryption::decrypt($data->user_id, $data->user_id_iv) . '/media?access_token=' . Encryption::decrypt($data->access_token, $data->access_token_iv) . '&fields=' . $fields;
        $images_id = InstagramCurl::fetch($url);

        $image_fetch_counter = 0;
        $fields = 'media_url,media_type,caption,permalink';
        foreach ($images_id['data'] as $image_id) {
            if ($image_fetch_counter >= $settings->max_images_fetched) {
                break;
            }

            $url = 'https://graph.instagram.com/' . $image_id['id'] . '?access_token=' . Encryption::decrypt($data->access_token, $data->access_token_iv) . '&fields=' . $fields;
            $image = InstagramCurl::fetch($url);

            if(empty($image['media_type'])){
                continue;
            }

            if ($image['media_type'] !== "IMAGE") {
                continue;
            }

            $this->saveImageData($image);

            ++$image_fetch_counter;
        }

        return true;
    }

    /**
     * @return false
     */
    public function getUserInfo() : bool
    {
        $data = new InstagramConfiguration(INSTAGRAM_CONFIG_ID);

        if (Validate::isLoadedObject($data)) {
            $fields = 'username,media_count';
            $url = 'https://graph.instagram.com/' . Encryption::decrypt($data->user_id, $data->user_id_iv) . '?access_token=' . Encryption::decrypt($data->access_token, $data->access_token_iv) . '&fields=' . $fields;
            $response = InstagramCurl::fetch($url);
            if(!empty($response) && !empty($response['username'])){
                Configuration::updateValue('ARKON_INSTAGRAM_USERNAME', $response['username']);
                return true;
            }
        } else {
            return false;
        }
    }

    public function initDefaultDisplaySettings()
    {
        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);
        $settings->hook = 'displayHeader';
        $settings->image_size = 300;
        $settings->display_style = 'slider';
        $settings->title = 'Example title';
        $settings->show_title = false;
        $settings->max_images_fetched = 6;
        $settings->images_per_gallery = 2;
        $settings->gap = 15;
        $settings->grid_column = 4;
        $settings->grid_row = 4;

        $m_settings = $settings;

        if ($settings->add() && $m_settings->add()) {
            return $this->registerHook($settings->hook);
        }

        return false;
    }

    public function saveImageData(array $image)
    {
        $img = new InstagramImages();
        $img->image_id = $image['id'];
        $img->image_url = $image['media_url'];
        $img->permalink = $image['permalink'];

        if (array_key_exists('caption', $image)) {
            $img->description = $image['caption'];
        } else {
            $img->description = '';
        }

        $img->save();
    }

    public function saveImagesLocally()
    {
        $images = new PrestaShopCollection('InstagramImages');

        foreach ($images->getResults() as $image) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $image->image_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resp = curl_exec($ch);
            if (empty($resp)) {
                return;
            }

            curl_close($ch);

            if (!($fp = fopen(_PS_IMG_DIR_ . '/modules/arkoninstagram/' . $image->id . '.jpg', 'w'))) {
                return;
            }
            fwrite($fp, $resp);
            fclose($fp);
        }
    }

    public function deleteLocalImages(): bool
    {
        $img_folder = scandir(_PS_IMG_DIR_ . '/modules/arkoninstagram/');
        foreach ($img_folder as $file) {
            if (preg_match('/^([0-9]*).(jpg)/', $file)) {
                if (!unlink(_PS_IMG_DIR_ . '/modules/arkoninstagram/' . $file)) {
                    return false;
                }
            }
        }
        return true;
    }
}