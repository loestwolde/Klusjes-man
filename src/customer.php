<?php
include('database.php');
class Customer extends Database
{
    public function getAllCustomers()
    {
        $query = "SELECT * FROM klanten";
        return $this->voerQueryUit($query);
    }
}