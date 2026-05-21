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
        $query = "SELECT k.*, a.straat, a.huisnummer, a.huisnummer_toevoeging, a.postcode, a.woonplaats
                  FROM klanten k
                  LEFT JOIN klant_adres ka ON k.klant_id = ka.klant_id
                  LEFT JOIN adressen a ON ka.adres_id = a.adres_id
                  WHERE k.klant_id = ?
                  ORDER BY ka.startdatum DESC
                  LIMIT 1";
        return parent::voerQueryUit($query, [$klantId]);
    }

    public function saveCustomer(
        $voorletters,
        $achternaam,
        $aanhef,
        $email,
        $telefoon,
        $woonplaats,
        $postcode,
        $straat,
        $huisnummer,
        $huisnummer_toevoeging
    ) {
        if(
            $voorletters == "" ||
            $achternaam  == "" ||
            $aanhef      == "" ||
            $email       == "" ||
            $telefoon    == "" ||
            $woonplaats  == "" ||
            $postcode    == "" ||
            $straat      == "" ||
            $huisnummer  == ""
        ) {
            return false;
        }

        // 1. Klant invoegen
        $query = "INSERT INTO klanten (voorletters, achternaam, aanhef, email, telefoon)
                  VALUES (?, ?, ?, ?, ?)";
        $klantOpgeslagen = parent::voerQueryUit($query, [
            $voorletters, $achternaam, $aanhef, $email, $telefoon
        ]);

        if (!$klantOpgeslagen) {
            return false;
        }

        // 2. Klant-id ophalen
        $klantId = $this->getLastId();

        // 3. Adres invoegen
        $adresId = $this->adresToevoegenAanKlant(
            $woonplaats, $postcode, $straat, $huisnummer, $huisnummer_toevoeging
        );

        if (!$adresId) {
            return false;
        }

        // 4. Koppelen
        return $this->klantAanAdresKoppelen($klantId, $adresId);
    }

    public function updateCustomer(
        $klantId,
        $voorletters,
        $achternaam,
        $aanhef,
        $email,
        $telefoon,
        $woonplaats,
        $postcode,
        $straat,
        $huisnummer,
        $huisnummer_toevoeging
    ) {
        if(
            $voorletters == "" ||
            $achternaam  == "" ||
            $aanhef      == "" ||
            $email       == "" ||
            $telefoon    == "" ||
            $woonplaats  == "" ||
            $postcode    == "" ||
            $straat      == "" ||
            $huisnummer  == ""
        ) {
            return false;
        }

        // 1. Klantgegevens bijwerken
        $query = "UPDATE klanten
                  SET voorletters = ?, achternaam = ?, aanhef = ?, email = ?, telefoon = ?
                  WHERE klant_id = ?";
        parent::voerQueryUit($query, [
            $voorletters, $achternaam, $aanhef, $email, $telefoon, $klantId
        ]);

        // 2. Nieuw adres aanmaken
        $adresId = $this->adresToevoegenAanKlant(
            $woonplaats, $postcode, $straat, $huisnummer, $huisnummer_toevoeging
        );

        if (!$adresId) {
            return false;
        }

        // 3. Nieuwe koppeling aanmaken (houdt adreshistorie bij)
        return $this->klantAanAdresKoppelen($klantId, $adresId);
    }

    public function deleteCustomer($klantId)
    {
        // 1. Adres id's ophalen
        $query = "SELECT adres_id FROM klant_adres WHERE klant_id = ?";
        $adressen = parent::voerQueryUit($query, [$klantId]);

        // 2. Koppeling verwijderen
        $query = "DELETE FROM klant_adres WHERE klant_id = ?";
        parent::voerQueryUit($query, [$klantId]);

        // 3. Adressen verwijderen
        if($adressen){
            foreach($adressen as $adres){
                $query = "DELETE FROM adressen WHERE adres_id = ?";
                parent::voerQueryUit($query, [$adres['adres_id']]);
            }
        }

        // 4. Klant verwijderen
        $query = "DELETE FROM klanten WHERE klant_id = ?";
        return parent::voerQueryUit($query, [$klantId]);
    }

    public function adresToevoegenAanKlant(
        $woonplaats,
        $postcode,
        $straat,
        $huisnummer,
        $huisnummer_toevoeging
    ) {
        $query = "INSERT INTO adressen (woonplaats, postcode, straat, huisnummer, huisnummer_toevoeging)
                  VALUES (?, ?, ?, ?, ?)";
        parent::voerQueryUit($query, [
            $woonplaats, $postcode, $straat, $huisnummer, $huisnummer_toevoeging
        ]);

        return $this->getLastId();
    }

    public function klantAanAdresKoppelen($klantId, $adresId)
    {
        $query = "INSERT INTO klant_adres (klant_id, adres_id, startdatum)
                  VALUES (?, ?, ?)";
        return parent::voerQueryUit($query, [
            $klantId, $adresId, date('Y-m-d')
        ]);
    }
}