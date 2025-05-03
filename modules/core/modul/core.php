<?php

namespace Modules\Core\Modul;

class Core{
    public function __construct(){
        Env::load();
        //var_dump(Env::get("APP_NAME"));
        if(Env::get("APP_INSTALL") == "true"){
            \Modules\Core\Modul\Install::seach_files();
        } 

        //$pdo = Sql::connect();
    }
}