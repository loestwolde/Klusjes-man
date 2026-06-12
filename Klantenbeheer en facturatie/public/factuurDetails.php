<?php

include_once __DIR__ . "/../src/facturen.php";

$factuurClass = new Facturen();

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<p>Geen factuur geselecteerd.</p>";
    echo "<a href='zoeken.php'>Terug</a>";
    exit;
}

$factuurInfo = $factuurClass->haalFactuurOp($id);

if (empty($factuurInfo)) {
    echo "<p>Factuur niet gevonden.</p>";
    echo "<a href='zoeken.php'>Terug</a>";
    exit;
}

$factuurInfo = $factuurInfo[0];

$regels = $factuurClass->haalFactuurregelsOp($id);

?>

<h2>Factuurdetails</h2>

<?php if (empty($regels)) { ?>

    <p>Geen factuurregels gevonden.</p>

<?php } else { ?>

<table border="1">

<tr>
    <th>Omschrijving</th>
    <th>Aantal uur</th>
    <th>Prijs</th>
    <th>Totaal</th>
</tr>

<?php foreach ($regels as $regel) { ?>

<tr>
    <td><?= $regel['omschrijving'] ?></td>
    <td><?= $regel['aantal'] ?></td>
    <td>€ <?= $regel['prijs_per_stuk'] ?></td>
    <td>€ <?= $regel['totaalprijs'] ?></td>
</tr>

<?php } ?>

</table>

<h2>Factuur overzicht</h2>

<p><strong>Factuurdatum:</strong> <?= $factuurInfo['factuurdatum'] ?></p>
<p><strong>Vervaldatum:</strong> <?= $factuurInfo['vervaldatum'] ?></p>
<p><strong>Uren:</strong> <?= $factuurInfo['uren'] ?></p>
<p><strong>Voorrijtijd:</strong> <?= $factuurInfo['voorrijtijd'] ?></p>
<p><strong>Totaalbedrag:</strong> € <?= number_format($factuurInfo['totaalbedrag'], 2) ?></p>

<?php
$teBetalen = $factuurInfo['totaalbedrag'];
$exclBtw = $teBetalen / 1.21;
$btw = $teBetalen - $exclBtw;
?>

<p><strong>Subtotaal:</strong> € <?= number_format($exclBtw, 2) ?></p>
<p><strong>BTW (21%):</strong> € <?= number_format($btw, 2) ?></p>

<?php } ?>

<br><br>
<a href="zoeken.php">Terug</a>