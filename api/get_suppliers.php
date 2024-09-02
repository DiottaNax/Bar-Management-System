<?php
require_once '../db-config.php';

// Assicuriamoci che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_GET['prodId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'A valid Prod Id is required']);
    exit;
}

$prodId = $_GET['prodId'];

$data = $dbh->getSuppliersForProd($prodId);

echo json_encode($data);
