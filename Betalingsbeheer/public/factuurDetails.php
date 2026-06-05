<?php

include_once __DIR__ . "/../src/facturen.php";

$factuur = new Facturen();

$id = $_GET['id'] ?? null;

if ($id === null) {
    echo "Geen factuur geselecteerd.";
    exit;
}


$regels = $factuur->haalFactuurregelsOp($id);

?>

<html>

<h2>Factuurregels</h2>

<?php if (empty($regels)) { ?>
    <p>Geen regels gevonden voor deze factuur.</p>
<?php } else { ?>

<table border="1">

<tr>
    <th>Omschrijving</th>
    <th>Aantal</th>
    <th>Prijs per stuk</th>
    <th>Totaalprijs</th>
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

<?php } ?>

</html>