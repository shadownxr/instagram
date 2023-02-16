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

include_once(_PS_MODULE_DIR_ . 'instagram/defines.php');
include_once(_PS_MODULE_DIR_ . 'instagram/instagramCurl.php');
require_once(_PS_MODULE_DIR_ . 'instagram/classes/instagramDisplaySettings.php');
require_once(_PS_MODULE_DIR_ . 'instagram/classes/instagramImages.php');

class Instagram extends Module
{
    private string $message = '';
    private string $message_type = '';

    public function __construct()
    {
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

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        Configuration::updateValue('INSTAGRAM_APP_ID', '1234567890');
        Configuration::updateValue('INSTAGRAM_APP_SECRET', '1234567890');
        Configuration::updateValue('ADMIN_LINK', '');

        include(dirname(__FILE__) . '/sql/install.php');

        if (parent::install()) {
            $this->installTab();
            $this->initDefaultDisplaySettings();
            $this->registerHook('actionFrontControllerSetMedia');
            $this->registerHook('actionAdminControllerSetMedia');
            return true;
        }

        return false;
    }

    public function uninstall()
    {
        Configuration::deleteByName('INSTAGRAM_APP_ID');
        Configuration::deleteByName('INSTAGRAM_APP_SECRET');
        Configuration::deleteByName('ADMIN_LINK');

        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall()
            && $this->uninstallTab()
            && $this->unregisterHook('actionFrontControllerSetMedia')
            && $this->unregisterHook('actionAdminControllerSetMedia')
            && $this->unregisterHook('moduleRoutes');
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && $this->installTab();
    }

    public function disable($force_all = false)
    {
        return parent::disable($force_all)
            && $this->uninstallTab();
    }


