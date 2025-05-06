<?php

namespace Modules\User\Modul;

class Encoder{
    private string $algorithm = PASSWORD_BCRYPT;
    private array $options = ['cost' => 12];

    public function hash($password){
        return password_hash($password, $this->algorithm, $this->options);
    }

    public function verify($password, $hash){
        return password_verify($password, $hash);
    }
}

    
