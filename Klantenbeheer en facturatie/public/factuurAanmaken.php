<?php
include_once("../src/facturen.php");

$facturen = new Facturen();

// haalKlussenOp laat alleen ongefactureerde klussen zien, want je wil hier juist een factuur aanmaken
$klussen = $facturen->haalKlussenOp();

// kijken of er een klus is geselecteerd, anders null
$geselecteerdeKlusId = $_POST['klus_id'] ?? null;

// standaard is er geen klus geselecteerd
$klus = null;

if ($geselecteerdeKlusId) {

    $klusResultaat = $facturen->haalKlusOp($geselecteerdeKlusId); // je haalt alle gegevens van die klus op met de functie
    $klus = $klusResultaat[0] ?? null; // je wil het eerste resultaat hebben omdat klus id uniek is
}

if (isset($_POST['opslaan']) && $klus) {

    // de uren, het tarief en voorrijtijd uit de database kolommen halen zodat je die in de readonly boxen kan laten zien
    // als je dat niet doet, kan je gewoon de form input value anders invullen en de factuur zo aanpassen
    $uren = $klus['uren'];
    $tarief = $klus['tarief'];
    $voorrijtijd = $klus['voorrijtijd'];

    $werkKosten = $uren * $tarief; // berekenen van de kosten met het uurtarief
    $voorrijtijdKosten = ($voorrijtijd * $tarief) / 2; // en ook de voorrijtijdkosten
    // dit delen door twee omdat je de voorrijtijd in halve uren invult, anders klopt het niet

    // materialen ophalen die gebruikt zijn bij de klus met het klus id
    $materialen = $facturen->haalKlusMaterialenOp($geselecteerdeKlusId);

    $materiaalKosten = 0; // materiaalkosten zijn standaard 0

    foreach ($materialen as $materiaal) {
        // loopt door alle gebruikte materialen en doet het aantal keer de prijs
        $materiaalKosten += $materiaal['aantal'] * $materiaal['prijs'];
    }

    // totalen berekenen
    $subtotaal = $werkKosten + $voorrijtijdKosten + $materiaalKosten;
    $btw = $subtotaal * 0.21;
    $eindbedrag = $subtotaal + $btw;

    // hier ga je de factuur maken met de functie, je geeft alle parameters mee
    $factuur_id = $facturen->maakFactuur($klus['klant_id'], $geselecteerdeKlusId, $uren, $voorrijtijd, $eindbedrag, $klus['datum']);

// factuurregel maken voor factuur details
$facturen->maakFactuurRegel($factuur_id, $klus['omschrijving'], $uren, $tarief);

// 

if($voorrijtijd > 0)
{
    // als je voorrijtijd hebt ingevuld roep je gewoon dezelfde functie aan en zet je dat onder voorrijtijd
    $facturen->maakFactuurRegel($factuur_id, "Voorrijtijd", $voorrijtijd, $tarief / 2);
}


// materialen
foreach ($materialen as $materiaal) {

    $facturen->maakFactuurRegel($factuur_id, $materiaal['naam'], $materiaal['aantal'], $materiaal['prijs']);

}

echo "Factuur opgeslagen!";

}

?>
<html>

<head>

<title>Factuur aanmaken</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="card">

<h2>Factuur maken</h2>

<form method="post">

<label>Klus kiezen:</label>

<select name="klus_id" onchange="this.form.submit()">

<?php foreach ($klussen as $k) { ?>

<option value="<?= $k['klus_id'] // kijkt welke klus het is door klus id ?>" 
<?= ($geselecteerdeKlusId == $k['klus_id']) ? 'selected' : ''
// als je een klus selecteert zorgt dit ervoor dat die klus ook geselecteerd blijft in de dropdown?>>

<?= $k['omschrijving'] ?> - <?= $k['datum'] ?>

</option>

<?php } ?>

</select>


<h3>Kort overzicht van de klus</h3>


<label>Uren gewerkt:</label>

<input type="number" name="uren" value="<?= $klus['uren'] ?? '' ?>" readonly>


<label>Tarief:</label>

<input type="number" step="0.01" name="tarief" value="<?= $klus['tarief'] ?? '' ?>" readonly>


<label>Voorrijtijd (halve uren):</label>

<input type="number" step="0.5" name="voorrijtijd"  value="<?= $klus['voorrijtijd'] ?? '' ?>" readonly>

<br>

<button class="btn" type="submit" name="opslaan">
    Maak factuur
</button>

</form>

<br>

<a class="btn" href="index.php">
    Dashboard
</a>

</div>

</body>

</html>