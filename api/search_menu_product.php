<?php
include_once '../db-config.php';

if (isset($_GET['q'])) {
    // Search for the menu's product starting with the given string
    $prodName = $_GET['q'];
    
    unset($products);
    $products = $dbh->searchMenuProduct($prodName);

    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    // Return an error if the parameter is missing
    http_response_code(400);
    echo json_encode(['error' => 'product name missing.']);
}
