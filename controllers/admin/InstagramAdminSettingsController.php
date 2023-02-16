<?php
require_once(_PS_MODULE_DIR_ . 'instagram/classes/InstagramDisplaySettings.php');

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

    public function postProcess()
    {
        $this->processDesktopSettings();
        $this->processMobileSettings();
    }

    private function processDesktopSettings()
    {
        if (Tools::isSubmit('save_desktop_settings')) {
            $settings = new instagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);
            $prev_hook = $settings->hook;
            $settings->hook = Tools::getValue('display_hook');
            $settings->display_style = Tools::getValue('display_style');
            $settings->image_size = Tools::getValue('image_size');
            $settings->show_title = Tools::getValue('show_title');
            $settings->max_images_fetched = Tools::getValue('max_images_fetched');
            $settings->images_per_gallery = Tools::getValue('images_per_gallery');
            $settings->gap = Tools::getValue('gap');
            $settings->grid_row = Tools::getValue('grid_row');
            $settings->grid_column = Tools::getValue('grid_column');

            $settings->title = 'Next';

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
        }

        if (Tools::isSubmit('refresh')) {
            $this->module->fetchImagesFromInstagram();
        }

        return parent::postProcess();
    }

    private function processMobileSettings()
    {
        if (Tools::isSubmit('save_mobile_settings')) {
            $settings = new instagramDisplaySettings(INSTAGRAM_MOBILE_CONFIG_ID);
            $prev_hook = $settings->hook;
            $settings->hook = Tools::getValue('m_display_hook');
            $settings->display_style = Tools::getValue('m_display_style');
            $settings->image_size = Tools::getValue('m_image_size');
            $settings->show_title = Tools::getValue('m_show_title');
            $settings->max_images_fetched = Tools::getValue('m_max_images_fetched');
            $settings->images_per_gallery = Tools::getValue('m_images_per_gallery');
            $settings->gap = Tools::getValue('m_gap');
            $settings->grid_row = Tools::getValue('m_grid_row');
            $settings->grid_column = Tools::getValue('m_grid_column');

            $settings->title = 'Next';

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
        }

        if (Tools::isSubmit('refresh')) {
            $this->module->fetchImagesFromInstagram();
        }

        return parent::postProcess();
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
            'display_hooks' => $display_hooks
        ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'instagram/views/templates/admin/settings.tpl');
    }

    private function db_getDisplayHooks(): array
    {
        return DB::getInstance()->executeS('SELECT id_hook, name FROM `' . _DB_PREFIX_ . 'hook` WHERE name LIKE "display%" AND name NOT LIKE "displayAdmin%"');
    }
}