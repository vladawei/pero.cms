<?php

namespace Modules\Core\Install;

class Router {

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

    
    
}