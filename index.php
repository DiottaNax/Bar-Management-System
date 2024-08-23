<?php

require "./db-config.php";

// Funzione per controllare se i cookie sono settati
function isLoggedIn() {
    return isset($_SESSION['employeeId']);
}

// Se i cookie sono settati, reindirizza a home.php
if (isLoggedIn()) {
    header("Location: home.php");
    exit();
} else {
    include_once "./login.php";
}
