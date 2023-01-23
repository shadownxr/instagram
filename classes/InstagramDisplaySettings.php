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
}