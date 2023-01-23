<?php
class InstagramDisplaySettings extends ObjectModel {
    public $id;
    public $title;
    public $flex_direction;

    public static $definition = array(
        'table' => 'instagramdisplaysettings',
        'primary' => 'id',
        'multishop' => 'false',
        'fields' => array(
            'title' => array('type' => self::TYPE_STRING,  'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'flex_direction' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
        )
    );

    public function __constructor($id = null, $id_lang = null, $id_shop = null){
        Shop::addTableAssociation('instagramdisplaysettings',array('type' => 'shop'));
        parent::__construct((int)$id, $id_lang, $id_shop);
    }
}