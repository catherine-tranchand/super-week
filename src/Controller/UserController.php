<?php

namespace App\Controller;

use App\Model\UserModel;

class UserController {
    public UserModel $userModel;

    public function __construct(){
        $this->userModel = new UserModel();
    }

    public function list(){
        echo json_encode($this->userModel->findAll());
    }

    public function getUserById($id){
        echo json_encode($this->userModel->userById($id));
    }

   public function connectionUser($email, $password){
    //$success=$this->userModel->
   }

}

