<?php

namespace App\Model\Helper;

use PDO;
use PDOException;


class Database {
    private string $db_host = 'localhost';
    private string $db_username = "root";
    private string $db_password = "root";
    private int $port = 8889;
    private string $db_name = "super_week";

    public ?object $pdo = null;
    public ?object $db = null;

    public function __construct()
    {
        $this->dbConnect();
    }

    public function setDatabaseUsername(string $db_username)
    {
        $this->db_username = $db_username;
    }

    public function setDatabasePassword(string $db_password)
    {
        $this->db_password = $db_password;
    }

    public function setDatabasePort(int $port)
    {
        $this->port = $port;
    }

    public function dbConnect(): ?object
    {
        $pdo = null;
        $db_dsn = $this->getDSN();
        $db_options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', // BE SURE TO WORK IN UTF8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //ERROR TYPE
            PDO::ATTR_EMULATE_PREPARES => false // FOR NO EMULATE PREPARE (SQL INJECTION)
        ];

        try {
            $pdo = new pdo($db_dsn, $this->db_username, $this->db_password, $db_options);
            $this->pdo = $pdo;
            $this->db = $pdo;
        } catch (PDOException $e) {
            // update the connection errors
            die($e->getMessage());

        }
        return $pdo;
    }


    private function getDSN(): string {
        $default_dsn = "mysql:host=$this->db_host;dbname=$this->db_name";

        return ($this->port !== 8889) ? "$default_dsn;port={$this->port}" : $default_dsn;
    }

}