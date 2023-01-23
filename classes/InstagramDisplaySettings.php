<?php
class InstagramDisplaySettings extends ObjectModel {
    public $id;
    public $title;
    public $flex_direction;
    public $image_height;
    public $image_width;
    public $image_margin;
    public $image_border_radius;
    public $show_title;
    public $show_description;
    public $description_alignment;
    public $max_images_fetched;
    public $max_images_visible;

    public static $definition = array(
        'table' => 'instagramdisplaysettings',
        'primary' => 'id',
        'multishop' => 'false',
        'fields' => array(
            'title' => array('type' => self::TYPE_STRING,  'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'flex_direction' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'image_height' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'image_width' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'image_margin' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'image_border_radius' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'show_title' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'show_description' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'description_alignment' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
            'max_images_fetched' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'max_images_visible' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
        )
    );
}