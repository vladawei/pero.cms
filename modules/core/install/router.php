<?php

namespace Modules\Core\Install;

class Router  extends \Modules\Abs\Install{

    public function install_BD(){
        $table = [];
        $table[] = '
            CREATE TABLE router (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(id),
            url VARCHAR(255) NOT NULL, 
            class VARCHAR(255) NOT NULL,
            funct VARCHAR(255) NOT NULL
        )
        ';
        return $table;
    }

    public function install_Router(){
        $table = [];
        /*
        $table[] = [
            "url"           => "/",
            "class"         => "Modules\Core\Controller\Index",
            "function"      => "index",
            "title"         => "Главная страница",
            "description"   => "Описание главной страницы",
            "keys"          => "Главная страница ключ",
            "name"          => "Главная страница",
            "add_to_sitemap"=> true,
            "lastmod_s"     => time(),
            "change_s"      => "monthly",
            "priority_s"    => 0.5
        ];    */


        return $table;
    }

    public function install_Congif(){
        $table = [];

        return $table;
    }
    
}