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

        Configuration::updateValue('INSTAGRAM_AUTHORIZATION_CODE', '1234567890');
        Configuration::updateValue('INSTAGRAM_APP_ID', '1234567890');
        Configuration::updateValue('INSTAGRAM_APP_SECRET', '1234567890');
        Configuration::updateValue('INSTAGRAM_REDIRECT_URL', 'http://www.google.com/');

        return (
            parent::install()
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('displayHeader')
         );
    }

    public function uninstall(){
        Configuration::deleteByName('INSTAGRAM_AUTHORIZATION_CODE');
        Configuration::deleteByName('INSTAGRAM_APP_ID');
        Configuration::deleteByName('INSTAGRAM_APP_SECRET');
        Configuration::deleteByName('INSTAGRAM_REDIRECT_URL');

        return parent::uninstall();
    }

    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitCompareModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCompareModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'desc' => $this->l('Your App -> Instagram Basic Display -> Basic Display'),
                        'name' => 'INSTAGRAM_APP_ID',
                        'label' => $this->l('Instagram App ID'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'desc' => $this->l('Your App -> Instagram Basic Display -> Basic Display'),
                        'name' => 'INSTAGRAM_APP_SECRET',
                        'label' => $this->l('Instagram App Secret'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'desc' => $this->l('Enter authorization code that you have recieved by connecting your Instagram account (without #_)'),
                        'name' => 'INSTAGRAM_AUTHORIZATION_CODE',
                        'label' => $this->l('Code'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
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

        $this->getAccessToken();

        $this->displayError($this->trans('Error', [], 'Admin.Notifications.Error'));
    }

    public function hookDisplayHeader(){
        echo "Test22";
    }

    public function getAccessToken(){
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
        $user_id = '';

        $this->trans('test',[], 'Admin.Notifications');
        //var_dump($fetch_data);

        if(array_key_exists('access_token', $fetch_data) && array_key_exists('user_id',$fetch_data)){
            $short_access_token = $fetch_data['access_token'];
            $user_id = $fetch_data['user_id'];
        } else if(array_key_exists('error_type', $fetch_data) && array_key_exists('error_message', $fetch_data)){
            echo '<div class="alert alert-danger">'.$fetch_data['error_message'].'</div>'; 
        } else {
            var_dump('Can\'t get Accsess Token');
        }  
    }
}