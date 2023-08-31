<?php

class InstagramDisplaySettings extends ObjectModel
{
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
        'table' => 'arkon_instagram_displaysettings',
        'primary' => 'id',
        'multishop' => false,
        'fields' => array(
            'hook' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
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

    public static function createTable(): bool
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'arkon_instagram_displaysettings` (
            `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
            `hook` varchar(255) NOT NULL,
            `title` varchar(255) NOT NULL,
            `display_style` varchar(255) NOT NULL,
            `image_size` int(11) unsigned NOT NULL,
            `show_title` BOOLEAN NOT NULL,
            `max_images_fetched` int(11) unsigned NOT NULL,
            `images_per_gallery` int(11) unsigned NOT NULL,
            `gap` int(11) unsigned NOT NULL,
            `grid_row` int(11) unsigned NOT NULL,
            `grid_column` int(11) unsigned NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    public static function dropTable(): bool
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'arkon_instagram_displaysettings`';

        return Db::getInstance()->execute($sql);
    }
}