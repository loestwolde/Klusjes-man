<?php
include('database.php');
class Customer extends Database
{
    public function getAllCustomers()
    {
        $query = "SELECT * FROM klanten";
        return $this->voerQueryUit($query);
    }
    public function getCustomer($klantId)
    {
        $query = "SELECT * FROM klanten WHERE klant_id = ?";
        return parent::voerQueryUit($query, [$klantId]);
    }
}