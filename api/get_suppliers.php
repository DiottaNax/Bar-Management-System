<?php
require_once '../db-config.php';

// Ensure that the request method is a get or else return an error
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Ensure that the prodId parameter is set or else return an error
if (!isset($_GET['prodId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'A valid Prod Id is required']);
    exit;
}

$prodId = $_GET['prodId'];

// Get the suppliers for the product as an associative array (using MYSQLI_ASSOC)
$data = $dbh->getSuppliersForProd($prodId);

echo json_encode($data);
