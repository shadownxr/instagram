<?php
class InstagramAuthModuleFrontController extends ModuleFrontController {
    public function init(){        
        $code = Tools::getValue('code');

        $admin_redirect_url = Configuration::get('ADMIN_LINK');

        if($code){
		    Tools::redirectLink($admin_redirect_url.'&code='.$code);
        }
    }
}