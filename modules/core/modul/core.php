<?php

namespace Modules\Core\Modul;

class Core{
    public function __construct(){
        $logger = new \Modules\Core\Modul\Logs();
        $logger->loging('core', 'ядро загружено');
        echo "Класс успешно загружен!";
        $logger->loging('core', 'ядро загружено');
    }
}