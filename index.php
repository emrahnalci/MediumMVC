<?php

/**
 * MediumMVC
 * 
 * MiniMVC is an MVC (Model-View-Controller) application framework for PHP.
 * 
 * No restrictive coding rules
 * Simple solutions over complexity
 * No large-scale monolithic libraries
 * Nearly zero configuration
 * No need for template language
 * Spend more time away from the computer
 * PHP >= 5.5.9
 * PDO PHP Extension
 * 
 * 
 * @package	MediumMVC
 * @author	Emrah NALCI
 * @copyright Copyright (c) 2017 - 2019, Emrah NALCI.
 * @since Version 1.0.1
 */

ob_start();

define('BASEPATH', 'MINIMVC');

/**
 * You can define this function to enable classes autoloading. Name of the class to load.
 * 
 * Include all php files on system/libs/ folder.
 * 
 * @param type $className
 */
function __autoload($className){
    include_once "system/libs/" . $className . ".php";
}

/**
 * Include your configration file.
 */
include_once "app/config/config.php";

$parser = new URLParser();

ob_flush();
?>

