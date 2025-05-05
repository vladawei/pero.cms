<?php

namespace Modules\Router\Modul;

class Collector{
    private static $routes = [];

    public static function add_route($path, $class, $function){
        if (!isset(self::$routes[$path])) {
            self::$routes[$path] = [
                'class' => $class,
                'function' => $function,
            ];
        }else{
            $logger = new \Modules\Core\Modul\Logs();       
            $logger->loging('router', "['ошибка']пусь уже существует: $path ");
        }        
    }

    public static function get_all_routes(){
        return self::$routes;
    }

    public static function get_route(string $path){
        return self::$routes[$path] ?? null;
    }

    public static function has_route(string $path){
        return isset(self::$routes[$path]);
    }
}