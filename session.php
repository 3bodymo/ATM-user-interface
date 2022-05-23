<?php 

require_once("config.php");

class User {
	public $card_number;
	public $card_mm;
	public $card_yy;
	public $card_cvv;
	public $pin_code;
	public $account_number;

	public function __construct($card_number, $card_mm, $card_yy, $card_cvv, $pin_code, $account_number) {
		$this->card_number = $card_number;
		$this->card_mm = $card_mm;
		$this->card_yy = $card_yy;
		$this->card_cvv = $card_cvv;
		$this->pin_code = $pin_code;
		$this->account_number = $account_number;
	}
}

// Set new session for users who logged in with NFC technology.
function setSessionNFC($account_number, $pin_code) {
	$db = connectToDatabase();
	$sql = "SELECT * FROM clients WHERE account_number = '$account_number' AND pin_code = '$pin_code'";
	$result = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($result);
	$account_number = $row['account_number'];
	$card_number = $row['card_number'];
	$card_mm = $row['card_mm'];
	$card_yy = $row['card_yy'];
	$card_cvv = $row['card_cvv'];
	$pin_code = $row['pin_code'];
	$count = mysqli_num_rows($result);
  // If $count = 1, the information is correct and the user is exist in the database.
	if($count == 1) {
		$user = new User($card_number, $card_mm, $card_yy, $card_cvv, $pin_code, $account_number);
		$serial_user = serialize($user);
		$serial_user_encrypted = openssl_encrypt($serial_user, "AES-128-CTR", "SX4567!ke@5628#5SadK5", 0, '1234567891011121');
		$cookie_name = "session";
		setcookie($cookie_name, $serial_user_encrypted);
		sleep(2);
		header("location: dashboard.html");
	}
	else {
		echo '<script type="text/javascript">';
		echo 'alert("The PIN Code you entered is incorrect!");';
		echo 'location.href = "./login.php";';
		echo '</script>';
	}
}

// Set new session for users who logged in through Login page.
function setSession($card_number, $card_mm, $card_yy, $card_cvv, $pin_code) {
	$db = connectToDatabase();
	$card_number = mysqli_real_escape_string($db,$card_number);
	$card_mm = mysqli_real_escape_string($db,$card_mm);
	$card_yy = mysqli_real_escape_string($db,$card_yy);
	$card_cvv = mysqli_real_escape_string($db,$card_cvv);
	$pin_code = mysqli_real_escape_string($db,$pin_code); 
	
	$sql = "SELECT account_number FROM clients WHERE card_number = '$card_number' AND card_mm = '$card_mm' AND card_yy = '$card_yy' AND card_cvv = '$card_cvv' AND pin_code = '$pin_code'";
	$result = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($result);
	$account_number = $row['account_number'];
	$count = mysqli_num_rows($result);
  // If $count = 1, the information is correct and the user is exist in the database.
	if($count == 1) {
		$user = new User($card_number, $card_mm, $card_yy, $card_cvv, $pin_code, $account_number);
		$serial_user = serialize($user);
		$serial_user_encrypted = openssl_encrypt($serial_user, "AES-128-CTR", "SX4567!ke@5628#5SadK5", 0, '1234567891011121');
		$cookie_name = "session";
		setcookie($cookie_name, $serial_user_encrypted);
		sleep(2);
		header("location: dashboard.html");
	}
	else {
		echo '<script type="text/javascript">';
		echo 'alert("The information you entered is incorrect!")';
		echo '</script>';
	}
}

// When the user update his pin code, the session will be invalid, so we need to re-build it.
function reBuildSession($new_pin_code) {
	$db = connectToDatabase();
	$new_pin_code = mysqli_real_escape_string($db, $new_pin_code);
	$accountNumber = accountNumber();
	$sql = "SELECT * FROM clients WHERE account_number = '$accountNumber' AND pin_code = '$new_pin_code'";
	$result = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($result);
	$card_number = $row['card_number'];
	$card_mm = $row['card_mm'];
	$card_yy = $row['card_yy'];
	$card_cvv = $row['card_cvv'];
	$pin_code = $row['pin_code'];
	$account_number = $row['account_number'];
	$user = new User($card_number, $card_mm, $card_yy, $card_cvv, $pin_code, $account_number);
	$serial_user = serialize($user);
	$serial_user_encrypted = openssl_encrypt($serial_user, "AES-128-CTR", "SX4567!ke@5628#5SadK5", 0, '1234567891011121');
	$cookie_name = "session";
	setcookie($cookie_name, $serial_user_encrypted);
	sleep(2);
}

