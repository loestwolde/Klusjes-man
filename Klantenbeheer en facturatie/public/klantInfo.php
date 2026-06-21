<?php
include("../src/opvragen.php");
include("../src/facturen.php");
include("../src/klussen.php");

$klanten = new KlantenOpvragen();
$facturen = new Facturen();
$klussen = new Klussen();

// klant id uit url ophalen zodat je de goede gegevens ophaalt
$id = $_GET['id'] ?? null;

if($id === null){
    echo "Geen klant geselecteerd.";
    exit;
}

// klantgegevens ophalen van de goede klant, id geef je mee als parameter
$resultaten = $klanten->haalKlantInfoOp($id);

// facturen ophalen van deze klant
$alleFacturen = $facturen->haalFacturenOp($id); // alle facturen van de klant ophalen
$alleKlussen = $klussen->haalKlussenVanKlantOp($id); // alle klussen van de klant ophalen

?>

<html>

<head>
<title>Klantinformatie</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>Klantinformatie</h1>
</header>

<main>

<div class="card">

<h2>Klantgegevens</h2>

<table>

<tr>
<th>Naam</th>
<th>Adres</th>
<th>Woonplaats</th>
<th>Periode</th>
<th>Status</th>
<th>Telefoon</th>
<th>Email</th>
</tr>

<?php foreach($resultaten as $klant){ ?>

<tr>
<td><?= $klant['naam'] ?></td>
<td><?= $klant['adres'] ?></td>
<td><?= $klant['woonplaats'] ?></td>
<td><?= $klant['periode'] ?></td>
<td><?= $klant['status'] ?></td>
<td><?= $klant['telefoon'] ?></td>
<td><?= $klant['email'] ?></td>
</tr>

<?php } ?>

</table>

</div>


<div class="card">

<h2>Facturen</h2>

<table>

<tr>
    <th>Factuurdatum</th>
    <th>Totaalbedrag</th>
    <th>Betaalstatus</th>
    <th>Details</th>
</tr>

<?php if (empty($alleFacturen)) { ?>

<tr>
    <td colspan="4">Geen facturen gevonden.</td>
</tr>

<?php } else { ?>

<?php foreach ($alleFacturen as $factuur) { ?>

<tr>

<td><?= $factuur['factuurdatum'] ?></td>

<td>€ <?= number_format($factuur['totaalbedrag'], 2) ?></td>

<td><?= $facturen->getStatus($factuur) ?></td>

<td>
    <a class="btn" href="factuurDetails.php?id=<?= $factuur['factuur_id'] ?>">Bekijk factuur</a>
</td>

</tr>

<?php } ?>

<?php } ?>

</table>

</div>

<br>

<div class="card">

<h2>Klussen</h2>

<table>

<tr>
<th>Datum</th>
<th>Omschrijving</th>
<th>Uren</th>
<th>Notities</th>
</tr>


<?php if(empty($alleKlussen)){ ?>

<tr>
<td colspan="4">Geen klussen gevonden.</td>
</tr>

<?php } else { ?>

<?php foreach($alleKlussen as $klus){ ?>

<tr>

<td><?= $klus['datum'] ?></td>

<td><?= $klus['omschrijving'] ?></td>

<td><?= $klus['uren'] ?></td>

<td>

<?php if(!empty($klus['notities'])){
    echo $klus['notities'];
}
else{
    echo "Geen notities";
}
?>

</td>

</tr>

<?php } ?>

<?php } ?>


</table>

</div>

<div class="card">

<div class="card">

<div class="nav-buttons">

<a class="btn" href="index.php">Dashboard</a>

<a class="btn" href="zoeken.php">Terug naar zoeken</a>

<a class="btn" href="adresWijzigen.php?id=<?= $id ?>">Adres wijzigen</a>

<a class="btn" href="verhuizen.php?id=<?= $id ?>">Verhuizing doorgeven</a>

<a class="btn" href="klusToevoegen.php">Nieuwe klus toevoegen</a>


</div>

</div>

</main>

</body>

</html>