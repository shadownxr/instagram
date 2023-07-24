<?php

class AdminArkonInstagramConfigShortcutController extends ModuleAdminController {
    public function __construct(){
        $token = Tools::getAdminTokenLite('AdminModules');
        parent::__construct();
        if ($this->module->active)
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules&token='.$token.'&configure=arkoninstagram&tab_module=administration&module_name=arkoninstagram',false));
    }
}