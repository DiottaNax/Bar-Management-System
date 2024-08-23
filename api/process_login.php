<?php

include_once "../db-config.php";

$result['message'] = "Wrong email or password";
$result['success'] = 0;

if(isset($_POST['email'], $_POST['password'])) {
    if($dbh->login($_POST['email'], $_POST['password'])) {
        $_SESSION = $dbh->getEmployeeEssentialInfo($_POST['email']);
        $result['message'] = "Login successful!";
        $result['success'] = 1;
    }
}

header('Content-Type: application/json');
echo json_encode($result);
