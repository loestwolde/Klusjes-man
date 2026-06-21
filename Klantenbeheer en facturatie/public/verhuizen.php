<?php
include("../src/opvragen.php");

$klanten = new KlantenOpvragen();

// klant id uit de url halen
$id = $_GET['id'] ?? null;

if ($id === null) {
    echo "Geen klant geselecteerd.";
    exit;
}


if(isset($_POST['opslaan'])){

// je geeft de nieuwe gegevens mee, startdatum is de datum van verhuizing. Dit wordt dan ook de einddatum bij het oude adres
$klanten->nieuwAdres($id, $_POST['straat'], $_POST['huisnummer'], $_POST['postcode'], $_POST['woonplaats'], $_POST['startdatum']);

// doorgestuurd worden naar klantInfo en de pagina wordt gerefreshed
header("Location: klantInfo.php?id=".$id);
exit;
}

?>

<html>
<head>

<title>Verhuizing</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<header>

<h1>Verhuizing doorgeven</h1>

</header>

<main>

<div class="card">

<h2>Nieuw adres invoeren</h2>

<form method="post">

<label>Straat</label>
<input name="straat">


<label>Huisnummer</label>
<input name="huisnummer">


<label>Postcode</label>
<input name="postcode">


<label>Woonplaats</label>
<input name="woonplaats">


<label>Verhuisdatum</label>
<input type="date" name="startdatum">


<button class="btn" name="opslaan">Opslaan</button>

</form>

</div>
</main>

</body>
</html>