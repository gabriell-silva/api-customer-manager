<?php

namespace ApiCustomerManager\database;

use PDO;
use PDOException;

class InstanceDatabase
{
    private $host = "localhost:3306";
    private $port = "3306";
    private $username = "root";
    private $password = "#projeto$2024";
    private $db_name = "dbCustomerManager";

    public $instance;

    public function connection(): PDO
    {
        try {
        $this->instance = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);

        return $this->instance;
        } catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }

    }
}
