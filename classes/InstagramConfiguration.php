<?php

class InstagramConfiguration extends ObjectModel {

    public $user_id;
    public $access_token;
    public $token_expires;

    public static $definition = [
        'table' => 'arkon_instagram_configuration',
        'primary' => 'id_instagram',
        'multishop' => false,
        'fields' => [
            'user_id' => ['type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => true, 'size' => 255],
            'access_token' => ['type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => true, 'size' => 255],
            'token_expires' => ['type' => self::TYPE_INT,  'validate' => 'isUnsignedInt', 'required' => true, 'size' => 255],
        ]
    ];

    public static function createTable() {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'arkon_instagram_configuration` (
            `id_instagram` int(1) NOT NULL AUTO_INCREMENT,
            `user_id` varchar(255) NOT NULL,
            `access_token` varchar(255) NOT NULL,
            `token_expires` int(11) NOT NULL,
            `creation_date` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (`id_instagram`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    public static function dropTable() {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'arkon_instagram_configuration`';

        return Db::getInstance()->execute($sql);
    }
}