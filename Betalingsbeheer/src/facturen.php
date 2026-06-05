<?php
include_once 'database.php';

class Facturen extends Database {

public function haalFacturenOp($klant_id)
    {
        $query = "SELECT *
            FROM factuur
            WHERE klant_id = ?
            ORDER BY factuurdatum DESC";

        return parent::voerQueryUit($query, [$klant_id]);
    }

public function haalFactuurregelsOp($factuur_id)
    {
        $query = "SELECT *
        FROM factuurregels
        WHERE factuur_id = ?";

    return parent::voerQueryUit($query, [$factuur_id]);

    }

public function maakFactuur($klant_id, $uren, $voorrijtijd, $totaal)
{
    $query = "INSERT INTO factuur 
    (klant_id, uren, voorrijtijd, totaalbedrag, betaald, factuurdatum)
    VALUES (?, ?, ?, ?, 0, NOW())";

    return parent::voerQueryUit($query, [$klant_id, $uren, $voorrijtijd, $totaal]);
}

public function maakFactuurRegel($factuur_id, $omschrijving, $aantal, $prijs)
{
    $totaal = $aantal * $prijs;

    $query = "INSERT INTO factuurregels 
    (factuur_id, omschrijving, aantal, prijs_per_stuk, totaalprijs)
    VALUES (?, ?, ?, ?, ?)";

    return parent::voerQueryUit($query, [$factuur_id, $omschrijving, $aantal, $prijs, $totaal]);
}

public function haalWerkzaamhedenOp()
{
    $query = "SELECT * FROM werkzaamheden
              ORDER BY omschrijving";

    return parent::voerQueryUit($query);
}

public function haalArtikelenOp()
{
    $query = "SELECT * FROM artikelen
              ORDER BY naam";

    return parent::voerQueryUit($query);
}

}
?>