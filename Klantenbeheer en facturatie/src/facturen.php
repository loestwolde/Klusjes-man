<?php
include_once 'database.php';

class Facturen extends Database {

 // standaard factuurdatum is vandaag, vervaldatum is na 30 dagen
 // maakt een nieuwe factuur aan, krijgt alle gegevens van de klus mee
 // na het opslaan geeft hij een nieuwe factuur id terug met laatsteInsertId
public function maakFactuur($klant_id, $klus_id, $uren, $voorrijtijd, $totaal, $datum)
{
    $query = "INSERT INTO factuur
(klant_id,klus_id,uren,voorrijtijd,totaalbedrag,betaald,factuurdatum,vervaldatum)
VALUES (?, ?, ?, ?, ?, 0, ?, DATE_ADD(?, INTERVAL 30 DAY))";

    parent::voerQueryUit($query, [$klant_id, $klus_id, $uren, $voorrijtijd, $totaal, $datum, $datum]);

    return $this->laatsteInsertId();
}

// factuurregel maken, dit heb je nodig om factuurdetails te kunnen laten zien
// rekent automatisch het totaal uit en slaat dit ook op
public function maakFactuurRegel($factuur_id, $omschrijving, $aantal, $prijs)
{
    $totaal = $aantal * $prijs;

    $query = "INSERT INTO factuurregels 
    (factuur_id, omschrijving, aantal, prijs_per_stuk, totaalprijs)
    VALUES (?, ?, ?, ?, ?)";

    return parent::voerQueryUit($query, [$factuur_id, $omschrijving, $aantal, $prijs, $totaal]);
}

// functie om de factuurregels op te halen, nodig voor factuurDetails
public function haalFactuurregelsOp($factuur_id)
    {
        $query = "SELECT *
        FROM factuurregels
        WHERE factuur_id = ?";

    return parent::voerQueryUit($query, [$factuur_id]);

}

// alle facturen van één klant ophalen, nodig voor klantInfo
public function haalFacturenOp($klant_id)
    {
        $query = "SELECT *
            FROM factuur
            WHERE klant_id = ?
            ORDER BY factuurdatum DESC";

        return parent::voerQueryUit($query, [$klant_id]);
}

// haalt één factuur op met klantgegeven, zonder de join kun je de naam van de klant niet weergeven
// wordt gebruikt bij factuurDetails
public function haalFactuurMetKlantOp($factuur_id)
{
    $query = " SELECT factuur.*, klanten.voorletters, klanten.achternaam
    FROM factuur
    JOIN klanten 
    ON factuur.klant_id = klanten.klant_id
    WHERE factuur.factuur_id = ?";

    return parent::voerQueryUit($query, [$factuur_id]);
}

// alleen openstaande facturen ophalen
// dus alleen where betaald = 0, dus als je nog niet betaald hebt
public function haalOpenstaandeFacturenOp()
{
    $query = " SELECT factuur.*, klanten.voorletters, klanten.achternaam
        FROM factuur
        JOIN klanten 
        ON factuur.klant_id = klanten.klant_id
        WHERE factuur.betaald = 0
        ORDER BY factuur.vervaldatum ASC";

    return parent::voerQueryUit($query);
}

// klussen ophalen die nog geen factuur hebben
// door de LEFT JOIN zie je alleen de ongefactureerde klussen
// dus waar ook nog geen klus_id van is bij de tabel factuur
public function haalKlussenOp()
{ 
    $query="SELECT klussen.*
        FROM klussen
        LEFT JOIN factuur 
        ON klussen.klus_id = factuur.klus_id
       WHERE factuur.klus_id IS NULL
       ORDER BY datum DESC";

    return parent::voerQueryUit($query);
}

// methode om een klus op te halen van een specifieke klus
public function haalKlusOp($klus_id)
{
    $query = "SELECT *
              FROM klussen
              WHERE klus_id = ?";

    return parent::voerQueryUit($query, [$klus_id]);
}

// alle soorten werkzaamheden ophalen zodat je daar uit kan kiezen bij het aanmaken van een klus  
public function haalWerkzaamhedenOp()
{
    $query = "SELECT * FROM werkzaamheden
        ORDER BY omschrijving";

    return parent::voerQueryUit($query);
}

// functie die gewoon alle materialen ophaalt
public function haalMaterialenOp()
{
$query=" SELECT *
FROM artikelen
ORDER BY naam";

return parent::voerQueryUit($query);

}

// alle materialen ophalen die bij een bepaalde klus horen, vandaar klus_id als parameter
// door de join zie je ook de naam en de prijs van het materiaal
public function haalKlusMaterialenOp($klus_id)
{
    $query = "SELECT artikelen.artikel_id, artikelen.naam, artikelen.prijs, klus_materialen.aantal
    FROM klus_materialen
    JOIN artikelen 
    ON artikelen.artikel_id = klus_materialen.artikel_id
    WHERE klus_materialen.klus_id = ?";

    return parent::voerQueryUit($query, [$klus_id]);
}

// dit koppelt materiaal aan een klus, dit heb je nodig als je dat invult bij het aanmaken van een klus
// dan wordt er onder de goede klus opgeslagen welke materialen er zijn bijbesteld
public function voegKlusMateriaalToe($klus_id, $artikel_id, $aantal)
{

$query="INSERT INTO klus_materialen (klus_id, artikel_id, aantal)
VALUES (?,?,?)";

return parent::voerQueryUit($query,[$klus_id, $artikel_id, $aantal]);
}

// kijkt naar wat de status is van de factuur in de database
// als het betaald is, staat er een 1 bij de kolom betaald, een 0 als het openstaand is
// als de vervaldatum al voorbij is, dan staat er te laat
public function getStatus($factuur)
{
    if ($factuur['betaald'] == 1) {
        return "Betaald";
    }

    // de vervaldatum heb je al aangemaakt bij de maakFactuur functie
    if (strtotime($factuur['vervaldatum']) < time()) { // time() vergelijkt het met nu
        return "Te laat";
    }

    return "Openstaand";
}

// een factuur kunnen markeren als betaald, je stuurt de factuur_id mee als parameter
// dan wordt de kolom betaald gewijzigd naar 1, dus dan is het betaald
// dat kan alleen als het nog op onbetaald staat
public function markeerAlsBetaald($factuur_id)
{
    $query = "UPDATE factuur
            SET betaald = 1
            WHERE factuur_id = ? AND betaald = 0";

    return parent::voerQueryUit($query, [$factuur_id]);
}





public function haalArtikelenOp()
{
    $query = "SELECT * FROM artikelen
            ORDER BY naam";

    return parent::voerQueryUit($query);
}

}
?>