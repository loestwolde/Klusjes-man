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
    public function saveCustomer($voorletters, $achternaam, $aanhef, $email, $telefoon, $woonplaats, $straat, $huisnummer, $huisnummer_toevoeging)
    {
        $lastId = $this->getlastid();
        // controleren of alle velden gevuld zijn
        if($voorletters == "" || $achternaam == "" || $aanhef == "" || $email == "" || $telefoon == "" || $woonplaats == "" || $straat == "" || $huisnummer == "" || $huisnummer_toevoeging == "")
        {
            return false;
        }

        // Adres toevoegen
        $adresId = $this->adresToevoegenAanKlant($woonplaats, $straat, $huisnummer, $huisnummer_toevoeging);

        // Klant aan adres koppelen
        $this->klantAanAdresKoppelen($lastId, $adresId);

        $query = "INSERT INTO klanten (voorletters, achternaam, aanhef, email, telefoon) VALUES (?, ?, ?, ?, ?)";
        return parent::voerQueryUit($query, [$voorletters, $achternaam, $aanhef, $email, $telefoon]);
    }

     public function adresToevoegenAanKlant($woonplaats, $straat, $huisnummer, $huisnummer_toevoeging)
     {
        $query = "INSERT INTO adressen (woonplaats, straat, huisnummer, huisnummer_toevoeging) VALUES (?, ?, ?, ?)";
        return parent::voerQueryUit($query, [$woonplaats, $straat, $huisnummer, $huisnummer_toevoeging]);
     }

     public function klantAanAdresKoppelen($klantId, $adresId)
     {
        $query = "INSERT INTO klant_adres (klant_id, adres_id, startdatum) VALUES (?, ?, ?)";
        return parent::voerQueryUit($query, [$klantId, $adresId, date('Y-m-d')]);
     }
}