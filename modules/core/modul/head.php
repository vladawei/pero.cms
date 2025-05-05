<?php

namespace Modules\Core\Modul;

class Head{
    public static $first_load = false;
    public static $title;
    public static $description;
    public static $charset;
    public static $ico_href;
    public static $ico_type;
    public static $lang;
    public static $theme_color;
    public static $viewport;
    public static $sql_data;
    public static $name;

    public static function load(){
        if(!self::$first_load){
            self::first_load();
            self::data_load();
            self::data_insert();
        }
    }
    public static function first_load(){
        self::$first_load = true;
        self::load_default_config();
    }
    public static function load_default_config(){
        self::$title = \Modules\Core\Modul\Env::get("HEAD_TITLE_PREFIX")
            .\Modules\Core\Modul\Env::get("HEAD_TITLE_DEFAULT")
            .\Modules\Core\Modul\Env::get("HEAD_TITLE_SUFFIX");

        self::$description = \Modules\Core\Modul\Env::get("HEAD_DESCRIPTION_PREFIX")
            .\Modules\Core\Modul\Env::get("HEAD_DESCRIPTION_DEFAULT")
            .\Modules\Core\Modul\Env::get("HEAD_DESCRIPTION_SUFFIX");

        self::$charset = \Modules\Core\Modul\Env::get("HEAD_CHARSET");

        self::$ico_href = \Modules\Core\Modul\Env::get("HEAD_ICO_HREF");
        self::$ico_type = \Modules\Core\Modul\Env::get("HEAD_ICO_TYPE");

        self::$lang = \Modules\Core\Modul\Env::get("HEAD_LANGUAGE");
        self::$theme_color = \Modules\Core\Modul\Env::get("HEAD_THEME_COLOR");
        self::$viewport = \Modules\Core\Modul\Env::get("HEAD_VIEWPORT");

        self::$name = \Modules\Core\Modul\Env::get("HEAD_NAME");
    }

    public static function data_load(){
        $db_name = \Modules\Core\Modul\Env::get("DB_PREFIX")."heads";
        $pdo = Sql::connect();  
        $sth1 = $pdo->prepare("SELECT * FROM $db_name WHERE `url` = ? LIMIT 1");
        $sth1->execute(array(\Modules\Router\Modul\Router::$url["d_line"]));
        self::$sql_data = $sth1->fetch(\PDO::FETCH_ASSOC);
    }

    public static function data_insert(){
        if(isset(self::$sql_data["title_q"])){
            self::$title = \Modules\Core\Modul\Env::get("HEAD_TITLE_PREFIX")
                .self::$sql_data["title_q"]
                .\Modules\Core\Modul\Env::get("HEAD_TITLE_SUFFIX");
        }
        if(isset(self::$sql_data["description_q"])){
            self::$description = \Modules\Core\Modul\Env::get("HEAD_TITLE_PREFIX")
                .self::$sql_data["description_q"]
                .\Modules\Core\Modul\Env::get("HEAD_TITLE_SUFFIX");
        }

        if(isset(self::$sql_data["name_q"])){
            self::$name = self::$sql_data["name_q"];
        }
    }
}