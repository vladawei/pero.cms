<?php

namespace Modules\Core\Modul;

class Core{
    public function __construct(){
        try {
            Env::load();
            if(Env::get("APP_DEBUG") == "true"){
                $this->APP_DEBUG_TRUE();
            }else{
                $this->APP_DEBUG_FALSE();
            }
            if(Env::get("APP_INSTALL") == "true"){
                \Modules\Core\Modul\Install::seach_files();
            } 
        } catch (\Throwable $e) {
            $this->e500([
                'error_message' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }

    public function APP_DEBUG_TRUE(){
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        ini_set('log_errors', '1');
    }
    public function APP_DEBUG_FALSE(){
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        ini_set('log_errors', '1'); // Но логируем всегда
    }

    public function e500(array $context = []){
        $router = new \Modules\Core\Modul\Router;
        $router->e500($context);
        exit;
    }
}