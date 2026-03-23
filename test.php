
<?php

require_once __DIR__ . '/Controllers/UserController.php';

use Controllers\User\User;


header("Content-Type: application/json");


// $data = json_decode(file_get_contents("php://input"), true);

$user = new User();
$user->SignUp(["name" => "yazed" , "email" => "yazed@gmail.com" , "password"=> "0011"]);
return $user;