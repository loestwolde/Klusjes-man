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
   
    $phone = $_POST['telefoon'];
    
    // klant opslaan
    $newCustomer = new Customer();
    if($newCustomer->saveCustomer($voorletters, $achternaam, $aanhef, $email, $phone))
    {
        echo "Klant is opgeslagen";
        header("Location: index.php");
    } else {
        echo "Klant is niet opgeslagen";
    }
}


