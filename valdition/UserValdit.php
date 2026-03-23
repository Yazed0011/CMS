<?php 
namespace Vaildit\User;

class Vaildite{
    private $name;
    private $email;
    private $password;
    private $user;

    public function ValiditeSign(array $data){
        $errors = [];
        if (empty($data['name']) || trim($data['name']) == "") {
            $errors[] = "Name Is Required";
        }
        if (empty($data['email']) || trim($data['email']) == "") {
            $errors[] = "Email Is Required";
        }
        if (empty($data['password']) || trim($data['password']) == "") {
            $errors[] = "Password Is Required";
        }
        if (!empty($errors)) {
            throw new \Exception(implode(", ", $errors), 400);
        }

        $this->name = strip_tags($data['name']);
        $this->email = strip_tags($data['email']);
        $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->user=[
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password
        ];
    }
    public function ValiditeLogin(array $data){
        $errors = [];
        if (empty($data['email']) || trim($data['email']) == "") {
            $errors[] = "Email Is Required";
        }
        if (empty($data['password']) || trim($data['password']) == "") {
            $errors[] = "Password Is Required";
        }
        if (!empty($errors)) {
            throw new \Exception(implode(", ", $errors), 400);
        }

        $this->email = strip_tags($data['email']);
        $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->user=[
            "email" => $this->email,
            "password" => $this->password
        ];
    }
    public function Getname(){
        return $this->name;
    }
    public function Getemail(){
        return $this->email;
    }
    public function Getpassword(){
        return $this->password;
    }
    public function Getall(){
        return $this->user;
    }
    
}