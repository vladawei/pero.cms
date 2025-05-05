<?php

namespace Modules\Router\Modul;

class Errorhandler{    

    // \Modules\Router\Modul\Errorhandler
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