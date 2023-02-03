<?php

class InstagramAjaxModuleFrontController extends ModuleFrontController {
    public function initContent(){
        $this->ajax = true;
        parent::initContent();
    }

    public function displayAjax(){
        $settings = new InstagramDisplaySettings(INSTAGRAM_CONFIG_ID);
        $image_width = $settings->image_width;
        $images_per_gallery = $settings->images_per_gallery;
        $gap = $settings->gap;
        die(Tools::jsonEncode(array(
            'image_width' => $image_width,
            'images_per_gallery' => $images_per_gallery,
            'gap' => $gap
            ))
        );
    }
}