<?php

namespace Modules\Abs;

abstract class Controller{
    public $type_show;
    public $page_load;
    public $list_file;
    public $data;
    public function show( $cash = false){
        switch ($this->type_show ) {
            case "default":
                $this->default();
                break;
            case "empty":
                $this->empty();
                break;
            case "admin":
                $this->admin();
                break;
            case "ajax":
                $this->ajax();
                break;
            case "api":
                $this->api();
                break;
            case "errors":
                $this->errors();
                break;
        }
    }

    public function default(){        
        $this->page_load = APP_ROOT."/modules/core/view/head.php";              
        $this->links();
        $this->page_load = APP_ROOT."/modules/core/view/header.php";              
        $this->links();
        $this->draw();
        $this->page_load = APP_ROOT."/modules/core/view/footer.php";              
        $this->links();
    }

    public function empty($h){
        $h["view"]["page_load"] = MYPOS."/mod/core/view/head.php";              
        $h = $this->links($h);
        $this->draw($h);
        return $h;
    }

    public function admin($h){
        $build_l_menu = new \Mod\Core\Modul\Builderlmenu;
        $h = $build_l_menu ->build($h);
        $build_pex = new \Mod\Admin\Modul\Pex;
        $h = $build_pex ->seach_files($h);

        $h["view"]["page_load"] = MYPOS."/mod/core/view/head.php";              
        $h = $this->links($h);
        $h["view"]["page_load"] = MYPOS."/mod/core/view/admin/lmenu.php";              
        $h = $this->links($h);
        $h["view"]["page_load"] = MYPOS."/mod/core/view/admin/header.php";              
        $h = $this->links($h);

        $this->draw($h);

        $h["view"]["page_load"] = MYPOS."/mod/core/view/admin/rmenu.php";              
        $h = $this->links($h);
        return $h;
    }

    public function ajax($h){
        $this->draw($h);
        return $h;
    }

    public function api($h){
        $this->draw($h);
        return $h;
    }

    public function errors(){
        $this->page_load = APP_ROOT."/modules/core/view/head.php";              
        $this->links();
        $this->page_load = APP_ROOT."/modules/core/view/header.php";              
        $this->links();
        $this->draw();
        $this->page_load = APP_ROOT."/modules/core/view/footer.php";              
        $this->links();
    }

    public function cashe_start($h){
        
        $h["cache"]["isset"] = false;
        if(!$h["cache"]["job"]) return $h;
        $file_name = md5($h["url"]["d_line"]."g".$h["url"]["d_of_get_line"]);
        $h["cache"]["filename"] = MYPOS.SLASH.'cache'.SLASH.$file_name.'.cache';
        if (file_exists($h["cache"]["filename"])) {
            $h["cache"]["isset"] = true;
            $c = @file_get_contents($h["cache"]["filename"] );
            echo $c;
            return $h;
        } 
        ob_start();
        return $h;
    }

    public function cashe_end($h){
        if(!$h["cache"]["job"]) return $h;
        $c = ob_get_contents();
        file_put_contents($h["cache"]["filename"], $c);
        return $h;
    }


    public function draw(){        
        foreach($this->list_file as $p){
            $this->page_load  = $p;         
            $this->links();
        }
    }

    public function links(){        
        if (file_exists($this->page_load )) {
            include  $this->page_load ;            
        }else{                 
            $logger = new \Modules\Core\Modul\Logs();           
            $msg = "Не найдет файл: ".$this->page_load ;
            $logger->loging('view', $msg);
        }
    }


}
