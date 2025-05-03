<?php
define('APP_ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR); 

// Автозагрузчик
spl_autoload_register(function ($className) {
    $classPath = str_replace(['\\', '_'], DS, $className);
    $file = APP_ROOT . DS . $classPath . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class {$className} not found at {$file}");
    }
});

$Core = new \Modules\Core\Modul\Core;

?>