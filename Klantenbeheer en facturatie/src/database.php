<?php
require_once '../config/db_config.php';

class Database {

    private $connectie;

    public function __construct()
    {
        $this->connectie = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",DB_USER,DB_PASS);
        // nu zie je ook de speciale karakters
    }

    public function voerQueryUit($query, $params = [])
{
    $statement = $this->connectie->prepare($query);
    $statement->execute($params);

    if (str_contains($query, 'SELECT')) {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // TOEVOEGEN HIER
    if (str_contains($query, 'INSERT')) {
        return $this->connectie->lastInsertId();
    }

    return $statement->rowCount();
}

public function laatsteInsertId()
{
    return $this->connectie->lastInsertId();
}

    

    public function sluitVerbinding(){
        $this->connectie = null;
    }

    public function testVerbinding(){
        return (bool) $this->connectie;
    }

    public function __destruct(){
        $this->sluitVerbinding();
    }

}
