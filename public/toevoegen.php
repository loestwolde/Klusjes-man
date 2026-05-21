<form action="#" method="post">
    <label for="voorletters">Voornaam:</label>
    <input type="text" name="voorletters" />
    <br />
    <label for="achternaam">Achternaam:</label>
    <input type="text" name="achternaam" />
    <br />
    <label for="aanhef">Aanhef:</label>
    <input type="radio" name="aanhef" value="Hr"/>Hr
    <input type="radio" name="aanhef" value="Mevr"/>Mevr
    <br />
    <label for="email">Email:</label>
    <input type="email" name="email" />
    <br />
    
    <label for="telefoon">Telefoon:</label>
    <input type="text" name="telefoon" />
    <br />

    <label for="woonplaats">Woonplaats:</label>
    <input type="text" name="woonplaats" />
    <br />

    <label for="straat">Straat:</label>
    <input type="text" name="straat" />
    <br />

    <label for="huisnummer">Huisnummer:</label>
    <input type="text" name="huisnummer" />
    <br />

    <label for="huisnummertoevoeging">Huisnummertoevoeging:</label>
    <input type="text" name="huisnummertoevoeging" />
    <br />

    <input type="submit" name="saveCustomer"/>
</form>

<?php
include("../src/customer.php");

if(isset($_POST['saveCustomer'])){
    // formulier uitlezen
    $voorletters = $_POST['voorletters'];
    $achternaam = $_POST['achternaam'];
    $aanhef = $_POST['aanhef'];
    $email = $_POST['email'];
    $woonplaats = $_POST['woonplaats'];
    $straat = $_POST['straat'];
    $huisnummer = $_POST['huisnummer'];
    $huisnummer_toevoeging = $_POST['huisnummertoevoeging'];
    $phone = $_POST['telefoon'];
    
    // klant opslaan
    $newCustomer = new Customer();
    if($newCustomer->saveCustomer($voorletters, $achternaam, $aanhef, $email, $phone, $woonplaats, $straat, $huisnummer, $huisnummer_toevoeging))
    {
        $$adresId = $this->adresToevoegenAanKlant($lastId, $woonplaats, $straat, $huisnummer, $huisnummer_toevoeging);
        $newCustomer->klantAanAdresKoppelen($newCustomer->getlastid(), $adresId);
        echo "Klant is opgeslagen";
        header("Location: index.php");
        
    } else {
        echo "Klant is niet opgeslagen";
    }
}


