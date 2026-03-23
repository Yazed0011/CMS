<?php

namespace Controllers\User;
use Model\User\Users;
use Vaildit\User\Vaild;
use Vaildit\User\Vaildite;

class User{
    public $user;

    public function SignUp(array $data){
        header("Content-Type: application/json");
        $vaild=new Vaildite();
        $vaild->ValiditeSign($data);
        $user = new Users();
        $user->signUp($vaild->Getall());
        $this->user=$user;
        return $this->user;
    }

    public function Login(array $data) {
        $vaild=new Vaildite();
        $vaild->ValiditeLogin($data);
        $user=new Users();
        $user->login($vaild->Getall());
        $this->user=$user;
        return $this->user;
    }
}