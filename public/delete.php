<link rel="stylesheet" href="../style/style.css">
<img src="../images/logoKlusjesman.png" alt="Klusjesman Logo" class="logo">

<?php
include("../src/customer.php");

if(isset($_GET['klant_id'])){
    $customer = new Customer();
    $customer->deleteCustomer($_GET['klant_id']);
    header("Location: index.php");
    exit;
}
?>
