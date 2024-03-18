<?php

namespace ApiCustomerManager\database;

use PDO;
use PDOException;

class InstanceDatabase
{
    private $DB_HOST = "localhost";
    private $DB_PORT = "3306";
    private $DB_USERNAME = "root";
    private $DB_PASSWORD = "#projeto$2024";
    private $DB_NAME = "dbCustomerManager";

    public $instance;

    public function connection(): PDO
    {
        try {
            $this->instance = new PDO("mysql:host={$this->DB_HOST};port={$this->DB_PORT};dbname={$this->DB_NAME}", $this->DB_USERNAME, $this->DB_PASSWORD);
            $this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->instance;
        } catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }
}
