<?php

require_once("session.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // To make sure if the user is authenticate or not.
    if(isset($_COOKIE['session'])) {
        $count = sessionCheck();
        // If $count = 1, the session value is correct and the user is exist in the database.
        if($count == 1) {
            // Make a chcek on user input, to make sure the amount is a positive number.
            isAmountPositiveNumber($_POST['amount']);
            $amount = mysqli_real_escape_string($db,abs($_POST['amount']));
            $balance = balanceCheck();            
            // Check if there is enough balance or not?
            if($balance < $amount){
                insufficientBalance();
            }
            else {
                // Update the balance.
                $sql = "UPDATE clients SET balance = balance - $amount WHERE account_number = '$account_number'";
                mysqli_query($db,$sql);
                successfulOperation();
            }
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
    <title>Withdrawal</title>
</head>
<body>
    <form action="./withdrawal.php" method="post">
        <div class="form__group field">
            <input type="input" pattern="[0-9]+(\.[0-9]{1,2})?%?" class="form__field form__field__pl" placeholder="Amount" name="amount" id='name' required />
            <label for="Amount" class="form__label">Amount</label>
        </div>
        <div class="btn-group">
                <button class="B1">Withdrawal</button>
        </div>
    </form>
</body>
</html>