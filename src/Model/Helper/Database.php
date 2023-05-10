<?php
/**
* @license
* super-week 
* Copyright (c) 2023 Abraham Ukachi
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* @project super-week
* @name Database
* @test test/database.php
* @file Database.php
* @author: Abraham Ukachi <abraham.ukachi@laplateforme.io>
* @version: 0.0.3
* 
* Usage:
*   1-|> require_once __DIR__ . '/Database.php';
*    -|> Use App\Model\Helper as modelHelper;
*    -|> $database = new modelHelper\Database('mysqli'); // <- or 'pdo'
*
*
*   2-|> // Handling database creation error
*    -|>
*    -|> if ($database->create_errno) {
*    -|>    echo $database->create_error;
*    -|>  }
*
*
*   3-|> // Handling database connection error
*    -|>
*    -|> if ($database->connect_errno) {
*    -|>    echo $database->connect_error;
*    -|>  }
*
*
*   4-|> $database = new modelHelper\Database(modelHelper\Database::TYPE_PDO, false);
*    -|> $database->dbCreation();
*
*/


/*
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * MOTTO: I'll always do more 😜!!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */





// Declare a namespace named `App\Model\Helper`
namespace App\Model\Helper;


// Using some core PHP Classes...

use pdo;
use mysqli;
use mysqli_driver;



// Uncomment the code below, to enable return type in PHP functions
// declare(strict_types=1);


/*
 * Declare a class named 'Database'.
 * NOTE: By default - upon instantiation, this class automatically creates 
 * the required database & subsequent table (if they don't already exist in `phpmyadmin`)
 *
 * Example usage:
 *  use App\Model\Helper\Database;
 *
 *  $database = new Database(Database::TYPE_PDO);
 *  $conn = $database->pdo;
 */
class Database {

  // public constants
  public const TYPE_MYSQLI = 'mysqli';
  public const TYPE_PDO = 'pdo';
  public const ERROR_NOT_FOUND = 0;
  public const ERROR_FOUND = 1;
  // tables
  public const TABLE_USERS = 'users';
  public const TABLE_BOOKS = 'books';
  // fields
  public const FIELD_ID = 'id';
  public const FIELD_EMAIL = 'email';
  public const FIELD_FIRST_NAME = 'first_name';
  public const FIELD_LAST_NAME = 'last_name';
  public const FIELD_PASSWORD = 'password';
  public const FIELD_TOKEN = 'token';
  public const FIELD_TITLE = 'title';
  public const FIELD_CONTENT = 'content';
  public const FIELD_ID_USER = 'id_user';


  // private properties
  private string $db_host = 'localhost';
  private string $db_username = 'root';
  private string $db_password = 'root';
  private int $db_port = -1;
  private string $db_name = 'super_week';
  private string $db_type;
  
  public MYSQLI $mysqli;
  public PDO $pdo;

  /*
  public ?object $mysqli = null;
  public ?object $pdo = null;
  public ?object $db = null;
  */ 


  // public properties
  // - connection errors
  public int $connect_errno;
  public string $connect_error;
  // - creation errors
  public int $create_errno;
  public string $create_error;


  

  

  /**
   * Constructor that is automatically called whenever an object of this database gets created.
   *
   * @param string $db_type - The type of database connection (i.e. 'mysqli' or `pdo`)
   * @param bool $autoCreate - If TRUE, the database will be created automatically
   */
  public function __construct(string $db_type = self::TYPE_MYSQLI, bool $autoCreate = true) {

    // Intializing some properties...

    $this->db_type = $db_type;

    // creation errors
    $this->create_errno = 0;
    $this->create_error = "";

    // connection errors
    $this->connect_errno = 0;
    $this->connect_error = "";

    if ($autoCreate) :
      // Create the project-specific database (if it *DOES NOT* exist)
      $this->dbCreation();
    endif;

    /* print_r($this->getTableQuery(self::TABLE_USERS)); */
  } 

  // PUBLIC SETTERS


  /**
   * Method used to set or update the database username with the given `db_username`
   *
   * @param string $db_username
   */
  public function setDatabaseUsername(string $db_username): void {
    $this->db_username = $db_username;
  }

