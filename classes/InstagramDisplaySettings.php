<?php
class InstagramDisplaySettings extends ObjectModel {
    public $id;
    public string $hook;
    public string $title;
    public string $display_style;
    public int $image_size;
    public bool $show_title;
    public int $max_images_fetched;
    public int $images_per_gallery;
    public int $gap;
    public int $grid_row;
    public int $grid_column;

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