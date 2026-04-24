<?php
include('../src/customer.php');

$customer = new Customer();
$allCustomers = $customer->getAllCustomers();

echo "<h1>Klanten</h1>";
echo "<a href=toevoegen.php>Toevoegen</a>";
echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Geslacht</th>
            <th>Adres</th>
            <th>Telefoon</th>
            <th>Bekijken</th>
            <th>Bewerken</th>
            <th>Verwijderen</th>
        </tr>";

        foreach ($allCustomers as $customer)
        {
            echo "<tr>";
                echo "<td>" . $customer['klant_id'] . "</td>";
                echo "<td>" . $customer['voorletters'] . " " . $customer['achternaam'] . "</td>";
                echo "<td>" . $customer['aanhef'] . "</td>";
                // echo "<td>" . $customer['email'] . " " . $customer['postalCode'] . " " . $customer['city'] . " " . $customer['state'] . " " . $customer['country'] . "</td>";
                echo "<td>" . $customer['telefoon'] . "</td>";
                echo "<td>" . $customer['email'] . "</td>";
                echo "<td><a href=detail.php?klant_id=" . $customer['klant_id'] . ">Bekijk</a></td>";
                echo "<td><a href=update.php?klant_id=" . $customer['klant_id'] . ">Bewerken</a></td>";
                echo "<td><a href=delete.php?klant_id=" . $customer['klant_id'] . ">Verwijderen</a></td>";  
            echo "</tr>";
        }
echo "</table>";
