<?php

require_once("session.php");

// To make sure if the user is authenticate or not.
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_COOKIE['session'])) {
        $count = sessionCheck();
        // If $count = 1, the session value is correct and the user is exist in the database.
        if($count == 1) {
            isAmountPositiveNumber($_POST['amount']);
            $amount = mysqli_real_escape_string($db,abs($_POST['amount']));
            $sql = "UPDATE clients SET balance = balance + $amount WHERE account_number = '$account_number'";
            mysqli_query($db,$sql);
            successfulOperation();
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
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Deposit</title>
</head>
<body>
    <form action="./deposit.php" method="post">
        <div class="form__group field">
            <input type="input" pattern="[0-9]+(\.[0-9]{1,2})?%?" class="form__field form__field__pl" placeholder="Amount" name="amount" id='name' required />
            <label for="Amount" class="form__label">Amount</label>
        </div>
        <div class="btn-group">
            <button class="B1">Deposit</button>
        </div>
    </form>
</body>
</html>