<?php

require "./db-config.php";

// Verify if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['employeeId']);
}

// If user is logged in then redirect to home.php
if (isLoggedIn()) {
    header("Location: home.php");
    exit();
} else {
    include_once "./login.php";
}
