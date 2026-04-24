
<?php
include("../src/customer.php");

echo "<pre>";
print_r($_GET);
echo "</pre>";

if(isset($_GET['klant_id'])){
    $customer = new Customer();
    $customerData = $customer->getCustomer($_GET['klant_id']);
    
}

?>

<h1>Klant: <?php echo $customerData[0]['voorletters'] . " " . $customerData[0]['achternaam']; ?></h1>
<p>aanhef: <?php echo $customerData[0]['aanhef']; ?></p>

<p>Telefoon: <?php echo $customerData[0]['telefoon']; ?></p>
<a href="update.php?klant_id=<?php echo $customerData[0]['klant_id']; ?>">Bewerken</a></br>
<a href="delete.php?klant_id=<?php echo $customerData[0]['klant_id']; ?>">Verwijderen</a>
<a href="index.php">Terug naar overzicht</a>




