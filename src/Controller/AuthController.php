<?php 

namespace App\Controller;
use App\Model\UserModel;

class AuthController{
    public UserModel $userModel;
    public function __construct(){
        $this->userModel = new UserModel();
    }


    public function register(){

        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        
        echo json_encode($this->userModel->insertUser($email, $firstname, $lastname, $password));
    }
   
    
}