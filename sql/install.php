<?php
/**
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'instagram` (
    `id_instagram` int(1) NOT NULL,
    `user_id` varchar(255) NOT NULL,
    `access_token` varchar(255) NOT NULL,
    `token_expires` int(11) NOT NULL,
    `creation_date` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`id_instagram`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'instagramdisplaysettings` (
    `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `flex_direction` varchar(255) NOT NULL,
    `image_height` int(11) unsigned NOT NULL,
    `image_width` int(11) unsigned NOT NULL,
    `image_border_radius` int(11) unsigned NOT NULL,
    `show_title` BOOLEAN NOT NULL,
    `show_description` BOOLEAN NOT NULL,
    `image_margin` int(11) unsigned NOT NULL,
    `max_images_fetched` int(11) unsigned NOT NULL,
    `max_images_visible` int(11) unsigned NOT NULL,
    `description_alignment` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'instagramimages` (
    `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `image_id` varchar(255) NOT NULL,
    `image_url` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
