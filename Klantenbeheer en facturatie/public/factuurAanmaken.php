<?php

include_once("../src/facturen.php");

$facturen = new Facturen();

$klussen = $facturen->haalKlussenOp();

$selectedKlusId = $_POST['klus_id'] ?? null;

$klus = null;
$uren = null;
$tarief = null;
$voorrijtijd = null;

if ($selectedKlusId) {

    $klusResultaat = $facturen->haalKlusOp($selectedKlusId);
    $klus = $klusResultaat[0] ?? null;

    if ($klus) {
        $uren = $klus['uren'];
        $tarief = $klus['tarief'];
        $voorrijtijd = $klus['voorrijtijd'];
    }
}

if (isset($_POST['opslaan']) && $klus) {

    // waarden uit form (mogen aangepast zijn)
    $uren = $_POST['uren'];
    $tarief = $_POST['tarief'];
    $voorrijtijd = $_POST['voorrijtijd'];

    // werk + voorrijtijd
    $werkKosten = $uren * $tarief;
    $voorrijtijdKosten = $voorrijtijd * $tarief;

    // materialen
    $materialen = $facturen->haalKlusMaterialenOp($selectedKlusId);

    $materiaalKosten = 0;

    foreach ($materialen as $materiaal) {
        $materiaalKosten += $materiaal['aantal'] * $materiaal['prijs'];
    }

    // totaal
    $subtotaal = $werkKosten + $voorrijtijdKosten + $materiaalKosten;
    $btw = $subtotaal * 0.21;
    $eindbedrag = $subtotaal + $btw;

    // factuur maken
    $factuur_id = $facturen->maakFactuur(
    $klus['klant_id'],
    $klus['uren'],
    $klus['voorrijtijd'],
    $eindbedrag
);

    // werkregel
    $facturen->maakFactuurRegel(
        $factuur_id,
        $klus['omschrijving'],
        $uren,
        $tarief
    );

    // materialen
    foreach ($materialen as $materiaal) {
        $facturen->maakFactuurRegel(
            $factuur_id,
            $materiaal['naam'],
            $materiaal['aantal'],
            $materiaal['prijs']
        );
    }

    echo "Factuur opgeslagen!";
}

?>

<h2>Factuur maken</h2>

<form method="post">

<select name="klus_id" onchange="this.form.submit()">

<?php foreach ($klussen as $k) { ?>
    <option value="<?= $k['klus_id'] ?>"
        <?= ($selectedKlusId == $k['klus_id']) ? 'selected' : '' ?>>
        <?= $k['omschrijving'] ?> - <?= $k['datum'] ?>
    </option>
<?php } ?>

</select>

<br><br>
<h3>Kort overzicht van de klus:</h3>

Uren gewerkt:
<input type="number" name="uren" value="<?= $uren ?>">

<br><br>

Tarief:
<input type="number" step="0.01" name="tarief" value="<?= $tarief ?>">

<br><br>

Voorrijtijd (in halve uren):
<input type="number" name="voorrijtijd" value="<?= $voorrijtijd ?>">

<br><br>

<button type="submit" name="opslaan">Maak factuur</button>

</form>

<br>
<a href="zoeken.php">Terug</a>