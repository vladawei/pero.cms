<?php

namespace Modules\Core\Modul;

class Install{
    public static $dir_list;
    public static $error_list;
    public static $msg;
    public static $table;

    public static function seach_files(){
        self::$dir_list =  scandir(APP_ROOT.DS."modules");
        array_splice(self::$dir_list , 0 ,  2);
        $stadiya = 0;
        while($stadiya < 3){
            foreach(self::$dir_list as $mod_dir){
                $dir_list = scandir(APP_ROOT.DS."modules".DS.$mod_dir);
                array_splice($dir_list , 0 ,  2);            
                foreach($dir_list as $mod_dir_dir){
                    if($mod_dir_dir  == "install"){
                        $file_list = scandir(APP_ROOT.DS."modules".DS.$mod_dir.DS.$mod_dir_dir);
                        array_splice($file_list , 0 ,  2);

                        foreach($file_list as $item_install){
                            self::$table = [];
                            $class_file = $pieces = explode(".", $item_install);
                            if(strtolower($class_file[1]) != "php" ) continue;
                            $class = ucfirst($class_file[0]); 
                            $class = '\\'."Modules".'\\'.ucfirst($mod_dir).'\\'.ucfirst($mod_dir_dir).'\\'.$class;
                            $result = new $class;

                            if($stadiya == 0){
                                $funct1  = "install_BD";
                                self::$table = $result->$funct1();
                                self::install_DB($class);
                            }

                            self::$table = [];
                        }     
                    }
                }
            }
            $stadiya++;
        }

        echo "<br>";
        var_dump("<pre>",self::$error_list,"</pre>");
        echo "<br>";
        var_dump("<pre>",self::$msg,"</pre>");
        echo "<br>";
    }

    public static function install_DB($class){           
        $pdo = Sql::connect();     
        foreach(self::$table as $sting_sql){
            try {
                var_dump("<pre>",$sting_sql,"</pre>");
                $res = $pdo->exec($sting_sql);
                self::$msg[] ="[ + ] " .$class ." Установлен";
            } catch (\PDOException $e) {
                $msg = "";
                $msg = $e->getMessage();
                echo $e->getMessage();
                self::$error_list[]  = "Ошибка Класс " . $class . " Возможно уже существует!";
                self::$msg[] ="[ - ] " .$class ." Не установился!";
            }                         
        }
    }
}