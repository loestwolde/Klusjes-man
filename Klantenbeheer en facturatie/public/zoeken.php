<?php
include("../src/opzoeken.php");

$zoekKlant = new KlantenZoeken();

$zoekResultaat = []; // je zoekresultaat is nu nog een lege array

if (isset($_POST['zoeken'])) {

$zoekterm = $_POST['zoekterm']; // zoekterm ophalen
$zoekResultaat = $zoekKlant->zoekKlanten($zoekterm); // je geeft de zoekterm mee aan de functie om te zoeken
}

?>

<html>

<head>

<title>Zoeken</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="card">

<a href="index.php" class="btn">Dashboard</a>

<br><br>

<div class="zoeken-form">
<form method="post">

<label>Zoek klant:</label>
<input type="text" name="zoekterm">

<button type="submit" class="btn" name="zoeken">Zoek klant</button>

</form>
</div>

<table>

<tr>
    <th>Aanhef</th>
    <th>Voorletters</th>
    <th>Achternaam</th>
    <th>Woonplaats</th>
    <th>Details</th>
</tr>

<?php

if ($zoekResultaat) { // als er resultaten zijn, loop je door elk resultaat

    foreach ($zoekResultaat as $klant) {

        echo "<tr>";

        echo "<td>" . $klant['aanhef'] . "</td>";
        echo "<td>" . $klant['voorletters'] . "</td>";
        echo "<td>" . $klant['achternaam'] . "</td>";
        echo "<td>" . $klant['woonplaats'] . "</td>";

       $id = $klant['klant_id']; // het id is de klant_id uit de database
       // die geef je mee als je klantInfo wil bekijken
        echo "<td><a class='btn' href='klantInfo.php?id=$id'>Bekijk</a></td>"; 

        echo "</tr>";
    }

}
?>

</table>

</div>

</body>
</html>