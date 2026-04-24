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
    public function saveCustomer($voorletters, $achternaam, $aanhef, $email, $telefoon)
    {
        $lastId = $this->getlastid();
        // controleren of alle velden gevuld zijn
        if($voorletters == "" || $achternaam == "" || $aanhef == "" || $email == "" || $telefoon == "")
        {
            return false;
        }

        // HIER AANVULLEN

        
        $query = "INSERT INTO klanten (voorletters, achternaam, aanhef, email, telefoon) VALUES (?, ?, ?, ?, ?)";
        return parent::voerQueryUit($query, [$voorletters, $achternaam, $aanhef, $email,  $telefoon]);
    }
}