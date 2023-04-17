<?php
class InstagramDisplaySettings extends ObjectModel {
    public $id;
    public $hook;
    public $title;
    public $display_style;
    public $image_size;
    public $show_title;
    public $max_images_fetched;
    public $images_per_gallery;
    public $gap;
    public $grid_row;
    public $grid_column;

    public static $definition = array(
        'table' => 'instagramdisplaysettings',
        'primary' => 'id',
        'multishop' => false,
        'fields' => array(
            'hook' => array('type' => self::TYPE_STRING,  'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'title' => array('type' => self::TYPE_STRING,  'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'display_style' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'image_size' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'show_title' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'max_images_fetched' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'images_per_gallery' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'gap' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'grid_row' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
            'grid_column' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true, 'size' => 11),
        )
    );
}