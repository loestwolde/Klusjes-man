<?php
require_once('../config/db_config.php');

class Database
{
    private $connectie;

    public function __construct()
    {
        $this->connectie = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    }

    public function getlastid()
    {
        return $this->connectie->lastInsertId();
    }

    public function voerQueryUit($query, $params = []) 
    {
        // Check if the query is a SELECT query
        $statement = $this->connectie->prepare($query);
        $statement->execute($params);

        if(str_contains($query, 'SELECT'))
        {
            // Query is a SELECT, fetch the results
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            // Query is INSERT, UPDATE or DELETE, return number of rows
            return $statement->rowCount();
        }
    }

    public function sluitVerbinding()
    {
        $this->connectie = null;
    }

    public function testVerbinding()
    {
        return (bool) $this->connectie;
    }

    public function __destruct()
    {
        $this->sluitVerbinding();
    }
}



