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

class Instagram extends Module {
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

        $this->displayName = $this->l('Custom content blocks');
        $this->description = $this->l('Module allows to add custom blocks to every front-office hook');

        $this->confirmUninstall = $this->l('Are you sure? All data will be lost!');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];

        //$this->admin_controller = 'AdminArkonCustomBlocksSettings';
    }

    public function install(){
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return (
            parent::install()
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('displayHeader')
         );
    }

    public function uninstall(){
        return parent::uninstall();
    }

    public function hookDisplayHeader(){
        echo "Test22";
    }
}