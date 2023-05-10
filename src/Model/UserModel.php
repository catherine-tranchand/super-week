<?php
/**
* @license MIT
* super-week
*
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
* @name User Model
* @test test/user_model.php
* @file UserModel.php
* @author: Abraham Ukachi <abraham.ukachi@laplateforme.io>
* @version: 0.0.1
* 
* Example usage:
*   
*   1+|> // Get all users using the `findAll()` method
*    -|> 
*    -|> use App\Model\UserModel;
*    -|>
*    -|> $userModel = new UserModel();
*    -|> $users = $userModel->findAll();
*    -|> 
*/


// declare a namespace for this `UserModel` class
namespace App\Model;


// use these classes
use App\Model\Helper\Database;
use pdo;
// use datetime;
// use PDO;
use PDOException;


/**
 * A class that represents our `users` table in the database
 * NOTE: This class currently extends a Database class
 *
 * TODO: Create an abstract Model class that extends the abstract Database class, 
 *       which in turn should implement a DatabaseInterface
 */
class UserModel extends Database {

  // Declare some constants


  // Declare some public properties


  // Declare some private properties
  private ?int $id = null;
  private ?string $email = null;
  private ?string $firstName = null;
  private ?string $lastName = null;


  /**
   * User constructor.
   * NOTE: This constructor is called when a new instance of the User class is created
   */
  public function __construct() {
    // call the parent/Database constructor
    parent::__construct(Database::TYPE_PDO); // <- via PDO

    // connect to the database 
    $this->dbConnection();


    // If the user is *appears to be* connected...
    if ($this->isConnected()) {
      // ...get the user's token from session
      $token = $this->getSessionToken();
      // get the user's details from the database using this `$token`
      $user = $this->findByToken($token);

      // If the user exists...
      if ($user) {
        // ...populate the `UserModel`'s properties
        $this->populateProps($user);
      } else {
        // ...else, disconnect the user 'cause duh! Somethin' doesn't seam right #LOL
        $this->disconnect();
      }

    }

  }


  // PUBLIC SETTERS
  
  /**
   * Sets the first name of the user
   * NOTE: This method sets the `firstName` property only after 
   * updating the `first_name` column in the database w/ the given $fistName
   * 
   * @param string $firstName - the first name of the user to set
   * @return void
   */
  public function setFirstName(string $firstName): void {

    // create an sql query named `update_firstname_query`,
    $update_firstname_query = sprintf(<<<SQL
      UPDATE %s
      SET %s = :firstName
      WHERE %s = :id
    SQL,
    
    // update (table)
    $this::TABLE_USERS,

    // fields
    $this::FIELD_FIRST_NAME, // <- set
    $this::FIELD_ID // <- where

    );
    
    try { // <- try to prepare and execute the `update_firstname_query`
      
      // prepare the `update_firstname_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($update_firstname_query);
      
      // execute our PDO statement
      $pdo_stmt->execute([
        ':firstName' => $firstName,
        ':id' => $this->id
      ]);

      // Now, we can set the `firstName` property
      $this->firstName = $firstName;

    } catch (PDOException $e) { // <- catch any PDO exceptions
      // log the error message
      error_log($e->getMessage());
    }

  }
  
  
  /**
   * Sets the last name of the user
   * NOTE: This method sets the `lastName` property only after 
   * updating the `last_name` column in the database w/ the given $lastName
   * 
   * @param string $lastName - the last name of the user to set
   * @return void
   */
  public function setLastName(string $lastName): void {

    // create an sql query named `update_lastname_query`,
    $update_lastname_query = sprintf(<<<SQL
      UPDATE %s
      SET %s = :lastName
      WHERE %s = :id
    SQL,
    
    // update (table)
    $this::TABLE_USERS,

    // fields
    $this::FIELD_LAST_NAME, // <- set
    $this::FIELD_ID // <- where

    );
    
    try { // <- try to prepare and execute the `update_lastname_query`
      
      // prepare the `update_lastname_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($update_lastname_query);
      
      // execute our PDO statement
      $pdo_stmt->execute([
        ':lastName' => $lastName,
        ':id' => $this->id
      ]);

      // Now, we can set the `lastName` property
      $this->lastName = $lastName;

    } catch (PDOException $e) { // <- catch any PDO exceptions
      // log the error message
      error_log($e->getMessage());
    }

  }


