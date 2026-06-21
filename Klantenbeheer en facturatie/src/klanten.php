<?php
include_once 'database.php';

class Klanten extends Database {

// een klant kunnen toevoegen met een insert statement
    public function voegKlantToe($voorletters, $achternaam, $aanhef, $email, $telefoon)
    {
        $query = "INSERT INTO klanten (voorletters, achternaam, aanhef, email, telefoon)
                VALUES (?,?,?,?,?)";

        return parent::voerQueryUit($query, [$voorletters, $achternaam, $aanhef, $email, $telefoon]);
    }

    // hetzelfde voor een adres toevoegen
    public function voegAdresToe($straat, $huisnummer, $postcode, $woonplaats)
    {
        $query = "INSERT INTO adressen (straat, huisnummer, postcode, woonplaats)
                VALUES (?,?,?,?)";

        return parent::voerQueryUit($query, [$straat, $huisnummer, $postcode, $woonplaats]);
    }

// een klant kan meerdere adressen hebben dus hier koppel je een klant aan een adres
// dan kan er in deze koppeltabel meer dan 1 adres per klant staan
    public function koppelKlantAdres($klantId, $adresId, $startdatum)
{
    $query = "INSERT INTO klant_adres (klant_id, adres_id, startdatum)
              VALUES (?,?,?)";

    return parent::voerQueryUit($query, [$klantId, $adresId, $startdatum]);
}
}