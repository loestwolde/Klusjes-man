<?php

session_start();

include_once("../src/facturen.php");

$facturen = new Facturen();

$werkzaamheden = $facturen->haalWerkzaamhedenOp();
$artikelen = $facturen->haalArtikelenOp();

if (!isset($_SESSION['materialen'])) {
    $_SESSION['materialen'] = [];
}


if (isset($_POST['nieuwe_factuur'])) {

    unset($_SESSION['materialen']);

    header("Location: factuurAanmaken.php");
    exit;
}

/*
----------------------------------
MATERIAAL TOEVOEGEN
----------------------------------
*/
if (isset($_POST['toevoegen'])) {

    $artikel_id = $_POST['artikel_id'];
    $aantal = $_POST['aantal'];

    foreach ($artikelen as $artikel) {

        if ($artikel['artikel_id'] == $artikel_id) {

            $_SESSION['materialen'][] = [

                'naam' => $artikel['naam'],
                'prijs' => $artikel['prijs'],
                'aantal' => $aantal,
                'voorraad' => $artikel['voorraad']

            ];
        }
    }
}

$werk = 0;
$materiaalKosten = 0;
$btw = 0;
$totaal = 0;

/*
----------------------------------
BEREKENEN
----------------------------------
*/
if (isset($_POST['bereken'])) {

    $werkzaamheid_id = $_POST['werkzaamheid_id'];
    $uren = $_POST['uren'];

    foreach ($werkzaamheden as $werkzaamheid) {

        if ($werkzaamheid['werkzaamheid_id'] == $werkzaamheid_id) {

            $werk =
                $uren *
                $werkzaamheid['prijs_per_stuk'];
        }
    }

    foreach ($_SESSION['materialen'] as $materiaal) {

        $materiaalKosten +=
            $materiaal['aantal']
            * $materiaal['prijs'];
    }

    $subtotaal = $werk + $materiaalKosten;

    $btw = $subtotaal * 0.21;

    $totaal = $subtotaal + $btw;
}

?>

<html>

<head>
    <title>Factuur aanmaken</title>
</head>

<body>

<h2>Factuur aanmaken</h2>

<form method="post">

    <h3>Werkzaamheid</h3>

    <select name="werkzaamheid_id">

        <?php foreach ($werkzaamheden as $werkzaamheid) { ?>

            <option value="<?= $werkzaamheid['werkzaamheid_id'] ?>">

                <?= $werkzaamheid['omschrijving'] ?>
                (€ <?= $werkzaamheid['prijs_per_stuk'] ?> per uur)

            </option>

        <?php } ?>

    </select>

    <br><br>

    Aantal uren:

    <input
        type="number"
        name="uren"
        min="1"
        value="<?= $_POST['uren'] ?? '' ?>"
    >

    <hr>

    <h3>Materiaal toevoegen</h3>

    <select name="artikel_id">

        <?php foreach ($artikelen as $artikel) { ?>

            <option value="<?= $artikel['artikel_id'] ?>">

                <?= $artikel['naam'] ?>

                (voorraad:
                <?= $artikel['voorraad'] ?>)

                (€ <?= $artikel['prijs'] ?>)

            </option>

        <?php } ?>

    </select>

    Aantal:

    <input
        type="number"
        name="aantal"
        min="1"
    >

    <button
        type="submit"
        name="toevoegen">
        Toevoegen
    </button>

    <hr>

    <h3>Toegevoegde materialen</h3>

    <table border="1">

        <tr>
            <th>Naam</th>
            <th>Aantal</th>
            <th>Prijs</th>
            <th>Totaal</th>
        </tr>

        <?php foreach ($_SESSION['materialen'] as $materiaal) { ?>

            <tr>

                <td>
                    <?= $materiaal['naam'] ?>
                </td>

                <td>
                    <?= $materiaal['aantal'] ?>
                </td>

                <td>
                    € <?= $materiaal['prijs'] ?>
                </td>

                <td>
                    € <?= $materiaal['aantal'] * $materiaal['prijs'] ?>
                </td>

            </tr>

        <?php } ?>

    </table>

    <br>

    <button
        type="submit"
        name="bereken">
        BTW berekenen
    </button>

</form>

<?php if ($totaal > 0) { ?>

    <hr>

    <h3>Overzicht</h3>

    Werkzaamheden:
    € <?= number_format($werk, 2) ?>
    <br>

    Materialen:
    € <?= number_format($materiaalKosten, 2) ?>
    <br>

    BTW (21%):
    € <?= number_format($btw, 2) ?>
    <br>

    <h2>
        Totaal:
        € <?= number_format($totaal, 2) ?>
    </h2>

    <button>
        Factuur opslaan
    </button>

<?php } ?>

<hr>

<form method="post">

    <button type="submit" name="nieuwe_factuur">
        Factuur leegmaken
    </button>

</form>

</body>

</html>