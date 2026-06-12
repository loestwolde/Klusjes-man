<?php

include_once 'database.php';

class Klussen extends Database {

    public function haalKlussenOp()
    {
        $query = "
            SELECT *
            FROM klussen
            ORDER BY datum DESC
        ";

        return parent::voerQueryUit($query);
    }

   public function voegKlusToe(
    $klant_id,
    $werkzaamheid_id,
    $omschrijving,
    $datum,
    $uren,
    $tarief,
    $voorrijtijd,
    $notities
)
{

$query = "
INSERT INTO klussen
(klant_id, werkzaamheid_id, omschrijving, datum, uren, tarief, voorrijtijd, notities)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";


return parent::voerQueryUit(
    $query,
    [
        $klant_id,
        $werkzaamheid_id,
        $omschrijving,
        $datum,
        $uren,
        $tarief,
        $voorrijtijd,
        $notities
    ]
);

}

}