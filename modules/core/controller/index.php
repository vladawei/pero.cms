<?php

namespace Modules\Core\Controller;

Class Index extends \Modules\Abs\Controller{
    public function index(){      
          
        $this->type_show = "default";
        $this->list_file[] = APP_ROOT."/modules/core/view/index.php";
        $this->show();
    }

}