  /**
   * Method used to set or update the database password with the given `db_password`
   *
   * @param string $db_password
   */
  public function setDatabasePassword(string $db_password): void {
    $this->db_password = $db_password;
  }

  /**
   * Method used to set or update the database port with the given `db_port`
   *
   * @param int $db_port
   */
  public function setDatabasePort(int $db_port): void {
    $this->db_port = $db_port;
  }


  // PUBLIC GETTERS

  /**
   * Returns the database username
   *
   * @return string $db_username
   * 
   */
  public function getDatabaseUsername(): string {
    return $this->db_username;
  }

  /**
   * Returns the database password
   *
   * @return string $db_password
   */
  public function getDatabasePassword(): string {
    return $this->db_password;
  }


  /**
   * Returns the database port
   *
   * @return int $db_port
   */
  public function getDatabasePort(): int {
    return $this->db_port;
  }



  // PUBLIC METHODS

  /**
   * Creates the project-specific database and table using the predefined type of database connection (i.e. `db_type`)
   * NOTE: This process is aborted, if the database and table already exists.
   *  
   * @return bool $result - Returns TRUE if the database was created successfully
   * @private
   */
  public function dbCreation(): bool {
    // Initalize the `result` variable
    $result = false;

    // TODO:? Use a switch/case block here instead 🤔

    // If the database connection type is 'mysqli'...
    if ($this->db_type == $this::TYPE_MYSQLI) :

      // Connect to the database without a database name
      $this->dbConnectViaMysqli(false);

      // Just return $result|FALSE, if there's a connection error 
      if ($this->connect_errno) { return $result; } 
      
      // DEBUG [4dbsmaster]: tell me about it :)
      // echo "[MYSQLI]: connect ERRNO => $this->connect_errno & ERROR => $this->connect_error";


      // IDEA: At this point, the database connection is successful
      // Now, We wanna create our database (if it doesn't exist)

      // Create a database via MYSQLI,
      // and update the `result` variable accordingly ;)
      $result = $this->dbCreateViaMysqli();

    elseif ($this->db_type == $this::TYPE_PDO) : // <- database connection type is 'pdo'
      // Connect to the database without a database name
      $this->dbConnectViaPdo(false);

      // Just return $result|FALSE, if there's a connection error 
      if ($this->connect_errno) { return $result; } 
      
      // DEBUG [4dbsmaster]: tell me about it :)
      // echo "[PDO]: connect ERRNO => $this->connect_errno & ERROR => $this->connect_error";


      // IDEA: At this point, the database connection is successful
      // Now, We wanna create our database (if it doesn't exist)

      // Create a database via PDO,
      // and update the `result` variable accordingly
      $result = $this->dbCreateViaPdo();

    endif;

    // return the `result` variable
    return $result;
  }




  /**
   * Method used to establish a database connection,
   * WARNING: The datbase name (i.e. `db_name`) will be used for connection
   *
   * @return object $conn - A `mysqli` or `pdo` database connection 
   */
  public function dbConnection(): object {
    // Initialize the `conn` variable
    $conn = null;

    // If the database connection type is MYSQLI... 
    if ($this->db_type == $this::TYPE_MYSQLI):
      // ...connect via mysqli
      $conn = $this->dbConnectViaMysqli(true);

    elseif ($this->db_type == $this::TYPE_PDO): // <- database connection is PDO
      // ...connect via pdo
      $conn = $this->dbConnectViaPdo(true);
    endif;

    // Return `conn`
    return $conn;
  }


  /**
   * Closes the current database connection
   */
  public function close(): void {
    // If the database connection type is MYSQLI... 
    if ($this->db_type == $this::TYPE_MYSQLI):
      // ...close the `mysqli` connection by using the `close()` function
      $this->mysqli->close();

    elseif ($this->db_type == $this::TYPE_PDO): // <- database connection is PDO
      // ...close the `pdo` connection by setting the `pdo` object to null
      $this->pdo = null;
    endif;
  }




  // PRIVATE SETTERS

  // PRIVATE GETTERS

