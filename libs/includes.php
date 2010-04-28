<?php

function __autoload($class_name) {
    require_once $class_name . '.class.php';
}
require_once('translations.php');
require_once('functions.php');
require_once('db_config.php');
?>