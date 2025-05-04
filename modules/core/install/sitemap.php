<?php

namespace Modules\Core\Install;

class Sitemap  extends \Modules\Abs\Install{

    public function install_BD(){
        $table = [];
        $table[] = '
        CREATE TABLE '.\Modules\Core\Modul\Env::get("DB_PREFIX").'sitemap (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        PRIMARY KEY(id),
        url VARCHAR(255) NOT NULL, 
        lastmod_s VARCHAR(255) NOT NULL,
        change_s VARCHAR(255) NOT NULL,
        priority_s VARCHAR(255) NOT NULL
        )
        ';
        return $table;
    }

    public function install_Router(){
        $table = [];

        return $table;
    }

    public function install_Congif(){
        $table = [];

        return $table;
    }
    
}