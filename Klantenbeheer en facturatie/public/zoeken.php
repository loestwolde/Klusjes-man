<?php
include("../src/opzoeken.php");

$zoekKlant = new KlantenZoeken();

$zoekResultaat = [];

if (isset($_POST['zoeken'])) {

    $zoekterm = $_POST['zoekterm'];

    $zoekResultaat = $zoekKlant->getCustomerOnLastAndFirstName($zoekterm);
}

?>

<html>

<form method="post">

    Zoek klant:
    <input type="text" name="zoekterm">

    <button type="submit" name="zoeken">
        Zoek klant
    </button>

</form>

<br>

<table border="1">

<tr>
    <th>Aanhef</th>
    <th>Voorletters</th>
    <th>Achternaam</th>
    <th>Woonplaats</th>
    <th>Details</th>
</tr>

<?php

if ($zoekResultaat) {

    foreach ($zoekResultaat as $klant) {

        echo "<tr>";

        echo "<td>" . $klant['aanhef'] . "</td>";
        echo "<td>" . $klant['voorletters'] . "</td>";
        echo "<td>" . $klant['achternaam'] . "</td>";
        echo "<td>" . $klant['woonplaats'] . "</td>";

        echo "<td><a href='klantInfo.php?id=" . $klant['klant_id'] . "'> Bekijk</a></td>";

        echo "</tr>";
    }

}
?>

</table>

</html>