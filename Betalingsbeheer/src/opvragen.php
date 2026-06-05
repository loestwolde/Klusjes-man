<?php
include_once 'database.php';

class KlantenOpvragen extends Database {

    public function haalKlantInfoOp($id) {

        $query = "SELECT
            CONCAT(klanten.voorletters, ' ', klanten.achternaam) AS naam,

            CONCAT(
                adressen.straat, ' ',
                adressen.huisnummer,
                IFNULL(adressen.huisnummer_toevoeging, ''),
                ', ',
                adressen.postcode
            ) AS adres,

            adressen.woonplaats,

            CASE 
                WHEN klant_adres.einddatum IS NULL 
                    THEN CONCAT('Sinds ', klant_adres.startdatum)
                ELSE 
                    CONCAT(klant_adres.startdatum, ' t/m ', klant_adres.einddatum)
            END AS periode,

            CASE 
                WHEN klant_adres.einddatum IS NULL 
                    THEN 'Huidig adres'
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
}
?>