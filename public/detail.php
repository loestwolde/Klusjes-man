<link rel="stylesheet" href="../style/style.css">
<img src="../images/logoKlusjesman.png" alt="Klusjesman Logo" class="logo">

<?php
include("../src/customer.php");

if(isset($_GET['klant_id'])){
    $customer = new Customer();
    $customerData = $customer->getCustomer($_GET['klant_id']);
}
?>

<h1>Klant: <?php echo $customerData[0]['voorletters'] . " " . $customerData[0]['achternaam']; ?></h1>
<p>Aanhef: <?php echo $customerData[0]['aanhef']; ?></p>
<p>Telefoon: <?php echo $customerData[0]['telefoon']; ?></p>
<p>Email: <?php echo $customerData[0]['email']; ?></p>

<h2>Adres</h2>
<p>
    <?php echo $customerData[0]['straat'] . " " . $customerData[0]['huisnummer']; ?>
    <?php if(!empty($customerData[0]['huisnummer_toevoeging'])) echo $customerData[0]['huisnummer_toevoeging']; ?>
</p>
<p><?php echo $customerData[0]['postcode'] . " " . $customerData[0]['woonplaats']; ?></p>

<a href="update.php?klant_id=<?php echo $customerData[0]['klant_id']; ?>">Bewerken</a><br/>
<a href="delete.php?klant_id=<?php echo $customerData[0]['klant_id']; ?>">Verwijderen</a><br/>
<a href="register_klus.php?klant_id=<?php echo $customerData[0]['klant_id']; ?>">klus registratie</a><br/>
<a href="index.php">Terug naar overzicht</a>