<?php

class InstagramAjaxModuleFrontController extends ModuleFrontController {
    public function initContent(){
        $this->ajax = true;
        parent::initContent();
    }

    public function displayAjax(){
        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);

        $array_settings = array();

        $array_settings[] = array(
            'image_size' => $settings->image_size,
            'images_per_gallery' => $settings->images_per_gallery,
            'gap' => $settings->gap,
            'display_style' => $settings->display_style
        );

        $settings = new InstagramDisplaySettings(INSTAGRAM_MOBILE_CONFIG_ID);

        $array_settings[] = array(
            'image_size' => $settings->image_size,
            'images_per_gallery' => $settings->images_per_gallery,
            'gap' => $settings->gap,
            'display_style' => $settings->display_style
        );

        die(Tools::jsonEncode($array_settings));
    }
}