<?php

namespace Modules\Core\Modul;

class Core{
    public function __construct(){
        Env::load();
        //var_dump(Env::get("APP_NAME"));

        //$pdo = Sql::connect();
    }
}