    public function getContent()
    {
        $this->processConfiguration();
        $this->processAuthorization();
        $this->processDeletion();

        $user = $this->getUserInfo();
        $username = '';

        if (!empty($user)) {
            $username = $user['username'];
        }

        $redirect_uri = $this->context->link->getModuleLink('instagram', 'auth');
        $instagram_app_id = Configuration::get('INSTAGRAM_APP_ID');
        $instagram_app_secret = Configuration::get('INSTAGRAM_APP_SECRET');

        $this->context->smarty->assign(array(
            'module_dir' => $this->_path,
            'username' => $username,
            'instagram_app_id' => $instagram_app_id,
            'instagram_app_secret' => $instagram_app_secret,
            'message' => $this->message,
            'message_type' => $this->message_type,
            'redirect_uri' => $redirect_uri,
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
            if (is_array($data)) {
                $this->db_updateAccessToken($data);
            }
            $this->fetchImagesFromInstagram();
        }
    }

    private function processConfiguration()
    {
        if (Tools::isSubmit('add_config')) {
            $instagram_app_id = Tools::getValue('instagram_app_id');
            $instagram_app_secret = Tools::getValue('instagram_app_secret');
            $this->setAdminRedirectLink();

            Configuration::updateValue('INSTAGRAM_APP_ID', $instagram_app_id);
            Configuration::updateValue('INSTAGRAM_APP_SECRET', $instagram_app_secret);
        }
    }

    private function setAdminRedirectLink()
    {
        $token = Tools::getAdminTokenLite('AdminModules');
        $link = $this->context->link->getAdminLink('AdminModules&token=' . $token . '&configure=instagram&tab_module=administration&module_name=instagram', false);

        Configuration::updateValue('ADMIN_LINK', $link);
    }

    private function processDeletion()
    {
        if (Tools::isSubmit('delete_account')) {
            $response = $this->db_deleteAccessToken();
            if ($response) {
                $response = $this->db_deleteInstagramImages();

                if ($response) {
                    $this->message = "Account deleted successfully";
                    $this->message_type = CONFIRMATION_MESSAGE;
                } else {
                    $this->message = "Unable to delete account";
                    $this->message_type = ERROR_MESSAGE;
                }
            }
        }
    }

    private function installTab()
    {
        $response = true;
        $tabparent = "InstagramAdminConfig";
        $id_parent = Tab::getIdFromClassName($tabparent);

        if (!$id_parent) {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = "InstagramAdminConfig";
            $tab->name = array();
            foreach (Language::getLanguages() as $lang) {
                $tab->name[$lang["id_lang"]] = "Instagram Settings";
            }
            $tab->id_parent = 0;
            $tab->module = $this->name;
            $response &= $tab->add();
            $id_parent = (int)$tab->id;
        }

        $subtabs = array(
            array(
                'class' => 'InstagramAdminConfigShortcut',
                'name' => $this->l('Config'),
                'id_parent' => $id_parent
            ),
            array(
                'class' => 'InstagramAdminSettings',
                'name' => $this->l('Settings'),
                'id_parent' => $id_parent
            ),
        );

        foreach ($subtabs as $subtab) {
            $idtab = (int)Tab::getIdFromClassName($subtab['class']);
            if ($idtab <= 0) {
                $tab = new Tab();
                $tab->active = 1;
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

    private function uninstallTab()
    {
        $list_tab = array('InstagramAdminSettings');

        foreach ($list_tab as $id_tab) {
            $id_tab = (int)Tab::getIdFromClassName($id_tab);
            if ($id_tab) {
                $tab = new Tab($id_tab);
                $tab->delete();
            }
        }

        $id_tabP = (int)Tab::getIdFromClassName('InstagramAdminConfig');

        if ($id_tabP) {
            $tabP = new Tab($id_tabP);
            $tabP->delete();
        }

        return true;
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addJS(_PS_MODULE_DIR_ . "instagram/views/js/controllers/instagramadminsettings.js");
        $this->context->controller->addCSS($this->_path . '/views/css/instagram.css');
        $this->context->controller->addJS(_PS_MODULE_DIR_ . "instagram/views/js/instagram.js");
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->addCSS($this->_path . '/views/css/instagram.css');
        $this->context->controller->addJS(_PS_MODULE_DIR_ . "instagram/views/js/instagram.js");
    }

    public function __call($name, $arguments)
    {
        $this->context->controller->addCSS($this->_path . '/views/css/instagram.css');

        if (!$this->context->isMobile()) {
            $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);

            $this->context->smarty->assign(array(
                'images_data' => $this->db_getImagesData(),
                'settings' => $settings,
            ));

            return $this->fetch(_PS_MODULE_DIR_ . 'instagram/views/templates/front/desktop.tpl');
        } else {
            $settings = new InstagramDisplaySettings(INSTAGRAM_MOBILE_CONFIG_ID);

            $this->context->smarty->assign(array(
                'images_data' => $this->db_getImagesData(),
                'settings' => $settings
            ));

            return $this->fetch(_PS_MODULE_DIR_ . 'instagram/views/templates/front/mobile.tpl');
        }
    }

    private function fetchShortAccessToken(string $code){
        $url = 'https://api.instagram.com/oauth/access_token';

        $redirect_uri = $this->context->link->getModuleLink('instagram', 'auth');

        $data = array(
            'client_id' => Configuration::get('INSTAGRAM_APP_ID'),
            'client_secret' => Configuration::get('INSTAGRAM_APP_SECRET'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirect_uri,
            'code' => $code,
        );

        $fetch_data = InstagramCurl::fetch($url, $data);

        if (array_key_exists('access_token', $fetch_data) && array_key_exists('user_id', $fetch_data)) {
            $short_access_token = $fetch_data['access_token'];
            $user_id = $fetch_data['user_id'];
        } else if (array_key_exists('error_type', $fetch_data) && array_key_exists('error_message', $fetch_data)) {
            $this->message = $fetch_data['error_message'];
            $this->message_type = ERROR_MESSAGE;
            return false;
        } else {
            $this->message = 'Can\'t get Short Access Token';
            $this->message_type = ERROR_MESSAGE;
            return false;
        }

        return array(
          'short_access_token' => $short_access_token,
          'user_id' => $user_id,
        );
    }

    /**
     * @param string $code
     * @return array|false
     */
    private function fetchLongAccessToken(string $code)
    {
        if($data = $this->fetchShortAccessToken($code)) {
            $short_access_token = $data['short_access_token'];
            $user_id = $data['user_id'];

            $url = 'https://graph.instagram.com/access_token?client_secret=' . Configuration::get('INSTAGRAM_APP_SECRET')
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
                $this->message = 'Can\'t get Long Access Token';
                $this->message_type = ERROR_MESSAGE;
                return false;
            }

            $this->message = 'Account successfully added';
            $this->message_type = CONFIRMATION_MESSAGE;

            return array(
                'access_token' => $long_access_token,
                'token_expires' => $token_expire_date,
                'user_id' => $user_id
            );
        } else {
            return false;
        }
    }

    public function db_checkIfAccessTokenExists(): bool
    {
        return !empty(DB::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'instagram`'));
    }


    public function db_updateAccessToken($data): bool
    {
        if (array_key_exists('access_token', $data) && array_key_exists('token_expires', $data) && array_key_exists('user_id', $data)) {
            if (!$this->db_checkIfAccessTokenExists()) {
                return DB::getInstance()->execute(
                    'INSERT INTO `' . _DB_PREFIX_ .
                    'instagram` (`id_instagram`, `user_id`, `access_token`, `token_expires`) 
                    VALUES ("' . INSTAGRAM_DESKTOP_CONFIG_ID . '", "' . pSQL($data['user_id']) . '", "' . pSQL($data['access_token']) . '", "' . pSQL($data['token_expires']) . '")'
                );
            } else {
                return DB::getInstance()->update('instagram', array(
                    'access_token' => $data['access_token'],
                    'token_expires' => $data['token_expires'],
                ));
            }
        } else {
            return false;
        }
    }

    public function db_getUserIdAndAccessToken(): array
    {
        return DB::getInstance()->executeS('SELECT user_id, access_token FROM `' . _DB_PREFIX_ . 'instagram` WHERE id_instagram=' . INSTAGRAM_DESKTOP_CONFIG_ID);
    }

    public function db_deleteAccessToken(): bool
    {
        return DB::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'instagram` WHERE id_instagram=' . INSTAGRAM_DESKTOP_CONFIG_ID);
    }

    public function db_deleteInstagramImages(): bool
    {
        $response = DB::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'instagramimages`');
        if ($response) {
            return DB::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'instagramimages` AUTO_INCREMENT=1');
        } else {
            return $response;
        }
    }

    public function db_getImagesData()
    {
        return DB::getInstance()->executeS('SELECT image_url, description FROM `' . _DB_PREFIX_ . 'instagramimages`');
    }

    public function fetchImagesFromInstagram(): bool
    {
        $data = $this->db_getUserIdAndAccessToken();
        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);

        if (!empty($data)) {
            $images_url = [];
            $image_fetch_counter = 1;

            $fields = 'id,timestamp';
            $url = 'https://graph.instagram.com/' . $data[0]['user_id'] . '/media?access_token=' . $data[0]['access_token'] . '&fields=' . $fields;
            $images_id = InstagramCurl::fetch($url);

            $fields = 'media_url,media_type,caption';

            foreach ($images_id['data'] as $image_id) {
                $url = 'https://graph.instagram.com/' . $image_id['id'] . '?access_token=' . $data[0]['access_token'] . '&fields=' . $fields;
                $images_url[] = InstagramCurl::fetch($url);
            }

            foreach ($images_url as $image) {
                if ($image_fetch_counter < $settings->max_images_fetched) {
                    $img = new InstagramImages($image_fetch_counter);
                    $img->image_id = $image['id'];
                    $img->image_url = $image['media_url'];

                    if (array_key_exists('caption', $image)) {
                        $img->description = $image['caption'];
                    } else {
                        $img->description = '';
                    }

                    if (Validate::isLoadedObject($img)) {
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

    public function getUserInfo()
    {
        $data = $this->db_getUserIdAndAccessToken();
        if (!empty($data)) {
            $fields = 'username,media_count';
            $url = 'https://graph.instagram.com/' . $data[0]['user_id'] . '?access_token=' . $data[0]['access_token'] . '&fields=' . $fields;

            return InstagramCurl::fetch($url);
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
            $this->registerHook($settings->hook);
        }
    }
}