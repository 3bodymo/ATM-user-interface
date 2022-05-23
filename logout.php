<?php
if (isset($_COOKIE['account_number']) || isset($_COOKIE['session'])) {
    unset($_COOKIE['account_number']); 
    unset($_COOKIE['session']); 
    setcookie('account_number', null, -1, '/'); 
    setcookie('session', null, -1, '/'); 
    sleep(2);
    header('location: login.php');
}
?>