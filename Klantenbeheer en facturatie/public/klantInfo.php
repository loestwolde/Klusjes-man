<?php

include("../src/opvragen.php");
include("../src/facturen.php");


$klanten = new KlantenOpvragen();
$facturenClass = new Facturen();


$id = $_GET['id'] ?? null;


if($id === null){
    echo "Geen klant geselecteerd.";
    exit;
}



// klantgegevens ophalen

$resultaten =
    $klanten->haalKlantInfoOp($id);



// facturen ophalen

$facturen =
    $facturenClass->haalFacturenOp($id);


?>


<html>

<head>

<title>Klantinformatie</title>

</head>


<body>



<h2>Klantinformatie</h2>


<table border="1">


<tr>

<th>Naam</th>
<th>Adres</th>
<th>Woonplaats</th>
<th>Periode</th>
<th>Status</th>

</tr>



<?php foreach($resultaten as $klant){ ?>


<tr>


<td>
<?= $klant['naam'] ?>
</td>


<td>
<?= $klant['adres'] ?>
</td>


<td>
<?= $klant['woonplaats'] ?>
</td>


<td>
<?= $klant['periode'] ?>
</td>


<td>
<?= $klant['status'] ?>
</td>



</tr>


<?php } ?>


</table>



<br><br>



<h2>Facturen</h2>

<table border="1">

<tr>
    <th>Factuurdatum</th>
    <th>Totaalbedrag</th>
    <th>Betaalstatus</th>
    <th>Details</th>
</tr>

<?php if (empty($facturen)) { ?>

<tr>
    <td colspan="4">Geen facturen gevonden.</td>
</tr>

<?php } else { ?>

<?php foreach ($facturen as $factuur) { ?>

<tr>

    <td>
        <?= $factuur['factuurdatum'] ?>
    </td>

    <td>
        € <?= number_format($factuur['totaalbedrag'], 2) ?>
    </td>

    <td>
    <?= $facturenClass->getStatus($factuur) ?>
</td>

    <td>
        <a href="factuurDetails.php?id=<?= $factuur['factuur_id'] ?>">
            Bekijk factuur
        </a>
    </td>

</tr>

<?php } ?>

<?php } ?>

</table>

<br><br>
<a href="klusToevoegen.php">Nieuwe klus toevoegen?</a>



</body>

</html>