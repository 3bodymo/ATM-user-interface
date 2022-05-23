<?php

require_once("session.php");

if($_SERVER["REQUEST_METHOD"] == "GET") {
    $account_number = shell_exec("nfc-list -t 1 | sed -n 's/ //g;/UID/s/.*://p'");
    if($account_number == "") {
        echo '<script type="text/javascript">';
        echo 'alert("Please make sure that your card is palced on the reader!");';
        echo 'location.href = "./login.php";';
        echo '</script>';
    }
    else {
        $account_number = preg_replace('/\s+/', '', $account_number);
	$account_number_encrypted = openssl_encrypt($account_number, "AES-128-CTR", "SX4567!ke@5628#5SadK5", 0, '1234567891011121');
        setcookie("account_number", $account_number_encrypted);
        $db = connectToDatabase();
        $sql = "SELECT first_name FROM clients WHERE account_number = '$account_number'";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);
        $first_name = $row['first_name'];
        // If the count does not equal 1, that mean the card doesn't exist in our Database.
        if($count != 1){
          echo '<script type="text/javascript">';
          echo 'alert("The card doesn\'t exist in our Database!");';
          echo 'location.href = "./login.php";';
          echo '</script>';
        }
    }
}
else if($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number_decrypted = openssl_decrypt($_COOKIE['account_number'], "AES-128-CTR", "SX4567!ke@5628#5SadK5", 0, '1234567891011121');
    accountNumberSessionCheck($account_number_decrypted);
    $pin_code = $_POST['pin_code'];
    setSessionNFC($account_number_decrypted, $pin_code);
}
?>
<head>
  <title>NFC</title>
  <link rel="stylesheet" href="css/nfc_scanner.css">
</head>
<body class="align">
  <h3>Welcome <?php echo $first_name ?>, please enter your PIN Code!</h3>
  <form method="post">
    <div class="form__field">
      <input type="password" name="pin_code" class="form__input" maxlength="4" pattern="[0-9]{4}" required>
      <span class="icon"></span>
    </div>
  </form>
  <p>PIN Code must be four digits.</p>
</body>