  // PUBLIC GETTERS

  
  /**
   * Returns the id of the user
   */
  public function getId(): int {
    return $this->id ?? -1;
  }


  /**
   * Returns the first name of the user
   *
   * @return string 
   */
  public function getFirstName(): string {
    return $this->firstName ?? '';
  }

  /**
   * Returns the last name of the user
   *
   * @return string 
   */
  public function getLastName(): string {
    return $this->lastName ?? '';
  }


  /**
   * Returns the full name of the user
   *
   * @return string
   */
  public function getFullName(): string {
    // get the first and last names as `$firstName` and `$lastName` respectively
    $firstName = $this->getFirstName();
    $lastName = $this->getLastName();

    // return the full name of the user
    return $firstName . ' ' . $lastName;
  }


  /**
   * Returns a total count of all the users in the database
   *
   * @return int
   */
  public function countAll(): int {
    // find all the users in the database
    $allUsers = $this->findAll();
    // return the toal count of all the users in the database
    return count($allUsers);
  }

  // PUBLIC METHODS


  /**
   * Method used to find all the users in the database
   *
   * @return array - an associative array of all the users in the database
   */
  public function findAll(): array {
    // create an sql query named `find_users_query`,
    // using a heredoc syntax - https://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
    $find_users_query = sprintf(<<<SQL
      SELECT * 
      FROM %s
    SQL,

    // from (table)
    $this::TABLE_USERS
    );

    // prepare the `find_users_query` as a PDO statement
    $pdo_stmt = $this->pdo->prepare($find_users_query);

    // execute our PDO statement
    $pdo_stmt->execute();

    // fetch all the results from `pdo_stmt` as an associative array
    $results = $pdo_stmt->fetchAll(PDO::FETCH_ASSOC);

    // return the results
    return $results;
  }



  /**
   * Creates a new user with the given parameters
   * NOTE: This method inserts a new user into the database
   *
   * @param string $email - the email of the user
   * @param string $firstName - the first name of the user
   * @param string $lastName - the last name of the user
   * @param string $password - the password of the user
   *
   * @return array|false - Returns an array including the user's id of the newly created user, or false if the user was not created
   */
  public function create(string $email, string $firstName, string $lastName, string $password): array|false {
    // Initialize the `result` boolean variable
    $result = false;

    // create an sql query named `create_user_query`,
    $create_user_query = sprintf(<<<SQL
      INSERT INTO %s 
        (%s, %s, %s, %s)
      VALUES 
        (:email, :firstName, :lastName, :password)
      SQL,

      // table
      self::TABLE_USERS,

      // fields
      self::FIELD_EMAIL,
      self::FIELD_FIRST_NAME,
      self::FIELD_LAST_NAME,
      self::FIELD_PASSWORD
    );

    try { // <- try to create a new user

      // prepare the `create_user_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($create_user_query);

      // execute our PDO statement with the given parameters,
      // and store the result in a variable named `$result`
      $pdo_stmt->execute([
        ':email' => $email,
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':password' => $password
      ]);

      // get the last inserted id as `userId`
      $userId = $this->pdo->lastInsertId();

      // update the `result` w/ a short-syntax associative array of the new user
      $result = [
        'id' => $userId,
        'email' => $email,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'password' => $password
      ];

    } catch (PDOException $e) { // <- catch any PDOException errors

      // log the error
      error_log($e->getMessage());

    }

    // return the `result`
    return $result;
  }



