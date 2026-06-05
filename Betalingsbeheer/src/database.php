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

        if (str_contains($query, 'SELECT')){
            // dit krijg je als array terug uit de tabel, want dit is wel met SELECT
            // je krijgt dus rijen uit de tabel terug
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        else {
            // dit is als de query geen SELECT statement is
            // dit zijn dus het aantal rijen dat gewijzigd, toegevoegd of verwijderd wordt
            // de rowCount wordt dan dus anders
            $result = $statement->rowCount();

            return $result;
        }
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