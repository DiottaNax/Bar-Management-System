<?php

include_once "../db-config.php";

unset($result);

// Set error message as default
$result['message'] = "Wrong email or password";
$result['success'] = 0;

// Check if the email and password are set
if(isset($_POST['email'], $_POST['password'])) {
    // If the login is successful, get the employee essential info and set the result message
    if($dbh->login($_POST['email'], $_POST['password'])) {
        $_SESSION = $dbh->getEmployeeEssentialInfo($_POST['email']);
        $result['message'] = "Login successful!";
        $result['success'] = 1;
    }
}

header('Content-Type: application/json');
echo json_encode($result);
