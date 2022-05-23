<?php

require_once("session.php");

// To make sure if the user is authenticate or not.
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_COOKIE['session'])) {
        $count = sessionCheck();
        // If $count = 1, the session value is correct and the user is exist in the database.
        if($count == 1) {
            $new_pin_code = $_POST['new_pin_code'];
            newPinCodeValidation($new_pin_code);
            $pin_code = pinCodeCheck();
            if($new_pin_code == $pin_code){
                echo '<script type="text/javascript">';
                echo 'alert("The PIN Code matches your current PIN Code, please choose a different one!")';
                echo '</script>';
            }
            else {
                $new_pin_code = mysqli_real_escape_string($db, $new_pin_code);
                $sql = "UPDATE clients SET pin_code = '$new_pin_code' WHERE account_number = '$account_number'";
                mysqli_query($db,$sql);
                reBuildSession($new_pin_code);
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
    <title>Change PIN Code</title>
</head>
<body>
    <form action="./change_pin.php" method="post">
        <div class="form__group field">
            <input type="input" maxlength="4" pattern="[0-9]{4}" class="form__field form__field__pl" placeholder="New PIN Code" name="new_pin_code" id='name' required />
            <label for="Amount" class="form__label">New PIN Code</label>
        </div>
        <div class="btn-group">
            <button class="B1">Submit</button>
        </div>
    </form>
</body>
</html>