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
        parent::__construct();
        
        $this->context = Context::getContext();
    }

    public function initContent()
    {
        $this->renderForm();
        parent::initContent();
        //$this->renderForm();
    }

    public function postProcess(){
        if(Tools::isSubmit('save_settings')){
            $id_settings = 1;
            $obj = new instagramDisplaySettings($id_settings);
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
                    echo 1;
                } else {
                    echo False;
                }
            } else {
                if($obj->update()){
                    echo 2;
                } else {
                    echo False;
                }
            }           
        }

        return parent::postProcess();
    }

    public function renderForm(){
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->override_folder = 'settings/';

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'save_settings';
        $helper->currentIndex = $this->context->link->getAdminLink('InstagramAdminSettings', false);
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => array('instagram_app_secret' => '',
                                    'instagram_app_id' => '',
                                    'instagram_code' => ''
                                ),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm() {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add Instagram Account'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram App ID'),
                        'name' => 'instagram_app_id',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram App Secret'),
                        'name' => 'instagram_app_secret',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram Code'),
                        'name' => 'instagram_code',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    public function renderList(){
        $id_settings = 1;
        $values = new instagramDisplaySettings($id_settings);

        $this->context->smarty->assign(array(
            'is_connected' => $this->db_checkIfAccessTokenExists(),
            'set_values' => $values,
        ));
        
        $output = $this->context->smarty->fetch(_PS_MODULE_DIR_.'instagram/views/templates/admin/settings.tpl');

        return $output;
    }

    private function db_checkIfAccessTokenExists(): bool{
        return !empty(DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'instagram`'));
    }
}