<?php

namespace Modules\Core\Modul;

class Router{
    public static $url;
    public static $need300 =  false;
    public static function start(){
        self::get_url_page();
        self::need_redirect300();
        self::go();
    }
    public static function go(){
        $pdo = Sql::connect(); 
        $sth = $pdo->prepare("SELECT * FROM `router` WHERE `url` = ?");
        $sth->execute(array(self::$url["d_line"]));
        $result_sql = $sth->fetch(\PDO::FETCH_ASSOC);

        if(!isset($result_sql["id"]) or !($result_sql["id"] >= 1)) {
            self::e404();
            return ;
        }   

        $class = $result_sql["class"];
        $funct = $result_sql["funct"];

        if (!class_exists($class)) {
            self::e500("Класс '$class' не найден.");
            return;
        }


        $controller = new $class;

        if (!method_exists($controller, $funct) || !(new \ReflectionMethod($controller, $funct))->isPublic()) {
            self::e500("Метод '$funct' в классе '$class' не найден или недоступен.");
            return;
        }

        try {
            // Вызов контроллера и метода
            $controller->$funct();
        } catch (\Throwable $e) {
            self::e500("Ошибка при вызове метода '$class::$funct': " . $e->getMessage());
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
    public static function e500($context){
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        $E500 = new \Modules\Core\Controller\E500;
        $E500->index($context);
    }

    public static function e404(){
        http_response_code(404);
        header('Content-Type: text/html; charset=utf-8');
        $e404 = new \Modules\Core\Controller\E404;
        $e404->index();
    }

    public static function e401(){
        http_response_code(401);
        header('Content-Type: text/html; charset=utf-8');
        $E401 = new \Modules\Core\Controller\E401;
        $E401->index();
    }
}