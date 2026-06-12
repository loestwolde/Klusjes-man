<link rel="stylesheet" href="../style/style.css">
<img src="../images/logoKlusjesman.png" alt="Klusjesman Logo" class="logo">

<?php
include("../src/customer.php");

if(isset($_POST['saveCustomer'])){

    // formulier uitlezen
    $voorletters            = $_POST['voorletters'];
    $achternaam             = $_POST['achternaam'];
    $aanhef                 = $_POST['aanhef'];
    $email                  = $_POST['email'];
    $woonplaats             = $_POST['woonplaats'];
    $postcode               = $_POST['postcode'];
    $straat                 = $_POST['straat'];
    $huisnummer             = $_POST['huisnummer'];
    $huisnummer_toevoeging  = $_POST['huisnummertoevoeging'];
    $phone                  = $_POST['telefoon'];

    // nieuwe klant maken
    $newCustomer = new Customer();

    // klant opslaan (inclusief adres)
    $klantOpgeslagen = $newCustomer->saveCustomer(
        $voorletters,
        $achternaam,
        $aanhef,
        $email,
        $phone,
        $woonplaats,
        $postcode,
        $straat,
        $huisnummer,
        $huisnummer_toevoeging
    );

    if($klantOpgeslagen){
        header("Location: index.php");
        exit;
    } else {
        echo "Klant is niet opgeslagen. Controleer of alle velden zijn ingevuld.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Klant toevoegen</title>
</head>
<body>

<form action="#" method="post">

    <label>Voorletters:</label>
    <input type="text" name="voorletters" />
    <br><br>

    <label>Achternaam:</label>
    <input type="text" name="achternaam" />
    <br><br>

    <label>Aanhef:</label>
    <input type="radio" name="aanhef" value="Hr" /> Hr
    <input type="radio" name="aanhef" value="Mevr" /> Mevr
    <br><br>

    <label>Email:</label>
    <input type="email" name="email" />
    <br><br>

    <label>Telefoon:</label>
    <input type="text" name="telefoon" />
    <br><br>

    <label>Woonplaats:</label>
    <input type="text" name="woonplaats" />
    <br><br>

    <label>Postcode:</label>
    <input type="text" name="postcode" />
    <br><br>

    <label>Straat:</label>
    <input type="text" name="straat" />
    <br><br>

    <label>Huisnummer:</label>
    <input type="text" name="huisnummer" />
    <br><br>

    <label>Huisnummertoevoeging:</label>
    <input type="text" name="huisnummertoevoeging" />
    <br><br>

    <input type="submit" name="saveCustomer" value="Opslaan" />

</form>

</body>
</html>