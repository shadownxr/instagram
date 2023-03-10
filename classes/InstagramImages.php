<?php

class InstagramImages extends ObjectModel {
    public $id;
    public string $image_id;
    public string $image_url;
    public string $description;

    public static $definition = array(
        'table' => 'instagramimages',
        'primary' => 'id',
        'multishop' => 'false',
        'fields' => array(
            'image_id' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255),
            'image_url' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 65535),
            'description' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 65535),
        )
    );
}