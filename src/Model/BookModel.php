<?php


 //declare a namespace for this `BookModel` class
namespace App\Model;

use App\Model\Helper\Database;
use PDO;



class BookModel extends Database {



  public function __construct() {
    // call the parent/Database constructor
    parent::__construct(); 

   }

   public function addBook($title, $content, $id_user){
    $sql = 'INSERT INTO books (title, content, id_user) VALUES (:title, :content, :id_user)';
    $sql_exe = $this->db->prepare($sql);
    $sql_exe->execute([
      'title' => $title,
      'content' => $content,
      'id_user' => $id_user
    ]);

    if ($sql_exe){
      return true;
    }else{
      return false;
    }

   }

   public function displayBooks(){
    $sql = 'SELECT * FROM book';
    $sql_exe =$this->db->prepare($sql);
    $result = $sql_exe->fetchAll(PDO::FETCH_ASSOC);
    return $result;

   }

   public function displayBookId($id){
    $sql = 'SELECT * FROM book WHERE id = $id';
    $sql_exe = $this->db->prepare($sql);
    $sql_exe->execute([]);
    $result = $sql_exe->fetch(PDO::FETCH_ASSOC);
    return $result;
   }




}