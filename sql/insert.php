<?php

$sql = array();

$sql[] = 'INSERT INTO `' . 'instagram';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}