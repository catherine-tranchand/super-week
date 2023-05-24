<?php

namespace App\Controller;
use App\Model\UserModel;
use App\Model\BookModel;

class BookController{
    public UserModel $user;
    public BookModel $book;

    public function __construct(){
        $this->user = new UserModel();
        $this->book = new BookModel();
    }

    public function insertBook(){
        $title = $_POST['title'];
        $content = $_POST['content'];
        $id_user = $_SESSION['id'];
        $success =  $this->book->addBook($title, $content, $id_user);
        
        echo json_encode(["success" => $success]);

    
    }

    public function getBooks(){
        echo json_encode($this->book->displayBooks());
    }

    public function getBookById($id){
        echo json_encode($this->book->displayBookId($id));
    }
}