<?php
include('../src/voorraad.php');

$voorraad = new OverzichtVoorraad();

$resultaat = $voorraad->getOverzichtVoorraad();

$zoekResultaat = []; // je krijgt het zoekresultaat als een array terug
$heeftZoek = false; // standaard heb je geen zoekopdracht 

// als je op de zoeken button klikt
if (isset($_POST['zoeken'])) {

    $heeftZoek = true;
    $zoekterm = $_POST['zoekterm']; // zoekterm invoer ophalen
    $zoekResultaat = $voorraad->getZoeken($zoekterm); // en meegeven aan de functie om een resultaat te krijgen
}

?>

<html>

<head>

<title>Voorraadbeheer</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="card">
<h2>Voorraadbeheer</h2>

<form method="post">

<input type="text" name="zoekterm" placeholder="Zoek artikel">

<button class="btn" type="submit" name="zoeken">Zoeken</button>


</form>

<br>

<table>

<tr>

<th>Artikel ID</th>
<th>Naam</th>
<th>Voorraad</th>
<th>Minimum voorraad</th>
<th>Prijs</th>
<th>Status</th>

</tr>


<?php

// verkorte if statement
// als je hebt gezocht, dan krijg je het zoekresultaat van dat gevonden materiaal terug
// heb je niet gezocht, dan krijg je gewoon alles te zien
$gegevens = $heeftZoek ? $zoekResultaat : $resultaat;

// als er iets gevonden is in de array, laat je alle gegevens van de materialen zien in een tabel
if (!empty($gegevens)) {

foreach ($gegevens as $g) {


echo "<td>" . $g['artikel_id'] . "</td>";

echo "<td>" . $g['naam'] . "</td>";

echo "<td>" . $g['voorraad'] . "</td>";

echo "<td>" . $g['minimum_voorraad'] . "</td>";

echo "<td>€ " . $g['prijs'] . "</td>";

// als de voorraad lager is dan de minimum, dan zie je bij status dat je de voorraad moet aanvullen
if ($g['voorraad'] <= $g['minimum_voorraad']) {

    echo "<td>Voorraad aanvullen!</td>";

} else {
    echo "<td>OK</td>";
}

echo "</tr>";

}

}

?>

</table>


<br>

<a class="btn" href="index.php">Dashboard</a>

</div>

</body>
</html>