<?php

namespace Modules\Core\Install;

class Head  extends \Modules\Abs\Install{

    public function install_BD(){
        $table = [];
        $table[] = '
        CREATE TABLE heads (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        PRIMARY KEY(id),
        url VARCHAR(255) NOT NULL, 
        title_q VARCHAR(255) NOT NULL, 
        description_q VARCHAR(255) NOT NULL,
        keys_q VARCHAR(255) NOT NULL,
        name_q VARCHAR(255) NOT NULL
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