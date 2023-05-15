<?php

// declare a namespace for this `UserModel` class
namespace App\Model;

use App\Model\Helper\Database;

use PDO;


class UserModel extends Database {
  
  // Declare some private properties
  private ?int $id = null;
  private ?string $email = null;
  private ?string $firstName = null;
  private ?string $lastName = null;


  public function __construct()
  {
    // call the parent/Database constructor
    parent::__construct(); 
    
  }


    public function findAll(){
      $sql ="SELECT * FROM users";
      $sql_exe = $this->db->prepare($sql);
      $sql_exe->execute([]);
      $result = $sql_exe->fetchAll(PDO::FETCH_ASSOC);

      return $result;

    }

    public function userById($id){
      $sql ="SELECT * FROM user WHERE id = $id";
      $sql_exe=$this->db->prepare($id);
      $sql_exe->execute([]);
      $result = $sql_exe->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    }

   }
  




  
  







