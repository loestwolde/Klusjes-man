<?php
include_once 'database.php';

class KlantenOpvragen extends Database {

// alle informatie van een klant ophalen
public function haalKlantInfoOp($id) {

    $query = "SELECT CONCAT(klanten.voorletters, ' ', klanten.achternaam) AS naam,
                     CONCAT(adressen.straat, ' ', adressen.huisnummer, IFNULL(adressen.huisnummer_toevoeging, ''),', ',
                     adressen.postcode) AS adres, 
                     adressen.woonplaats,
                     klanten.email,
                     klanten.telefoon,

        CASE
        -- als de einddatum leeg is, dan woont de klant daar nog steeds, dus dan staat er sinds
            WHEN klant_adres.einddatum IS NULL 
                THEN CONCAT('Sinds ', klant_adres.startdatum)
        -- anders staat er vanaf de startdatum tot en met de einddatum
            ELSE 
                CONCAT(klant_adres.startdatum, ' t/m ', klant_adres.einddatum)
        -- dat wordt opgeslagen onder periode
        END AS periode,

        CASE
        -- als de einddatum niet bekend is, dan is de status: huidig adres
            WHEN klant_adres.einddatum IS NULL 
                THEN 'Huidig adres'
        -- wel een einddatum? dan is het een oud adres
            ELSE 'Oud adres'
        END AS status

    FROM klanten

    JOIN klant_adres 
        ON klanten.klant_id = klant_adres.klant_id

    JOIN adressen 
        ON klant_adres.adres_id = adressen.adres_id
    
    WHERE klanten.klant_id = ?

    ORDER BY klant_adres.startdatum DESC";

    return parent::voerQueryUit($query, [$id]);
}


    
// functie om alle klanten op te halen
public function haalKlantenOp()
{
    $query = "SELECT klant_id, voorletters, achternaam
    FROM klanten
    ORDER BY achternaam";

    return parent::voerQueryUit($query);
}



// functie waarmee je het adres kunt wijzigen
public function wijzigAdres($klant_id, $straat, $huisnummer, $postcode, $woonplaats)
{

// nieuw adres toevoegen
$query = "INSERT INTO adressen(straat, huisnummer, postcode, woonplaats)

VALUES (?,?,?,?)";


parent::voerQueryUit(
$query,[$straat, $huisnummer, $postcode, $woonplaats]);

// het id pakken van het net aangemaakte adres
$adres_id = $this->laatsteInsertId();

// huidige adres van de klant aanpassen

$query = "UPDATE klant_adres
SET adres_id = ?
WHERE klant_id = ?
AND einddatum IS NULL";

return parent::voerQueryUit($query,[$adres_id, $klant_id]);

}

// een heel nieuw adres aanmaken, dit is nodig voor een verhuizing
public function nieuwAdres($klant_id, $straat, $huisnummer, $postcode, $woonplaats, $startdatum)
{

// oude adres afsluiten, je zoekt het adres waar de einddatum null is
// dan wordt die einddatum de startdatum van het nieuwe huidige adres

$query = "UPDATE klant_adres
SET einddatum = ?
WHERE klant_id = ?
AND einddatum IS NULL";

parent::voerQueryUit(
$query,[$startdatum, $klant_id]);

// nieuw adres maken in de adressen tabel
$query = "INSERT INTO adressen(straat, huisnummer, postcode, woonplaats)

VALUES (?,?,?,?)";

parent::voerQueryUit($query,[$straat, $huisnummer, $postcode, $woonplaats]);

// id van het aangemaakte adres pakken, je moet dat koppelen aan de klant
$adres_id = $this->laatsteInsertId();


// koppeling maken, dus je geeft aan over welke klant het gaat, welk adres en de startdatum
$query = "INSERT INTO klant_adres(klant_id, adres_id, startdatum)

VALUES(?,?,?)";

return parent::voerQueryUit($query,[$klant_id, $adres_id, $startdatum]);

}
}
?>