<?php
include_once '../db-config.php';

if (isset($_GET['q'])) {
    $prodName = $_GET['q'];
    $products = $dbh->searchStockedProduct($prodName);

    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    // Errore se il parametro menuProdId non è presente
    http_response_code(400);
    echo json_encode(['error' => 'product name missing.']);
}
