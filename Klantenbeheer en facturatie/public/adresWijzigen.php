<?php

include("../src/opvragen.php");

// nieuw object maken van klanten opvragen klasse
$klanten = new KlantenOpvragen();

// klanten id ophalen uit url, als er geen id gevonden is wordt het null
$id = $_GET['id'] ?? null;

// dan stopt het ook gelijk
if ($id === null) {
    echo "Geen klant geselecteerd.";
    exit;
}


if(isset($_POST['opslaan'])){

// als je op opslaan klikt wordt de adres wijzigen functie aangeroepen
$klanten->wijzigAdres(
    $id,
    $_POST['straat'],
    $_POST['huisnummer'],
    $_POST['postcode'],
    $_POST['woonplaats']
);


header("Location: klantInfo.php?id=".$id);
// je gaat terug naar klantInfo en exit zorgt ervoor dat de pagina ook echt stopt
exit;

}

?>

<html>

<head>
<link rel="stylesheet" href="style.css">
</head>

<body>

<header>
<h1>Adres wijzigen</h1>
</header>

<main>

<div class="card">
<form method="post">

<label>Straat</label>
<input name="straat">


<label>Huisnummer</label>
<input name="huisnummer">


<label>Postcode</label>
<input name="postcode">


<label>Woonplaats</label>
<input name="woonplaats">


<button class="btn" name="opslaan">
Opslaan
</button>

</form>


</div>
</main>

</body>

</html>