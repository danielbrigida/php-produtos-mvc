<?php 

ini_set('display_errors', 'On');

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

require __DIR__.'\..\vendor\autoload.php';

require __DIR__ .'\..\app\modules\Produtos\router\route.php';
require __DIR__ .'\..\app\modules\Arquivos\router\route.php';
