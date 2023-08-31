<?php

class InstagramImages extends ObjectModel
{
    public $id;
    public $image_id;
    public $image_url;
    public $description;
    public $permalink;

    public static $definition = array(
        'table' => 'arkon_instagram_images',
        'primary' => 'id',
        'multishop' => 'false',
        'fields' => array(
            'image_id' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255),
            'image_url' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 65535),
            'description' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 65535),
            'permalink' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 65535)
        )
    );

    public static function createTable(): bool
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'arkon_instagram_images` (
            `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
            `image_id` varchar(255) NOT NULL,
            `image_url` TEXT NOT NULL,
            `description` TEXT NOT NULL,
            `permalink` TEXT NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    public static function dropTable(): bool
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'arkon_instagram_images`';

        return Db::getInstance()->execute($sql);
    }
}