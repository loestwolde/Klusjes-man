<?php
include_once 'database.php';

class KlantenZoeken extends Database {

    public function getCustomerOnLastAndFirstName($zoekterm)
    {
        $query = "SELECT klanten.*, adressen.woonplaats
                  FROM klanten

                  JOIN klant_adres
                  ON klanten.klant_id = klant_adres.klant_id   
                  JOIN adressen
                  ON klant_adres.adres_id = adressen.adres_id
                  WHERE voorletters LIKE ?
                  OR achternaam LIKE ?
                  ORDER BY achternaam";

        $zoek = "%$zoekterm%";

        return parent::voerQueryUit($query, [$zoek, $zoek]);
    }
}
?>