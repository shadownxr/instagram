<?php

class InstagramAdminConfigShortcutController extends ModuleAdminController {
    public function __construct(){
        $token = Tools::getAdminTokenLite('AdminModules');
        parent::__construct();
        if ($this->module->active)
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules&token='.$token.'&configure=instagram&tab_module=administration&module_name=instagram',false));
    }
}