<?php
include("../src/klussen.php");
include("../src/opvragen.php");
include("../src/facturen.php");
include("../src/voorraad.php");


$facturen = new Facturen();
$voorraad = new OverzichtVoorraad();

$klussen = $facturen->haalKlussenOp();
$meldingen = $voorraad->getMelding();
$openstaandeFacturen = $facturen->haalOpenstaandeFacturenOp();

?>

<html>

<head>

<title>Dashboard</title>
<link rel="stylesheet" href="style.css">

</head>


<body>

<header>

<h1>Klusjesman Dashboard</h1>

</header>

<main>
<br>
<?php if (!empty($meldingen)) { ?>

<div class="card melding">

    <div class="melding-text">

        <strong>Voorraad waarschuwing: </strong>

        Er zijn <?= count($meldingen) ?> producten die bijna op minimum voorraad zitten.

    </div>

</div>

<?php } ?>

<div class="card">

<h2 class="navigatie-titel">Navigatie</h2>
<div class="nav-buttons">

<a href="zoeken.php" class="btn">Klanten zoeken</a>

<a href="klantToevoegen.php" class="btn">Nieuwe klant</a>

<a href="klusToevoegen.php" class="btn">Nieuwe klus</a>

<a href="factuurAanmaken.php" class="btn">Factuur maken</a>

<a href="voorraadOverzicht.php" class="btn">Voorraadbeheer</a>

</div>

</div>


<div class="card">

<h2>Openstaande facturen</h2>

<table>

<tr>
    <th>Factuur ID</th>
    <th>Naam klant</th>
    <th>Datum</th>
    <th>Bedrag</th>
    <th>Status</th>
    <th>Details factuur</th>
</tr>

<?php foreach($openstaandeFacturen as $factuur){ 
// loopt eerst door alle openstaande facturen heen, je ziet dus alleen onbetaalde facturen hier ?>

<tr>

<td><?= $factuur['factuur_id'] ?></td>

<td><?= $factuur['voorletters'] ?> <?= $factuur['achternaam'] ?></td>

<td><?= $factuur['factuurdatum'] ?></td>

<td>€ <?= number_format($factuur['totaalbedrag'], 2) ?></td>

<td><?= $facturen->getStatus($factuur) 
// kijken wat de status van elke factuur is: te laat of openstaand ?></td>

<td>
<a class="btn" href="factuurDetails.php?id=<?= $factuur['factuur_id'] ?>">bekijken</a>
</td>

</tr>

<?php } ?>

</table>
</main>
</body>
</html>