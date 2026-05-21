<?php
include("../src/customer.php");

if(isset($_GET['klant_id'])){
    $customer = new Customer();
    $customer->deleteCustomer($_GET['klant_id']);
    header("Location: index.php");
    exit;
}
?>
