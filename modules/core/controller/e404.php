<?php

namespace Modules\Core\Controller;

Class E404 extends \Modules\Abs\Controller{
    public function index(){      
        $this->type_show = "default";
        $this->list_file[] = APP_ROOT."/modules/core/view/e404.php";
        $this->show();
    }

}
