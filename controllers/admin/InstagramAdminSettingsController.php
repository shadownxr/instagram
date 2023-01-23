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
        parent::initContent();
        
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

    public function renderList(){
        $id_settings = 1;
        $values = new instagramDisplaySettings($id_settings);

        $this->context->smarty->assign(array(
            'is_connected' => $this->db_checkIfAccessTokenExists(),
            'set_values' => $values,
        ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'instagram/views/templates/admin/settings.tpl');
    }

    private function db_checkIfAccessTokenExists(): bool{
        return !empty(DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'instagram`'));
    }
}