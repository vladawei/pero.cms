<?php

namespace Modules\Router\Modul;

class Router{
    // \Modules\Router\Modul\Router::start();
    public static $url;
    public static $need300 =  false;
    public static function start(){
        self::get_url_page();
        self::need_redirect300();
        \Modules\Router\Modul\Loader::load_default_routes();
        self::go();
    }
    public static function go(){
            // Получаем текущий URL (адаптируйте под вашу реализацию)
        $currentPath = self::$url["d_line"] ?? '/';
        
        // Ищем маршрут в Collector
        $route = \Modules\Router\Modul\Collector::get_route($currentPath);
        
        // Если маршрут не найден - 404
        if ($route === null) {
            \Modules\Router\Modul\Errorhandler::e404();
            return;
        }

        $class = $route['class'];
        $method = $route['function'];

        if (!class_exists($class)) {
            \Modules\Router\Modul\Errorhandler::e500("Класс '$class' не найден.");
            return;
        }

        $controller = new $class;

        if (!method_exists($controller, $method) || !(new \ReflectionMethod($controller, $method))->isPublic()) {
            \Modules\Router\Modul\Errorhandler::e500("Метод '$method' в классе '$class' не найден или недоступен.");
            return;
        }
        
        try {
            // Вызываем метод контроллера
            $controller->$method();
        } catch (\Throwable $e) {
            \Modules\Router\Modul\Errorhandler::e500("Ошибка при вызове метода '$class::$method': " . $e->getMessage()
            );
        }
    }

    public static function need_redirect300(){
        if(self::$need300){
            $url = self::$url['d_line'];
            if(isset(self::$url["get_in_line"]) and self::$url["d_of_get_line"] != ""){
                $url = $url."?".self::$url["d_of_get_line"];
            }
            header("Location: $url", true, 301);
            die();
        }
        return;
    }

    public static function get_url_page(){
        self::$url = [];
        self::$url['all'] = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        self::$url['protocol'] = (!empty($_SERVER['HTTPS'])) ? 'https' : 'http';
        self::$url['domen'] = $_SERVER['HTTP_HOST'] ;
        
        $dir = explode('?', $_SERVER['REQUEST_URI']);        
        if(substr($dir[0], -1) != "/") {
            $dir[0].= "/";
            self::$need300 = true;
        }

        self::$url['d_line'] = $dir[0];
        if(isset($dir[1])){
            self::$url['d_of_get_line'] = $dir[1];
        }else{
            self::$url['d_of_get_line'] = "";
        }
        self::$url['d_array'] = explode('/', self::$url['d_line']);
        self::$url['direct_size'] = count(self::$url['d_array']) - 2;

        self::take_get();
        self::take_post();
    }

    public static function take_get(){
        if(!empty($_GET)){
            self::$url['get'] = $_GET;  
        }      
    }

    public static function take_post(){
        if(!empty($_POST)){
            self::$url['post'] = $_POST;
        };
    }
}