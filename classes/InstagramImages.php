<?php

class InstagramImages extends ObjectModel {
    public $id;
    public $image_id;
    public $image_url;

    public static $definition = array(
        'table' => 'instagramimages',
        'primary' => 'id',
        'multishop' => 'false',
        'fields' => array(
            'image_id' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255),
            'image_url' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 65535),
        )
    );
}