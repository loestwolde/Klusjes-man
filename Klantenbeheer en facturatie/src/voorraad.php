<?php

include_once 'database.php';


class OverzichtVoorraad extends Database {


// voor het overzicht van alle materialen, gesorteerd op naam
    public function getOverzichtVoorraad()
    { 
        $query = "SELECT *
        FROM artikelen
        ORDER BY naam";

        return parent::voerQueryUit($query);

    }

// je roept deze functie aan om het resultaat van een zoekterm te kunnen laten zien bij de voorraad
public function getZoeken($zoekterm)
    {
        $query = "SELECT *
        FROM artikelen
        -- je kunt op artikel id of op naam zoeken
        WHERE artikel_id LIKE ?
        OR naam LIKE ?
        ORDER BY naam";

        $zoek = "%$zoekterm%";

        return parent::voerQueryUit($query,[$zoek, $zoek]);

    }

// functie die wordt aangeroepen als je materialen invult bij klus aanmaken 
public function verlaagVoorraad($artikel_id, $aantal)
{
    $query=" UPDATE artikelen
    SET voorraad = voorraad - ?
    WHERE artikel_id = ?";

return parent::voerQueryUit($query,[$aantal, $artikel_id]);

}

// je krijgt een melding te zien op de index als de voorraad bijna op is
public function getMelding()
{
    $query = "SELECT *
    FROM artikelen
    WHERE voorraad <= minimum_voorraad + 10";

    return parent::voerQueryUit($query);
}

}

?>