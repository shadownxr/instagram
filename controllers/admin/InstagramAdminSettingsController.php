<?php
class InstagramAdminSettingsController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
        $this->context = Context::getContext();
    }

    public function initContent()
    {
        parent::initContent();
        
    }

    public function postProcess(){
        if(Tools::isSubmit('save_settings')){
            $display_direction = Tools::getValue('display_direction');
        }
    }

    public function renderList(){
        $this->context->smarty->assign('is_connected',$this->db_checkIfAccessTokenExists());
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'instagram/views/templates/admin/settings.tpl');
    }

    private function db_checkIfAccessTokenExists(): bool{
        return !empty(DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'instagram`'));
    }
}