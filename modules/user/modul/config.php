<?php

namespace Modules\User\Modul;

class Config{
    private static $instance;
    private $config;
    
    private function __construct(){
        $configFile = APP_ROOT . DS. "modules". DS. "user". DS. "modul". DS. "config.json";
        if (!file_exists($configFile)) {
            $logger = new \Modules\Core\Modul\Logs();       
            $logger->loging('User', "['ошибка'] config.json не найден");
        }

        $this->config = json_decode(file_get_contents($configFile), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $logger = new \Modules\Core\Modul\Logs();       
            $logger->loging('User', "['ошибка'] config.json ошибка чтения");
        }
    }

    public static function get_instance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(string $key, $default = null){
        $keys = explode('->', $key);
        $value = $this->config;
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        return $value;
    }

    public function get_message(string $key, array $placeholders = []){
        $message = $this->get("messages->{$key}", '');        
        foreach ($placeholders as $placeholder => $value) {
            $message = str_replace("{{$placeholder}}", $value, $message);
        }        
        return $message;
    }
}

    
