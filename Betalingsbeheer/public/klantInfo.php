<?php

include("../src/opvragen.php");
include("../src/facturen.php");

$klanten = new KlantenOpvragen();
$facturenClass = new Facturen();

// klant_id uit URL halen
$id = $_GET['id'];

// klantgegevens ophalen
$resultaten = $klanten->haalKlantInfoOp($id);

// facturen ophalen
$facturen = $facturenClass->haalFacturenOp($id);

?>

<html>

<h2>Klantinformatie</h2>

<table border="1">

<tr>
    <th>Naam</th>
    <th>Adres</th>
    <th>Woonplaats</th>
    <th>Periode</th>
    <th>Status</th>
</tr>

<?php

foreach ($resultaten as $klant) {

    echo "<tr>";

    echo "<td>" . $klant['naam'] . "</td>";
    echo "<td>" . $klant['adres'] . "</td>";
    echo "<td>" . $klant['woonplaats'] . "</td>";
    echo "<td>" . $klant['periode'] . "</td>";
    echo "<td>" . $klant['status'] . "</td>";

    echo "</tr>";
}

?>

</table>

<br>
<br>

<h2>Facturen</h2>

<table border="1">

<tr>
    <th>Factuurdatum</th>
    <th>Totaalbedrag</th>
    <th>Betaald</th>
    <th>Details</th>
</tr>

<?php

foreach ($facturen as $factuur) {

    echo "<tr>";

    echo "<td>" . $factuur['factuurdatum'] . "</td>";

    echo "<td>€ " . $factuur['totaalbedrag'] . "</td>";

    echo "<td>";

    if ($factuur['betaald'] == 1) {
        echo "Ja";
    } else {
        echo "Nee";
    }

    echo "</td>";

    echo "<td>
    <a href='factuurDetails.php?id=" . $factuur['factuur_id'] . "'>
    Bekijk regels
    </a>
    </td>";

    echo "</tr>";
}

?>

</table>

</html>