  /**
   * Returns the Data Source Name (dsn) of our database,
   * using some predefined attributes like `db_host` and `db_name`.
   *
   * @return string
   */
  private function getDSN(): string {
    $default_dsn = "mysql:host=$this->db_host;dbname=$this->db_name";
    
    return ($this->db_port !== -1) ? "$default_dsn;port={$this->db_port}" : $default_dsn;
  }


  /**
   * Returns the SQL query string to *safely* create our database
   * NOTE: This query contains a `utf8` character set and a `utf8_unicode_520_ci`. 
   * To learn more about this character encoding, check out this post on [Stark Overflow](https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci)
   *
   * @return string $query - The SQL query string to create our database
   */
  private function getDatabaseQuery(): string {
    // create a query string
    $query = "CREATE DATABASE IF NOT EXISTS " . $this->db_name . " CHARACTER SET utf8 COLLATE utf8_unicode_520_ci";

    // return the query string
    return $query;
  }

  /**
   * Returns the SQL query string to *safely* create the project-required table with columns in our database
   *
   * @param string $table_name - The name of the table to be created
   *
   * @return string $query - The SQL query string to create our table
   */
  private function getTableQuery(string $table_name): string {
    // use a switch/case block to return the appropriate query string
    switch ($table_name) {
      case self::TABLE_USERS:
        return $this->getUsersTableQuery();
        break;
      case self::TABLE_BOOKS:
        return $this->getBooksTableQuery();
        break;
      default:
        return "";
        break;
    }

  }


  /**
   * Returns the SQL query string to *safely* create the `books` table with columns in our database
   *
   * @return string $query - The SQL query string to create our table
   */
  private function getBooksTableQuery(): string {
    return "CREATE TABLE IF NOT EXISTS " . self::TABLE_BOOKS . " (
      " . self::FIELD_ID . " INT(11) UNSIGNED AUTO_INCREMENT,
      " . self::FIELD_TITLE . " VARCHAR(255) NOT NULL,
      " . self::FIELD_CONTENT . " TEXT NOT NULL,
      " . self::FIELD_ID_USER . " INT(11) UNSIGNED NOT NULL,
      PRIMARY KEY (" . self::FIELD_ID . "),
      FOREIGN KEY (" . self::FIELD_ID_USER . ") REFERENCES " . self::TABLE_USERS . " (" . self::FIELD_ID . ") ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci"; // <- TODO:? Specify an Engine like `innoDB`
  }



  /**
   * Returns the SQL query string to *safely* create the `users` table with columns in our database
   *
   * @return string $query - The SQL query string to create our table
   */
  private function getUsersTableQuery(): string {
    return "CREATE TABLE IF NOT EXISTS " . self::TABLE_USERS . " (
      " . self::FIELD_ID . " INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      " . self::FIELD_EMAIL . " VARCHAR(255) NOT NULL,
      " . self::FIELD_FIRST_NAME . " VARCHAR(255) NOT NULL,
      " . self::FIELD_LAST_NAME . " VARCHAR(255) NOT NULL,
      " . self::FIELD_PASSWORD . " VARCHAR(255) NOT NULL,
      " . self::FIELD_TOKEN . " VARCHAR(255) DEFAULT NULL,
      PRIMARY KEY (" . self::FIELD_ID . "),
      UNIQUE KEY `users_email_unique` (" . self::FIELD_EMAIL . ") 
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci"; // <- TODO:? Specify an Engine like `innoDB`
  }



  // PRIVATE METHODS
  

