<?php
require_once '../db-config.php';

// Assicuriamoci che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_GET['startDate'], $_GET['endDate'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing start, end or both dates']);
    exit;
}

$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];

$data = $dbh->getSalesInfo($startDate, $endDate);

echo json_encode($data);
