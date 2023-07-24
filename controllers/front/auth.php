<?php
class ArkonInstagramAuthModuleFrontController extends ModuleFrontController {
    public function init(){        
        $code = Tools::getValue('code');
        $cookie = new Cookie('ADMIN_LINK');
        $admin_redirect_url = $cookie->admin_link;
        $cookie->deleteSession();

        if($code){
		    Tools::redirectLink($admin_redirect_url.'&code='.$code);
        }
    }
}