  /**
   * Method used to connect to the database via MYSQLI
   *
   * @param bool $useName - If TRUE, the database name (i.e. `db_name`) will be used for connection
   *
   * @return object $mysqli - The MYSQLI connection to the database
   * @private
   */
  private function dbConnectViaMysqli($useName = false) {
    // Reset the connection errors
    $this->resetConnectErrors();

    // Set the MYSQL error mode to exception
    // (or: switch ON exception mode instead of class error reporting)
    $driver = new mysqli_driver();
    $driver->report_mode = MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR;
    
    // Let's try to establish a database connection, shall we?
    try {
      
      // Using the object-oriented style of MYSQLI, if `useName` is TRUE...
      if ($useName) {
        // ...connect to our database, using the `db_name` variable
        $mysqli = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name);

      }else { // <- `useName` is FALSE
        // ...connect to our database without specifying the database name 
        $mysqli = new mysqli($this->db_host, $this->db_username, $this->db_password);
      }
    
    } catch (mysqli_sql_exception $mse) {
       // update the connection errors
      $this->updateConnectErrors($this::ERROR_FOUND, "[dbConnectViaMysqli]: Failed to connect to database - " . $mse->getMessage());
    } 

    // DEBUG [4dbsmaster]: tell me about it :)
    // echo "Connected to database via MYSQLI !!!"; 
      
    // Update `mysqli` of this class
    $this->mysqli = $mysqli;
    