  /**
   * Method used to connect the user with the given `$email` and `$password`
   *
   * @param string $email - the email of the user
   * @param string $password - the password of the user
   *
   * @return array|false - Returns an array including the user's id of the connected user, or FALSE if the user was not created
   */
  public function connect(string $email, string $password): array|false {
    // TODO: Check if the user is already connected

    // Initialize the `result` boolean variable
    $result = false;
    
    // Find the user with the given email as `user`
    $user = $this->findByEmail($email, true);


    // If the user exists and if the password is correct...
    if ($user && password_verify($password, $user['password'])) { 
      // ...get the user's id as `userId`
      $userId = $user['id'];

      // generate a new token for the user as `token`
      $token = $this->generateToken(); // returns eg.: "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"
      
      // store the `token` in the session and database
      $this->storeUserToken($userId, $token);

      // add the `token` to the `user` array
      $user[self::FIELD_TOKEN] = $token;

      // populate the `userModel` properties with the `populateProps()` method
      $this->populateProps($user);
      
      // update the `result` variable by setting it to the `$user` array
      $result = $user;
      
    } 

    // return the `result`
    return $result;
  }


  /**
   * Disconnects the user
   * NOTE: This method removes the user's token from the database and session
   *
   * @return bool - Returns TRUE if the user was disconnected, FALSE otherwise
   */
  public function disconnect(): bool {
    // Initialize the `result` boolean variable
    $result = false;

    // If the user is conected...
    if ($this->isConnected()) {
      // ...get the user's id as `userId`
      $userId = $this->getId();

      // remove the user's token from the database
      $this->removeUserToken($userId);

      // remove the user's token from the session
      $this->removeSessionToken();

      // update the `result` variable by setting it to TRUE
      $result = true;

    }else {

      // HACK: Even if the user is not connected,
      //       we still wanna remove the session token JUST TO BE SAFE ;)
      $this->removeSessionToken();
    }

    // return `result`
    return $result;
  }


  /**
   * Checks if the current user is connected
   *
   * @return bool - Returns TRUE if the user is connected, FALSE otherwise
   */
  public function isConnected(): bool {
    // Initialize the `result` boolean variable
    $result = false;

    // IDEA: Verify that the session token exists in the database

    // get the session token as `sessionToken`
    $sessionToken = $this->getSessionToken();
    
    // DEBUG [4dbsmaster]: tell me about it ;)
    //printf('<pre>sessionToken: %s</pre>', $sessionToken);

    // if there's a session token and if this `sessionToken` is verified...
    if (!empty($sessionToken) && $this->verifyToken($sessionToken)) {
      // ...update the `result` variable by setting it to TRUE
      $result = true;
    }

    // return `result`
    return $result;

  }
  



  /**
   * Method used to find a user with the given `$userId` 
   *
   * @param string $userId - the id to find the user with
   *
   * @return array|false - Returns an array containing the user's details, or FALSE if the user was not found
   *
   */
  public function findById(string $userId): array|false {
    // Initialize the `result` boolean variable
    $result = false;


    // create a `find_user_by_id_query` sql query
    $find_user_by_id_query = sprintf(<<<SQL
      SELECT %s, %s, %s, %s, %s 
      FROM %s
      WHERE %s = :userId

    SQL,

    // select (fields)
    self::FIELD_ID,
    self::FIELD_EMAIL,
    self::FIELD_FIRST_NAME,
    self::FIELD_LAST_NAME,
    self::FIELD_TOKEN,

    // from (table)
    self::TABLE_USERS,

    // where (field)
    self::FIELD_ID

    );

    try { // <- try to prepare and execute our query

      // prepare the `find_user_by_id_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($find_user_by_id_query);

      // execute our PDO statement with the given parameters
      $pdo_stmt->execute([
        ':userId' => $userId
      ]);
      
      // fetch the results from `pdo_stmt` as an associative array
      $result = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) { // <- catch any PDOException errors

      // log the error
      error_log($e->getMessage());

    }

    // return the `result`
    return $result;
  }




  /**
   * Method used to find a user with the given `$token` 
   *
   * @param string $token - the token to find the user with
   *
   * @return array|false - Returns an array containing the user's details, or FALSE if the user was not found
   *
   */
  public function findByToken(string $token): array|false {
    // Initialize the `result` boolean variable
    $result = false;


    // create a `find_user_by_token_query` sql query
    $find_user_by_token_query = sprintf(<<<SQL
      SELECT %s, %s, %s, %s, %s 
      FROM %s
      WHERE %s = :token

    SQL,

    // select (fields)
    self::FIELD_ID,
    self::FIELD_EMAIL,
    self::FIELD_FIRST_NAME,
    self::FIELD_LAST_NAME,
    self::FIELD_TOKEN,

    // from (table)
    self::TABLE_USERS,

    // where (field)
    self::FIELD_TOKEN

    );

    try { // <- try to prepare and execute our query

      // prepare the `find_user_by_token_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($find_user_by_token_query);

      // execute our PDO statement with the given parameters
      $pdo_stmt->execute([
        ':token' => $token
      ]);
      
      // fetch the results from `pdo_stmt` as an associative array
      $result = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) { // <- catch any PDOException errors

      // log the error
      error_log($e->getMessage());

    }

    // return the `result`
    return $result;
  }




