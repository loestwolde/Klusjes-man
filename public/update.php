<?php
include("../src/customer.php");

if(!isset($_GET['klant_id'])){
    header("Location: index.php");
    exit;
}

$customer = new Customer();

// Formulier opgeslagen
if(isset($_POST['saveCustomer'])){
    $voorletters           = $_POST['voorletters'];
    $achternaam            = $_POST['achternaam'];
    $aanhef                = $_POST['aanhef'];
    $email                 = $_POST['email'];
    $telefoon              = $_POST['telefoon'];
    $woonplaats            = $_POST['woonplaats'];
    $postcode              = $_POST['postcode'];
    $straat                = $_POST['straat'];
    $huisnummer            = $_POST['huisnummer'];
    $huisnummer_toevoeging = $_POST['huisnummertoevoeging'];

    $bijgewerkt = $customer->updateCustomer(
        $_GET['klant_id'],
        $voorletters,
        $achternaam,
        $aanhef,
        $email,
        $telefoon,
        $woonplaats,
        $postcode,
        $straat,
        $huisnummer,
        $huisnummer_toevoeging
    );

    if($bijgewerkt){
        header("Location: detail.php?klant_id=" . $_GET['klant_id']);
        echo "Klant is bijgewerkt.";
        exit;
    } else {
        $foutmelding = "Bijwerken mislukt. Controleer of alle velden zijn ingevuld.";
        echo $foutmelding;
    }
}

// Huidige klantgegevens ophalen voor het formulier
$customerData = $customer->getCustomer($_GET['klant_id']);
$klant = $customerData[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Klant bewerken</title>
</head>
<body>

<h1>Klant bewerken</h1>

<?php if(isset($foutmelding)): ?>
    <p style="color:red;"><?php echo $foutmelding; ?></p>
<?php endif; ?>

<form action="update.php?klant_id=<?php echo $_GET['klant_id']; ?>" method="post">

    <label>Voorletters:</label>
    <input type="text" name="voorletters" value="<?php echo $klant['voorletters']; ?>" />
    <br><br>

    <label>Achternaam:</label>
    <input type="text" name="achternaam" value="<?php echo $klant['achternaam']; ?>" />
    <br><br>

    <label>Aanhef:</label>
    <input type="radio" name="aanhef" value="Hr" <?php echo $klant['aanhef'] == 'Hr' ? 'checked' : ''; ?> /> Hr
    <input type="radio" name="aanhef" value="Mevr" <?php echo $klant['aanhef'] == 'Mevr' ? 'checked' : ''; ?> /> Mevr
    <br><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $klant['email']; ?>" />
    <br><br>

    <label>Telefoon:</label>
    <input type="text" name="telefoon" value="<?php echo $klant['telefoon']; ?>" />
    <br><br>

    <label>Woonplaats:</label>
    <input type="text" name="woonplaats" value="<?php echo $klant['woonplaats']; ?>" />
    <br><br>

    <label>Postcode:</label>
    <input type="text" name="postcode" value="<?php echo $klant['postcode']; ?>" />
    <br><br>

    <label>Straat:</label>
    <input type="text" name="straat" value="<?php echo $klant['straat']; ?>" />
    <br><br>

    <label>Huisnummer:</label>
    <input type="text" name="huisnummer" value="<?php echo $klant['huisnummer']; ?>" />
    <br><br>

    <label>Huisnummertoevoeging:</label>
    <input type="text" name="huisnummertoevoeging" value="<?php echo $klant['huisnummer_toevoeging']; ?>" />
    <br><br>

    <input type="submit" name="saveCustomer" value="Opslaan" />
    <a href="detail.php?klant_id=<?php echo $_GET['klant_id']; ?>">Annuleren</a>

</form>

</body>
</html>
