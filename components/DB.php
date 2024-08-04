<?php

namespace components;

use PDO;
use PDOException;

class DB
{
    protected string $host;

    protected string $username;

    protected string $password;

    protected string $database;

    protected static $connection;

    public function __construct(array $configs)
    {
        $this->host = $configs['database']['host'];
        $this->username = $configs['database']['username'];
        $this->password = $configs['database']['password'];
        $this->database = $configs['database']['database'];
    }

    public function connect(): ?PDO
    {
        try {
            self::$connection = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return self::$connection;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        return null;
    }


    /**
     * check if connection exists return or create
     *
     * @return PDO
     */
    public function getConnection(): ?PDO
    {
        if (!self::$connection) {
            $this->connect();
        }
        return self::$connection;
    }

}