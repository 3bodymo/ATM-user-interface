<?php

require_once("session.php");

if($_SERVER["REQUEST_METHOD"] == "GET") {
    // To make sure if the user is authenticate or not.
    if(isset($_COOKIE['session'])) {
        $count = sessionCheck();
        // If $count = 1, the session value is correct and the user is exist in the database.
        if($count == 1) {
            $sql = "SELECT balance FROM clients WHERE account_number = '$account_number'";
            $result = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($result);
            $balance = $row['balance'];
            echo '<script type="text/javascript">';
            echo 'alert("Your account balance is $' . $balance . '.");';
            echo 'location.href = "./dashboard.html";';
            echo '</script>';
        }
        else {
            unauthorizedAccess();
        }
    }
    else {
        unauthorizedAccess();
    }
}    
?>