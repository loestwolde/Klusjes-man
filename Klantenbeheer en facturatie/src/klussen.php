<?php

include_once 'database.php';

class Klussen extends Database {

// functie om een nieuwe klus toe te voegen aan de database
public function voegKlusToe($klant_id, $werkzaamheid_id, $omschrijving, $datum, $uren, $tarief, $voorrijtijd, $notities)
{
$query=" INSERT INTO klussen (klant_id, werkzaamheid_id, omschrijving, datum, uren, tarief, voorrijtijd, notities)

VALUES (?,?,?,?,?,?,?,?)";

parent::voerQueryUit($query, [$klant_id, $werkzaamheid_id, $omschrijving, $datum, $uren, $tarief, $voorrijtijd, $notities]);

// geeft de klus ook gelijk een nieuw id mee
return $this->laatsteInsertId();

}

// haalt alle klussen van een klant op zodat je deze kunt bekijken
public function haalKlussenVanKlantOp($klant_id)
{
    $query = "SELECT *
    FROM klussen
    WHERE klant_id = ?
    ORDER BY datum DESC";

    return parent::voerQueryUit($query, [$klant_id]);
}

}