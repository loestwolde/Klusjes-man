<?php

include("../src/klussen.php");
include("../src/facturen.php");
include("../src/opvragen.php");

$bestaandeKlanten = new KlantenOpvragen();
$klussen = new Klussen();
$facturen = new Facturen();

$werkzaamheden = $facturen->haalWerkzaamhedenOp();
$klanten = $bestaandeKlanten->haalKlantenOp();

$gekozenTarief = "";

if(isset($_POST['tariefOphalen'])){

    $werkzaamheid_id = $_POST['werkzaamheid_id'];

    foreach($werkzaamheden as $werkzaamheid){

        if($werkzaamheid['werkzaamheid_id'] == $werkzaamheid_id){

            $gekozenTarief = $werkzaamheid['prijs_per_stuk'];

        }

    }

}

if (isset($_POST['opslaan'])) {

    $klussen->voegKlusToe(
    $_POST['klant_id'],
    $_POST['werkzaamheid_id'],
    $_POST['omschrijving'],
    $_POST['datum'],
    $_POST['uren'],
    $_POST['tarief'],
    $_POST['voorrijtijd'],
    $_POST['notities']
);

    echo "Klus opgeslagen!";
}
?>

<h2>Nieuwe klus toevoegen</h2>

<form method="post">

Werkzaamheid:
<select name="werkzaamheid_id">
<?php foreach ($werkzaamheden as $werkzaamheid) { ?>
    <option 
value="<?= $werkzaamheid['werkzaamheid_id'] ?>"
data-prijs="<?= $werkzaamheid['prijs_per_stuk'] ?>">

<?= $werkzaamheid['omschrijving'] ?>

(€ <?= $werkzaamheid['prijs_per_stuk'] ?>)

</option>
<?php } ?>
</select>

<button type="submit" name="tariefOphalen">
Tarief ophalen
</button>

<br><br>

Klant:
<select name="klant_id">
<?php foreach ($klanten as $klant) { ?>
    <option value="<?= $klant['klant_id'] ?>">
        <?= $klant['voorletters'] ?> <?= $klant['achternaam'] ?>
    </option>
<?php } ?>
</select>

<br><br>

Omschrijving:
<input type="text" name="omschrijving">

<br><br>

Datum:
<input type="date" name="datum">

<br><br>

Voorrijtijd (in halve uren):
<input type="number" name="voorrijtijd" min="0" step="0.5">

<br><br>

Uren:
<input type="number" name="uren" min="1">

<br><br>

Tarief:

<input 
type="number"
name="tarief"
step="0.01"
value="<?= $gekozenTarief ?>"
readonly>

<br><br>

Notities:
<textarea name="notities"></textarea>

<br><br>

<button type="submit" name="opslaan">Opslaan</button>

</form>

<br>
<a href="factuurAanmaken.php">Factuur maken</a>