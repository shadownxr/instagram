<?php
class InstagramAuthModuleFrontController extends ModuleFrontController {
    public function init(){        
        $data = Tools::getValue('data');

        $admin_redirect_url = Configuration::get('ADMIN_REDIRECT_URL');

        if($data){
		    Tools::redirect($admin_redirect_url.'&data='.$data);
        }
    }
}