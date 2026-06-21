<?php
include("../src/klanten.php");

$klanten = new Klanten();

if (isset($_POST['opslaan'])) {

    // eerst de voeg klant toe functie aanroepen om deze gegevens mee te geven
    $klantId = $klanten->voegKlantToe($_POST['voorletters'], $_POST['achternaam'], $_POST['aanhef'], $_POST['email'], $_POST['telefoon']);

    // dan de adresgegevens meegeven
    $adresId = $klanten->voegAdresToe($_POST['straat'], $_POST['huisnummer'], $_POST['postcode'], $_POST['woonplaats']);

    // een nieuwe klantadres regel maken in de koppeltabel
    // als er geen startdatum is ingevuld gebruikt hij de datum van vandaag gewoon
    $klanten->koppelKlantAdres($klantId, $adresId, $_POST['startdatum'] ?? date('Y-m-d'));

    echo "Klant toegevoegd!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Klant toevoegen</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="card">

<h2>Nieuwe klant</h2>

<form method="post">

    <h3>Klantgegevens</h3>

    Aanhef:
    <input name="aanhef" required>

    Voorletters:
    <input name="voorletters" required>

    Achternaam:
    <input name="achternaam" required>

    Email:
    <input name="email" required>

    Telefoon:
    <input name="telefoon" required>

    <h3>Adres</h3>

    Straat:
    <input name="straat" required>

    Huisnummer:
    <input name="huisnummer" required>

    Postcode:
    <input name="postcode" required>

    Woonplaats:
    <input name="woonplaats" required>

    Startdatum:
    <input type="date" name="startdatum">

    <br><br>

    <button type="submit" name="opslaan" class="btn">Opslaan</button>

</form>

<br>

<a href="index.php" class="btn">Dashboard</a>

</div>

</body>
</html>