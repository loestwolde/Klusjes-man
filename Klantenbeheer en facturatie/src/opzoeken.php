<?php
include_once 'database.php';

class KlantenZoeken extends Database {

// deze functie gebruik je bij het zoeken
    public function zoekKlanten($zoekterm)
{
    $query = "SELECT klanten.*, adressen.woonplaats
    FROM klanten
    
    JOIN klant_adres
    ON klanten.klant_id = klant_adres.klant_id
    
    JOIN adressen
    ON klant_adres.adres_id = adressen.adres_id
    -- je krijgt alleen de huidige adressen te zien
    WHERE klant_adres.einddatum IS NULL
    
    -- je kunt zoeken op naam, adres, woonplaats
    AND (klanten.voorletters LIKE ?
    OR klanten.achternaam LIKE ?
    OR adressen.straat LIKE ?
    OR adressen.postcode LIKE ?
    OR adressen.woonplaats LIKE ?)
    
    ORDER BY klanten.achternaam";

    $zoek = "%$zoekterm%";

    return parent::voerQueryUit($query,[$zoek, $zoek, $zoek, $zoek, $zoek]);
}
}
?>