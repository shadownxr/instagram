<?php

class InstagramAjaxModuleFrontController extends ModuleFrontController {
    public function initContent(){
        $this->ajax = true;
        parent::initContent();
    }

    public function displayAjax(){
        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);
        $image_size = $settings->image_size;
        $images_per_gallery = $settings->images_per_gallery;
        $gap = $settings->gap;
        die(Tools::jsonEncode(array(
            'image_size' => $image_size,
            'images_per_gallery' => $images_per_gallery,
            'gap' => $gap
            ))
        );
    }
}