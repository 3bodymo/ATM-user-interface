<?php

require_once("config.php");
require_once("session.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // To make sure if the user is authenticate or not.
    if(isset($_COOKIE['session'])) {
        $count = sessionCheck();
        // If $count = 1, the session value is correct and the user is exist in the database.
        if($count == 1) {
            isAmountPositiveNumber($_POST['amount']);
            $balance = balanceCheck();
            $amount = mysqli_real_escape_string($db,abs($_POST['amount']));   
            // Here we make a check on user balance to make sure that he has enough money to perform the transaction.
            if($balance < $amount){
                insufficientBalance();
            }
            else {
                $reciver_account_number = mysqli_real_escape_string($db,$_POST['account_number']);
                // Check if the user transfer to himself or not.
                if($reciver_account_number == $account_number){
                    echo '<script type="text/javascript">';
                    echo 'alert("You can\'t transfer to yourself!")';
                    echo '</script>';
                }
                else if(isClientInDatabase($reciver_account_number) == false){
                    echo '<script type="text/javascript">';
                    echo 'alert("The account number doesn\'t exist in our Database!")';
                    echo '</script>';
                }
                else {
                    // Update the sender balance.
                    $sql = "UPDATE clients SET balance = balance - $amount WHERE account_number = '$account_number'";
                    mysqli_query($db,$sql);
                    // Update the receiver balance.
                    $sql = "UPDATE clients SET balance = balance + $amount WHERE account_number = '$reciver_account_number'";
                    mysqli_query($db,$sql);
                    successfulOperation();
                }
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
    <title>Transfer</title>
</head>
<body>
    <form action="./transfer.php" method="post">
        <div class="form__group field">
            <input type="input" class="form__field form__field__pl" name="account_number" id='name' required />
            <label for="account_number" class="form__label">Account Number</label>
            <br><input type="input" pattern="[0-9]+(\.[0-9]{1,2})?%?" name="amount" class="form__field" placeholder="Amount" id='name' required />
        </div>
        <div class="btn-group">
            <button class="B1">Transfer</button>
        </div>
    </form>
</body>
</html>