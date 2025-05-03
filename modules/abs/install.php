<?php

namespace Modules\Abs;

abstract class Install{
    
    //Отвечает за установку таблиц
    abstract public function install_BD();

    //Отвечает за добавления в роутер
    abstract public function install_Router();

    //Отвечает за добавления в роутер
    abstract public function install_Congif();



}
