<?php

namespace Modules\Router\Modul;

class Loader{
    // \Modules\Router\Modul\Loader::load_default_routes();
    public static function load_from_json(string $jsonFile){
        if (!file_exists($jsonFile)) {
            $logger = new \Modules\Core\Modul\Logs();
            $logger->loging('router', "JSON файл не найден: $jsonFile");
            return false;
        }

        $jsonContent = file_get_contents($jsonFile);
        $routesData = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $logger = new \Modules\Core\Modul\Logs();
            $logger->loging('router', "ошибка JSON формат в файле: $jsonFile");
            return false;
        }

        if (!isset($routesData['routes']) || !is_array($routesData['routes'])) {
            $logger = new \Modules\Core\Modul\Logs();
            $logger->loging('router', "нет массива роутер в JSON файле: $jsonFile");
            return false;
        }

        $loadedCount = 0;
        foreach ($routesData['routes'] as $route) {
            if (self::validate_route($route)) {
                \Modules\Router\Modul\Collector::add_route($route['path'], $route['class'], $route['method']);
                $loadedCount++;
            }
        }
        
        return true;
    }

    private static function validate_route(array $route){
        $required = ['path', 'class', 'method'];
        foreach ($required as $field) {
            if (!isset($route[$field]) || !is_string($route[$field])) {
                $logger = new \Modules\Core\Modul\Logs();
                $logger->loging('router', "ошибка формата роут '$field'");
                return false;
            }
        }
        return true;
    }

    public static function load_default_routes(){
        $defaultFile = APP_ROOT . DS . 'modules' . DS . 'router' . DS . 'install' . DS . 'buildrouter.json';
        return self::load_from_json($defaultFile);
    }
}