  /**
   * Method used to find a user by their email
   *
   * @param string $email - the email of the user
   * @param bool $includesPassword - whether or not the password should be included in the result
   *
   * @return array|false - Returns an array containing the user's details, or FALSE if the user does not exist
   */
  public function findByEmail(string $email, bool $includesPassword = false): array|false {
    // Initialize the `result` boolean variable
    $result = false;


    // create a `find_user_by_email_query` sql query
    $find_user_by_email_query = sprintf(<<<SQL
      SELECT %s, %s, %s, %s, %s %s
      FROM %s
      WHERE %s = :email

    SQL,

    // select (fields)
    self::FIELD_ID,
    self::FIELD_EMAIL,
    self::FIELD_FIRST_NAME,
    self::FIELD_LAST_NAME,
    self::FIELD_TOKEN,
    $includesPassword ? ', ' . self::FIELD_PASSWORD : '',

    // from (table)
    self::TABLE_USERS,

    // where (field)
    self::FIELD_EMAIL

    );

    try { // <- try to prepare and execute our query

      // prepare the `find_user_by_email_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($find_user_by_email_query);

      // execute our PDO statement with the given parameters
      $pdo_stmt->execute([
        ':email' => $email
      ]);
      
      // fetch the results from `pdo_stmt` as an associative array
      $result = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) { // <- catch any PDOException errors

      // log the error
      error_log($e->getMessage());

    }

    // return the `result`
    return $result;
  }




  // PRIVATE SETTERS

  /**
   * Method used to populate the `userModel` properties with the given `$user`
   *
   * @param array $user - the user to populate the properties with
   *
   * @return void
   * @private
   */
  private function populateProps(array $user): void {
    // set the `id` property to the `id` of the given `$user`
    $this->id = $user[self::FIELD_ID];
    
    // set the `email` property to the `email` of the given `$user`
    $this->email = $user[self::FIELD_EMAIL];
    
    // set the `firstName` property to the `first_name` of the given `$user`
    $this->firstName = $user[self::FIELD_FIRST_NAME];
    
    // set the `lastName` property to the `last_name` of the given `$user`
    $this->lastName = $user[self::FIELD_LAST_NAME];

  }





  // PRIVATE GETTERS

  /**
   * Method used to get the user's token from the session
   *
   * @return string - the token from the session
   * @private
   */
  private function getSessionToken(): string {
    // Safely create the `user` session
    $this->createUserSession();

    // return the `token` session variable
    return $_SESSION['user']['token'] ?? '';
  }


  /**
   * Method used to remove the user's token from the session
   * NOTE: This method is private, and is only used internally ;)
   *
   * @return void
   * @private
   */
  private function removeSessionToken(): void {
    // Safely create the `user` session
    $this->createUserSession();

    // unset the `token` session variable
    unset($_SESSION['user']['token']);
  }


  /**
   * Method used to remove or delete the user's token from the database
   *
   * @param int $userId - the id of the user to remove the token from
   * @return bool - whether or not the token was successfully removed
   * @private
   */
  private function removeUserToken($userId): bool {
    // Initialize the `result` boolean variable
    $result = false;

    // create a `remove_user_token_query` sql query
    $remove_user_token_query = sprintf(<<<SQL
      UPDATE %s
      SET %s = NULL
      WHERE %s = :id

    SQL,

    // update (table)
    self::TABLE_USERS,

    // set (field)
    self::FIELD_TOKEN,

    // where (field)
    self::FIELD_ID

    );

    try { // <- try to prepare and execute our query

      // prepare the `remove_user_token_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($remove_user_token_query);

      // execute our PDO statement with the given parameters
      $pdo_stmt->execute([
        ':id' => $userId
      ]);

      // set the `result` to true
      $result = true;

    } catch (PDOException $e) { // <- catch any PDOException errors

      // log the error
      error_log($e->getMessage());

    }

    // return the `result`
    return $result;
  }



