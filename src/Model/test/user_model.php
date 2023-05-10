<?php

//declare the namespace 

namespace App\Model\Test;

// require the 'autoload.php' 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use App\Model\UserModel;
use Faker\Factory;

$userModel = new UserModel();
$faker = Factory::create('fr');


$numberOfUsers = 50;

for ($i=1; $i < $numberOfUsers; $i++) { 
    
    $firstName = $faker->firstName();
    
    $lastName = $faker->lastName();
    
    $password = $faker->password();

    $hashPassword = password_hash($password,PASSWORD_DEFAULT);
    
    $email = $faker->email();


    echo sprintf("
    firstname: %s
    lastName: %s
    email: %s
    password: %s
    hashPassword: %s
    ", $firstName, $lastName, $email, $password, $hashPassword);

    $userModel->create($email,$firstName,$lastName, $hashPassword);

}




