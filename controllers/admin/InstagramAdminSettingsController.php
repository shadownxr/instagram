<?php
require_once(_PS_MODULE_DIR_ . 'instagram/classes/InstagramDisplaySettings.php');

class Version {
    const DESKTOP = 'desktop';
    const MOBILE = 'mobile';
}
class InstagramAdminSettingsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;

        $this->context = Context::getContext();
        parent::__construct();
    }

    public function postProcess()
    {
        $this->processSettings();
    }

    #todo Refactor
    public function processSettings(){
        $version = false;
        $settings = false;

        if (Tools::isSubmit('save_desktop_settings')) {
            $settings = new instagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);
            $version = Version::DESKTOP;
        } else if (Tools::isSubmit('save_mobile_settings')){
            $settings = new instagramDisplaySettings(INSTAGRAM_MOBILE_CONFIG_ID);
            $version = Version::MOBILE;
        }

        if (is_string($version) && is_object($settings)) {
            $prev_hook = $settings->hook;
            $settings->hook = Tools::getValue($version.'_display_hook');
            $settings->display_style = Tools::getValue($version.'_display_style');
            $settings->image_size = Tools::getValue($version.'_image_size');
            $settings->show_title = Tools::getValue($version.'_show_title');
            $settings->max_images_fetched = Tools::getValue($version.'_max_images_fetched');
            $settings->images_per_gallery = Tools::getValue($version.'_images_per_gallery');
            $settings->gap = Tools::getValue($version.'_gap');
            $settings->grid_row = Tools::getValue($version.'_grid_row');
            $settings->grid_column = Tools::getValue($version.'_grid_column');
            $settings->title = Tools::getValue($version.'_title');

            if (!Validate::isLoadedObject($settings)) {
                if ($settings->add()) {
                    $this->module->registerHook($settings->hook);
                }
            } else {
                if ($settings->update()) {
                    $this->module->unregisterHook($prev_hook);
                    $this->module->registerHook($settings->hook);
                }
            }

            if(!$this->module->fetchImagesFromInstagram()){
                return;
            }
            $this->module->saveImagesLocally();
        }

        if (Tools::isSubmit('refresh')) {
            if(!$this->module->fetchImagesFromInstagram()){
                return;
            }
            $this->module->saveImagesLocally();
        }

        parent::postProcess();
    }

    public function renderList()
    {
        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);
        $m_settings = new InstagramDisplaySettings(INSTAGRAM_MOBILE_CONFIG_ID);
        $display_hooks = $this->db_getDisplayHooks();

        $this->context->smarty->assign(array(
            'is_connected' => $this->module->db_checkIfAccessTokenExists(),
            'images_data' => $this->module->db_getImagesData(),
            'settings' => $settings,
            'm_settings' => $m_settings,
            'display_hooks' => $display_hooks,
        ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'instagram/views/templates/admin/settings.tpl');
    }

    private function db_getDisplayHooks(): array
    {
        return DB::getInstance()->executeS('SELECT id_hook, name FROM `' . _DB_PREFIX_ . 'hook` WHERE name LIKE "display%" AND name NOT LIKE "displayAdmin%"');
    }
}