  // PRIVATE METHODS
  
  /**
   * Creates a `user` session variable, if it doesn't exist
   * NOTE: This method is private, and is only used internally ;)
   *
   * @return void
   */
  private function createUserSession(): void {
    // If there's no `user` session variable
    if (!isset($_SESSION['user'])) {

      // set the `user` session variable to an empty array
      $_SESSION['user'] = [];
    }
  }



  /**
   * Updates the `user` session variable with the given `$key` and `$value`
   * NOTE: This method is private, and is only used internally ;)
   *
   * @param string $key - the key to update
   * @param mixed $value - the value to update the key with
   *
   * @return void
   */
  private function updateUserSession(string $key, mixed $value): void {
    // Safely create the `user` session
    $this->createUserSession();

    // Update the `user` session variable with the given `$key` and `$value`
    $_SESSION['user'][$key] = $value;
  }



  /**
   * Generates a random token
   *
   * @return string - the generated token
   *
   * @private
   */
  private function generateToken(): string {
    // Initialize the `token` string variable
    $token = '';

    // Generate a random token
    $token = bin2hex(random_bytes(32)); // <- 32 bytes = 256 bits = 64 hex characters | eg.: 1a79a4d60de6718e8e5b326e338ae533

    // return the `token`
    return $token;
  }


  /**
   * Method used to store the given `$token` in the `user` database *AND* session variable
   *
   * @param int $userId - the id of the user to store the token for
   * @param string $token - the token to store
   *
   * @return void
   * @private
   */
  private function storeUserToken(int $userId, string $token): void {
    
    // IDEA: Store the user's token in the database first, then update its value in the session variable

    // create a `store_user_token_query` sql query
    $store_user_token_query = sprintf(<<<SQL
      UPDATE %s
      SET %s = :token
      WHERE %s = :id
    SQL,

    // table
    self::TABLE_USERS,

    // fields
    self::FIELD_TOKEN,
    self::FIELD_ID
    );

    // try to prepare and execute our query
    try {
      // prepare the `store_user_token_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($store_user_token_query);

      // execute our PDO statement with the given parameters
      $pdo_stmt->execute([
        ':token' => $token,
        ':id' => $userId
      ]);


      // Now, we need to store the token in the session variable ;)
  
      // Update the `token` key in the `user` session variable with the given `$token`
      $this->updateUserSession('token', $token);

    } catch (PDOException $e) {
      // log the error
      error_log($e->getMessage());
    }

  }






  /**
   * Checks if the given `$token` is valid or exists in the database
   * NOTE: This method is private, and is only used internally ;)
   *
   * @param string $token - the token to check
   *
   * @return bool - Returns TRUE if the token is valid, FALSE otherwise
   *
   * @private
   */
  private function verifyToken(string $token): bool {
    // Initialize the `result` boolean variable
    $result = false;

    // create a `verify_token_query` sql query
    $verify_token_query = sprintf(<<<SQL
      SELECT %s 
      FROM %s
      WHERE %s = :token
      
      SQL,

      // select (field)
      self::FIELD_ID,

      // from (table)
      self::TABLE_USERS,

      // where (field)
      self::FIELD_TOKEN
    );

    // try to prepare and execute our query
    try {
      // prepare the `verify_token_query` as a PDO statement
      $pdo_stmt = $this->pdo->prepare($verify_token_query);

      // execute our PDO statement with the given parameters
      $pdo_stmt->execute([
        ':token' => $token
      ]);

      // get the result from `pdo_stmt` as an associative array,
      // and assign it to a `user` variable
      $user = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

      // update the `result` variable accordingly
      $result = empty($user) ? false : true; // <- if `$user` is empty, set `result` to false, otherwise set it to true
      
    } catch (PDOException $e) {
      // log the error
      error_log($e->getMessage());
    }


    // return the `result` variable
    return $result;

  }



}

