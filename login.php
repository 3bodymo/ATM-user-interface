<?php

require_once("session.php");

// POST request mean that the user clicked on the button, and he sent his data in the form.
if($_SERVER["REQUEST_METHOD"] == "POST") {
  	setSession($_POST['card_number'], $_POST['card_mm'], $_POST['card_yy'], $_POST['card_cvv'], $_POST['pin_code']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Login</title>
    <link rel='stylesheet' type='text/css' media='screen' href='css/login.css'>
</head>
<body>
    <div class="demo">
		<form class="payment-card" method="post">
			<div class="bank-card">
				<div class="bank-card__side bank-card__side_front">
					<div class="bank-card__inner">
						<label class="bank-card__label bank-card__label_holder">
							<span class="bank-card__hint">Card Number</span>
							<input type="text" class="bank-card__field" placeholder="Card Number" maxlength="16" pattern="[0-9]{16}" name="card_number" required>
						</label>
					</div>
					<div class="bank-card__inner">
						<label class="bank-card__label bank-card__label_number">
							<span class="bank-card__hint">PIN Code</span>
							<input type="password" class="bank-card__field" placeholder="PIN Code" maxlength="4" pattern="[0-9]{4}" name="pin_code" required>
						</label>
					</div>
					<div class="bank-card__inner">
						<span class="bank-card__caption">Expiration Date</span>
					</div>
					<div class="bank-card__inner bank-card__footer">
						<label class="bank-card__label bank-card__month">
							<span class="bank-card__hint">Month</span>
							<input type="text" class="bank-card__field" placeholder="MM" maxlength="2" pattern="[0-9]{2}" name="card_mm" required>
						</label>
						<span class="bank-card__separator">/</span>
						<label class="bank-card__label bank-card__year">
							<span class="bank-card__hint">Year</span>
							<input type="text" class="bank-card__field" placeholder="YY" maxlength="2" pattern="[0-9]{2}" name="card_yy" required>
						</label>
					</div>
				</div>
				<div class="bank-card__side bank-card__side_back">
					<div class="bank-card__inner">
						<label class="bank-card__label bank-card__cvc">
							<span class="bank-card__hint">CVV</span>
							<input type="text" class="bank-card__field" placeholder="CVV" maxlength="3" pattern="[0-9]{3}" name="card_cvv" required>
						</label>
					</div>
				</div>
			</div>
			<div class="payment-card__footer">
				<button class="payment-card__button">Login</button><br><br>
				<a href="./nfc_scanner.php">Click here to scan</a> 
			</div>
		</form>
	</div>
<footer class="footer">
</footer>
</body>
</html>