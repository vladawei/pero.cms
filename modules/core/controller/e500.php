<?php

namespace Modules\Core\Controller;

Class E500 extends \Modules\Abs\Controller{
    public function index($context){  
        $this->data["error_msg"] = $context;
        $this->type_show = "errors";
        $this->list_file[] = APP_ROOT."/modules/core/view/e500.php";
        $this->show();
    }

}
