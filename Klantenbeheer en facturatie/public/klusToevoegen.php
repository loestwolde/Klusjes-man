<?php
include("../src/klussen.php");
include("../src/facturen.php");
include("../src/opvragen.php");
include("../src/voorraad.php");

$bestaandeKlanten = new KlantenOpvragen();
$klussen = new Klussen();
$facturen = new Facturen();
$voorraad = new OverzichtVoorraad();

$werkzaamheden = $facturen->haalWerkzaamhedenOp(); // werkzaamheden ophalen zodat je daar uit kan kiezen
$klanten = $bestaandeKlanten->haalKlantenOp(); // klanten ophalen zodat je ook een klant kunt selecteren
$materialen = $facturen->haalMaterialenOp(); // dit is hetzelfde idee maar dan voor de materialen

$gekozenTarief = 0; // standaard tarief is 0

if (!empty($_POST['werkzaamheid_id'])) {
    // pas als iemand een soort klus heeft gekozen haal je het uurtarief van die werkzaamheid op

    foreach ($werkzaamheden as $werkzaamheid) {
        if ($werkzaamheid['werkzaamheid_id'] == $_POST['werkzaamheid_id']) {
            $gekozenTarief = $werkzaamheid['prijs_per_stuk']; // en dan wordt het tarief die van die werkzaamheid
        }
    }
}


if (isset($_POST['opslaan'])) {

// een klus opslaan, de functie geeft het nieuwe klus id terug
$klus_id = $klussen->voegKlusToe( $_POST['klant_id'], $_POST['werkzaamheid_id'], $_POST['omschrijving'], $_POST['datum'],
$_POST['uren'], $gekozenTarief, $_POST['voorrijtijd'], $_POST['notities']);

// kijken of er materialen zijn gekozen
if(isset($_POST['artikel_id']))
{

// loopt door alle gekozen materialen
foreach($_POST['artikel_id'] as $a => $artikel_id)
{

// je krijgt daar een array van terug en het aantal van elk gekozen materiaal sla je op
$aantal = $_POST['materiaal_aantal'][$a];

// als je een materiaal hebt geselecteerd en een aantal hebt ingevuld wordt het opgeslagen
if($artikel_id != "" && $aantal > 0)
{

// materiaal toevoegen onder de goede klus id 
$facturen->voegKlusMateriaalToe($klus_id, $artikel_id, $aantal);

// voorraad van die materialen verlagen
$voorraad->verlaagVoorraad($artikel_id, $aantal);

}

}

}


echo "Klus opgeslagen!";

}

?>

<html>

<head>

<title>Klus toevoegen</title>
<link rel="stylesheet" href="style.css">

</head>


<body>

<div class="card">

    <h2>Nieuwe klus toevoegen</h2>

    <form method="post">

    <label>Werkzaamheid:</label>
    <select name="werkzaamheid_id" onchange="this.form.submit()">

    <?php foreach ($werkzaamheden as $werkzaamheid) { ?>

        <option value="<?= $werkzaamheid['werkzaamheid_id'] ?>"

        <?php
        // zo blijft de gekozen werkzaamheid geselecteerd
        if (isset($_POST['werkzaamheid_id']) && $_POST['werkzaamheid_id'] == $werkzaamheid['werkzaamheid_id']) {
            echo 'selected';
            }
        ?>

        >

        <?= $werkzaamheid['omschrijving'] // je ziet de soort klus in de dropdown ?> 
        (€ <?= $werkzaamheid['prijs_per_stuk'] // daarachter zie je ook nog het uurtarief?>)

        </option>

    <?php } ?>

    </select>


    <label>Klant:</label>
    <select name="klant_id">

    <?php foreach ($klanten as $klant) { ?>

        <option value="<?= $klant['klant_id'] ?>">

            <?= $klant['voorletters'] ?> <?= $klant['achternaam'] ?>

        </option>

    <?php } ?>

    </select>


    <label>Omschrijving:</label>
    <input type="text" name="omschrijving">


    <label>Datum:</label>
    <input type="date" name="datum">


    <label>Voorrijtijd (halve uren):</label>
    <input type="number" name="voorrijtijd" min="0" step="0.5">


    <label>Uren:</label>
    <input type="number" name="uren" min="1">


    <label>Tarief:</label>
    <input type="number" name="tarief" step="0.01" value="<?= $gekozenTarief ?>" readonly>


    <label>Notities:</label>
    <textarea name="notities"></textarea>


    <label>Gebruikte materialen:</label>

<?php for($i = 0; $i < 3; $i++) { 
    // je kunt drie materialen toevoegen als je die hebt moeten bijbestellen ?>

<div>

<select name="artikel_id[]">

<option value="">Geen materiaal</option>

<?php foreach($materialen as $materiaal){ ?>

<option value="<?= $materiaal['artikel_id'] ?>">

<?= $materiaal['naam'] // naam materiaal tonen ?>
(€ <?= $materiaal['prijs'] // en ook de prijs per stuk ?>)</option>

<?php } ?>

</select>


<input type="number" name="materiaal_aantal[]" min="0" placeholder="Aantal">

</div>

<br>

<?php } ?>


    <button class="btn" type="submit" name="opslaan">Opslaan</button>

    </form>

    <br>

    <div class="nav-buttons">

        <a class="btn" href="factuurAanmaken.php">Factuur maken</a>

        <a class="btn" href="index.php">Dashboard</a>

    </div>


</div>


</body>

</html>