// Check if account number session is valid or not.
function accountNumberSessionCheck($account_number){
	$db = connectToDatabase();
	$sql = "SELECT user_id FROM clients WHERE account_number = '$account_number'";
	$result = mysqli_query($db,$sql);
	$count = mysqli_num_rows($result);
	// If $count != 1, the account number doesn't exist in the database.
	if($count != 1) {
		echo "The session is invalid, please re-login!";
		header("location: login.php");
		die();
	}
}

// Decrypt the value of session to retrieve the data from it.
function sessionCheck() {
	$serial_user_decrypted = openssl_decrypt($_COOKIE['session'], "AES-128-CTR", "SX4567!ke@5628#5SadK5", 0, '1234567891011121');
  $unserial_user = unserialize($serial_user_decrypted);
	global $account_number;
	$account_number = $unserial_user->account_number;
	$pin_code = $unserial_user->pin_code;    
	$db = connectToDatabase();    
	$sql = "SELECT user_id FROM clients WHERE account_number = '$account_number' and pin_code = '$pin_code'";
	$result = mysqli_query($db,$sql);
	$count = mysqli_num_rows($result);
	return $count;
}

// When the user transfer money to someone, we need to make sure that user is exist in our Database.
function isClientInDatabase($reciver_account_number) {
	$db = connectToDatabase();
	$sql = "SELECT user_id FROM clients WHERE account_number = '$reciver_account_number'";
	$result = mysqli_query($db,$sql);
	$count = mysqli_num_rows($result);
	if($count == 1) {
		return true;
	}
	else {
		return false;
	}
}

// The only job to this function is to return current user account number.
function accountNumber() {
	$serial_user_decrypted = openssl_decrypt($_COOKIE['session'], "AES-128-CTR", "SX4567!ke@5628#5SadK5", 0, '1234567891011121');
  $unserial_user = unserialize($serial_user_decrypted);
	$account_number = $unserial_user->account_number;
	return $account_number;
}

// The only job to this function is to return current user balance.
function balanceCheck() {
	$db = connectToDatabase();
	$accountNumber = accountNumber();
	$sql = "SELECT balance FROM clients WHERE account_number = '$accountNumber'";
	$result = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($result);
	$balance = $row['balance'];
	return $balance;
}

// The only job to this function is to return current user PIN Code.
function pinCodeCheck(){
	$db = connectToDatabase();
	$accountNumber = accountNumber();
	$sql = "SELECT pin_code FROM clients WHERE account_number = '$accountNumber'";
	$result = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($result);
	$pin_code = $row['pin_code'];
	return $pin_code;
}

// Make a check on user input, to make sure the amount is a positive number and doesn't contain special character.
function isAmountPositiveNumber($amount) {
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $amount) || !is_numeric($amount)) {
		echo "The amount must be a positive number only!";
		die();
	}
}

// Make a check on the user input, to make sure that the new PIN Code match the requirements.
function newPinCodeValidation($new_pin_code) {
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $new_pin_code) || !is_numeric($new_pin_code) || strlen($new_pin_code) != 4) {
		echo "The PIN Code must be a four digits!";
		die();
	}
}

// Insufficient Balance Message.
function insufficientBalance() {
	echo '<script type="text/javascript">';
  echo 'alert("Insufficient Balance!")';
  echo '</script>';
}

// Successful Operation Message.
function successfulOperation() {
	echo '<script type="text/javascript">';
	echo 'alert("Successful Operation!");';
	echo 'location.href = "./dashboard.html";';
	echo '</script>';
}

// Unauthorized Access Message.
function unauthorizedAccess() {
	echo '<script type="text/javascript">';
	echo 'alert("Unauthorized Access!");';
	echo 'location.href = "./login.php";';
	echo '</script>';
}

?>