<?php

class InstagramApiConfiguration extends ObjectModel {
    public $app_id;
    public $app_id_iv;
    public $app_secret;
    public $app_secret_iv;

    public static $definition = [
      'table' => 'arkon_instagram_api_configuration',
      'primary' => 'id',
      'multishop' => 'false',
      'fields' => [
          'app_id' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255],
          'app_id_iv' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255],
          'app_secret' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255],
          'app_secret_iv' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255],
      ]
    ];

    public static function createTable(){
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'arkon_instagram_api_configuration` (
            `id` int(1) NOT NULL AUTO_INCREMENT,
            `app_id` varchar(255) NOT NULL,
            `app_id_iv` blob NOT NULL,
            `app_secret` varchar(255) NOT NULL,
            `app_secret_iv` blob NOT NULL,
            PRIMARY KEY  (`id`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    public static function dropTable(){
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'arkon_instagram_api_configuration`';

        return Db::getInstance()->execute($sql);
    }
}