<?php
define('APP_ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR); 
// Автозагрузчик

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

spl_autoload_register(function ($className) {
    
    $classPath = str_replace(['\\', '_'], DS, $className);
    $classPath = strtolower($classPath);
    $file = APP_ROOT . DS . $classPath . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class {$className} not found at {$file}");
    }
});

$Core = new \Modules\Core\Modul\Core;

?>