    // Return `mysqli`
    return $mysqli; 
  }



  /**
   * Create the database via MYSQLI.
   * NOTE: This method will also create the required table with columns, if they do not exist.
   *
   * @param object $mysqli - MySQL database connection
   *
   * @return bool - Returns TRUE if the databse was successfully created via MYSQLI
   * @private
   */
  private function dbCreateViaMysqli() {
    // Return FALSE if there's no `mysqli` object
    // if (isset($mysqli)) { return false; }
    
    // Initialize the `result` variable 
    $result = false;

    // Reset the creation errors
    $this->resetCreateErrors();

    // Creating the database and subsequent table...

    try {

      // get the datbase query string as `create_db_query`
      $create_db_query = $this->getDatabaseQuery();
      // get the `users` table query string as `create_users_table_query`
      $create_users_table_query = $this->getTableQuery(self::TABLE_USERS);
      // get the `books` table query string as `create_books_table_query`
      $create_books_table_query = $this->getTableQuery(self::TABLE_BOOKS);


      // Perform the `create_db_query` against our `mysqli` database,
      // to safely create a database with our predefined `db_name`,
      // and asing it's result to a `databaseCreated`
      $databaseCreated = $this->mysqli->query($create_db_query);

      // select this as our default database
      $this->mysqli->select_db($this->db_name);
      
      // Perform the `create_users_table_query` against our `mysqli` database,
      // to safely create a table in our database, and asign it's result to a `usersTableCreated`...
      $usersTableCreated = $this->mysqli->query($create_users_table_query);
      // ...do the same for `books` table
      $booksTableCreated = $this->mysqli->query($create_books_table_query);


      // If our database and both table were created successfully...
      if ($databaseCreated && $usersTableCreated && $booksTableCreated) {
        // set `result` to TRUE
        $result = true;
      }else {
        // update creation errors
        $this->updateCreateErrors($this::ERROR_FOUND, "[dbCreateViaMysqli]: Creation of database and table failed");
      }

    } catch (mysqli_sql_exception $mse) {
      // update creation errors
      $this->updateCreateErrors($this::ERROR_FOUND, "[dbCreateViaMysqli]: Failed to create database or table - " . $mse->getMessage());
    }

    // return the `result`
    return $result;

  }




  /**
   * Method used to connect to the database via PDO
   *
   * @param bool $useName - If TRUE, the database name (i.e. `db_name`) will be used for connection
   *
   * @return object $pdo - The PDO connection to the database
   * @private
   */
  private function dbConnectViaPdo($useName = false) {
    // Reset the connection errors
    $this->resetConnectErrors();

    // define multiple options for PDO
    $db_options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // <- Throw exceptions
      PDO::ATTR_CASE => PDO::CASE_NATURAL, // <- Use the case of the database
      PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING, // <- Convert empty strings to NULL
      PDO::ATTR_EMULATE_PREPARES => false // <- Use real prepared statements
    ];


    // get our current Data Source Name as `db_dsn`
    $db_dsn = $this->getDSN();
     
    
    // Let's try to establish a database connection, shall we?
    try { 

      // Using the object-oriented style of PDO, if `useName` is TRUE...
      if ($useName) {
        // ...connect to our database, using the `db_name` variable
        $pdo = new pdo($db_dsn, $this->db_username, $this->db_password, $db_options);

      }else { // <- `useName` is FALSE
        // ...connect to our database without specifying the database name  
        $pdo = new pdo("mysql:host=$this->db_host", $this->db_username, $this->db_password, $db_options);
      }

      // DEBUG [4dbsmaster]: tell me about it ;)
      // echo "Database connected successfully via PDO";
    
    } catch (PDOException $e) { 
       // update the connection errors
      $this->updateConnectErrors($this::ERROR_FOUND, "[dbConnectViaPdo]: Failed to connect to database - " . $e->getMessage());
    } 
    
    // DEBUG [4dbsmaster]: tell me about it :)
    // echo "Connected to database via PDO !!!"; 
     
    // Update `pdo` of this class
    $this->pdo = $pdo;
    
    // Return `pdo`
    return $pdo; 
  }



  /**
   * Create the database via PDO.
   * NOTE: This method will also create the required table with columns, if they do not exist.
   *
   * @param object $pdo - MySQL database connection
   *
   * @return bool - Returns TRUE if the databse was successfully created via PDO
   * @private
   */
  private function dbCreateViaPdo() {
    // Return FALSE if there's no `pdo` object
    // if (isset($pdo)) { return false; }
    
    // Initialize the `result` variable 
    $result = false;

    // Reset the creation errors
    $this->resetCreateErrors();

    // Creating the database and subsequent table...

    try {

      // get the datbase query string as `create_db_query`
      $create_db_query = $this->getDatabaseQuery();
      // get the users table query string as `create_users_table_query`
      $create_users_table_query = $this->getTableQuery(self::TABLE_USERS);
      // get the books table query string as `create_books_table_query`
      $create_books_table_query = $this->getTableQuery(self::TABLE_BOOKS);

      
      // Perform the `create_db_query` against our `pdo` database,
      // to safely create a database, and asing it's result to a `databaseCreated`
      $databaseCreated = $this->pdo->query($create_db_query);

      // select this as our default database
      $this->pdo->query("use " . $this->db_name);
      
      // Perform the `create_users_table_query` against our `pdo` database,
      // to safely create a table in our database, and asign it's result to a `usersTableCreated` 
      $usersTableCreated = $this->pdo->query($create_users_table_query);
      // ...do the same for `books` table
      $booksTableCreated = $this->pdo->query($create_books_table_query);


      // If our database and both tables were created successfully...
      if ($databaseCreated && $usersTableCreated && $booksTableCreated) {
        // set `result` to TRUE
        $result = true;

      }else {
        // update creation errors
        $this->updateCreateErrors($this::ERROR_FOUND, "[dbCreateViaPdo]: Creation of database and table failed");
      }

    } catch (PDOException $pdo_e) {
      // update creation errors
      $this->updateCreateErrors($this::ERROR_FOUND, "[dbCreateViaPdo]: Failed to create database or table - " . $pdo_e->getMessage());
    } catch (Exception $e) {
      // update creation errors
      $this->updateCreateErrors($this::ERROR_FOUND, "[dbCreateViaPdo]: Failed to create database or table - " . $pdo_e->getMessage());
    }

    // return the `result`
    return $result;

  }



  /**
   * Resets the connection error variables (i.e. `connect_errno` and `connect_error`)
   */
  private function resetConnectErrors() {
    $this->connect_errno = 0;
    $this->connect_error = "";
  }

  /**
   * Resets the creation error variables (i.e. `create_errno` and `create_error`)
   */
  private function resetCreateErrors() {
    $this->create_errno = 0;
    $this->create_error = "";
  }


  /**
   * Updates the connection errors
   *
   * @param int errno - Error code
   * @param string error - Error message
   */
  private function updateConnectErrors($errno, $error) {
    $this->connect_errno = $errno;
    $this->connect_error = $error;
  }


  /**
   * Updates the creation errors
   *
   * @param int errno - Error code
   * @param string error - Error message
   */
  private function updateCreateErrors($errno, $error) {
    $this->create_errno = $errno;
    $this->create_error = $error;
  }

}