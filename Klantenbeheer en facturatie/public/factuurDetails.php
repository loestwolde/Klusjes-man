<?php
include_once __DIR__ . "/../src/facturen.php";

$facturen = new Facturen();

// id uit url ophalen omdat je die nodig hebt om te weten over welke factuur het gaat
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<p>Geen factuur geselecteerd.</p>";
    echo "<a href='zoeken.php'>Terug</a>";
    exit;
}

// alle factuur info ophalen voor de goede factuur en de goede klant
$factuurInfo = $facturen->haalFactuurMetKlantOp($id);

if (empty($factuurInfo)) {
    echo "<p>Factuur niet gevonden.</p>";
    echo "<a href='zoeken.php'>Terug</a>";
    exit;
}

// je wil dan het eerste resultaat hebben, want je krijgt een array terug 
$factuurInfo = $factuurInfo[0];

// alle factuurregels van dit factuur ophalen met de functie
$regels = $facturen->haalFactuurregelsOp($id);

// je kunt een factuur als betaald markeren, dan zie je dat ook gelijk op het scherm
if (isset($_POST['betaald'])) {
    $facturen->markeerAlsBetaald($id); // de factuur id wordt doorgestuurd naar database

    // na het opslaan blijf je gewoon op deze pagina
    header("Location: factuurDetails.php?id=" . $id . "&success=1");
    // dit stuk php wordt gestopt
    exit;
}

?>
<html>

<head>

<title>Factuur details</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="card">

<?php 
// staat er succes = 1 in de url, dan krijg je dit te zien
if (isset($_GET['success'])) { ?>
<p style="background:#d4edda; padding:10px; border-radius:8px; color:#155724;">Factuur is gemarkeerd als betaald</p><?php } ?>

<h2>Factuur overzicht</h2>

<div class="factuur-info">

<p>
<strong>Klant:</strong>
<?= $factuurInfo['voorletters'] ?> <?= $factuurInfo['achternaam'] ?>
</p>

<p>
<strong>Factuurdatum:</strong>
<?= $factuurInfo['factuurdatum'] ?>
</p>


<p>
<strong>Vervaldatum:</strong>
<?= $factuurInfo['vervaldatum'] ?>
</p>


<p>
<strong>Uren:</strong>
<?= $factuurInfo['uren'] ?>
</p>


<p>
<strong>Voorrijtijd:</strong>
<?= $factuurInfo['voorrijtijd'] ?> halve uren
</p>

<br><br>

<h2>Factuurdetails</h2>


<?php if (empty($regels)) { ?>

<p>Geen factuurregels gevonden.</p>

<?php } else { ?>


<table>

<tr>
    <th>Omschrijving</th>
    <th>Aantal</th>
    <th>Kosten</th>
    <th>Totaal</th>
</tr>

<?php foreach ($regels as $regel) { ?>

<tr>

<td><?= $regel['omschrijving']?></td>
<td><?= $regel['aantal'] ?></td>
<td>€ <?= $regel['prijs_per_stuk'] ?></td>
<td>€ <?= $regel['totaalprijs'] ?></td>

</tr>

<?php } ?>


</table>

<br>

<?php
$teBetalen = $factuurInfo['totaalbedrag']; // totaalbedrag ophalen
$exclBtw = $teBetalen / 1.21; // dit is het bedrag zonder btw
$btw = $teBetalen - $exclBtw; // totaal met btw berekenen
?>

<p>
<strong>Subtotaal:</strong>
€ <?= number_format($exclBtw, 2) ?>
</p>


<p>
<strong>BTW (21%):</strong>
€ <?= number_format($btw, 2) ?>
</p>

<p>
<strong>Totaalbedrag:</strong>
€ <?= number_format($factuurInfo['totaalbedrag'], 2) ?>
</p>


</div>


<?php } ?>


<form method="post">

<br><br>

<?php if ($factuurInfo['betaald'] == 0) { 
    // als betaald op 0 staat, dan is de factuur nog niet betaald, dus dan zie je deze button wel ?>

<form method="post">
    <br><br>

    <button class="btn" type="submit" name="betaald">Factuur is betaald</button>

</form>

<?php } else { 
    // anders staat er dat de factuur al is betaald?>

<p style="color:green; font-weight:bold;">Deze factuur is al betaald</p>

<?php } ?>

</form>

<a class="btn" href="index.php">Dashboard</a>

</div>

</body>

</html>