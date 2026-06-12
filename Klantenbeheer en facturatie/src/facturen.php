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

    public function haalKlussenOp()
{

$query="
SELECT *
FROM klussen
ORDER BY datum DESC
";

return parent::voerQueryUit($query);

}


public function haalFactuurOp($factuur_id)
{
    $query = "SELECT * FROM factuur WHERE factuur_id = ?";
    return parent::voerQueryUit($query, [$factuur_id]);
}


public function haalFactuurregelsOp($factuur_id)
    {
        $query = "SELECT *
        FROM factuurregels
        WHERE factuur_id = ?";

    return parent::voerQueryUit($query, [$factuur_id]);

    }

    // standaard factuurdatum is vandaag, vervaldatum is na 30 dagen
public function maakFactuur($klant_id, $uren, $voorrijtijd, $totaal)
{
    $query = "INSERT INTO factuur
    (
    klant_id,
    uren,
    voorrijtijd,
    totaalbedrag,
    betaald,
    factuurdatum,
    vervaldatum
    )

    VALUES
    (
     ?, ?, ?, ?, 0, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY)
    )";

    parent::voerQueryUit($query, [
        $klant_id,
        $uren,
        $voorrijtijd,
        $totaal
    ]);

    return $this->laatsteInsertId();
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

public function haalKlusMaterialenOp($klus_id)
{
    $query = "
        SELECT 
            artikelen.naam,
            klus_materialen.aantal,
            artikelen.prijs
        FROM klus_materialen

        JOIN artikelen
        ON klus_materialen.artikel_id = artikelen.artikel_id

        WHERE klus_materialen.klus_id = ?
    ";

    return parent::voerQueryUit($query, [$klus_id]);
}

public function haalArtikelenOp()
{
    $query = "SELECT * FROM artikelen
              ORDER BY naam";

    return parent::voerQueryUit($query);
}


// methode om klussen op te halen
public function haalKlusOp($klus_id)
{
    $query = "SELECT *
              FROM klussen
              WHERE klus_id = ?";

    return parent::voerQueryUit($query, [$klus_id]);
}

public function getStatus($factuur)
{
    if ($factuur['betaald'] == 1) {
        return "Betaald";
    }

    if (strtotime($factuur['vervaldatum']) < time()) {
        return "Te laat";
    }

    return "Openstaand";
}


}
?>