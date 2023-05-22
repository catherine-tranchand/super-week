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

    public function verifUser($email){
      $sql="SELECT * FROM users WHERE email = :email";
      $sql_exe = $this->db->prepare($sql);
      $sql_exe->execute(['email' => $email]);
      $result = $sql_exe->fetch(PDO::FETCH_ASSOC);
      if($result){
        return true;
      } else{
        return false;
      }
    }

    public function insertUser($email, $firstname, $lastname, $password){
      if (!$this->verifUser($email)){
        $sql= "INSERT INTO users ( email, first_name, last_name, password) VALUES (:email, :first_name, :last_name, :password)";
        $sql_exe = $this->db->prepare($sql);
        $sql_exe->execute([
          'email' => htmlspecialchars($email),
          'first_name' => htmlspecialchars($firstname),
          'last_name' => htmlspecialchars($lastname),
          'password' => htmlspecialchars(password_hash($password, PASSWORD_DEFAULT))
        ]);

        if($sql_exe){
          return true;
        }else{
          return false;
        }

      }
    }

    public function connection($email, $password){
      $sql = 'SELECT * FROM users WHERE email = :email';
      $sql_exe = $this->db->prepare($sql);
      $sql_exe->execute(['email' => $email]);
      $result = $sql_exe->fetch(PDO::FETCH_ASSOC);
      if($result){
        $hashed_password = $result['password'];
        if(password_verify($password, $hashed_password)){
          $userId = $result['id'];
          $_SESSION['id'] = $userId;
          return true;
        }else{
          return false;
        } 

      }
    }

    public function logout(){
      session_destroy();
    }

   }
  
