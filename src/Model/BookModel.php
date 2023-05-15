<?php


 //declare a namespace for this `BookModel` class
namespace App\Model\Helper;

use App\Model\Helper\Database;



class BookModel extends Database {



  public function __construct() {
    // call the parent/Database constructor
    parent::__construct(); 

   }


}