<?php
require_once '../db-config.php';

// Assicuriamoci che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_GET['startDate'], $_GET['endDate'], $_GET['type'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing some parameter!']);
    exit;
}

$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];
$type = $_GET['type'];

if($type == 'sales')
    $data = $dbh->getSalesInfo($startDate, $endDate);
else if ($type == 'costs')
    $data = $dbh->getStockCostsInfo($startDate, $endDate);
else if ($type == 'services')
    $data = $dbh->getServicesInfo($startDate, $endDate);
else {
    http_response_code(400);
    echo json_encode(['error' => 'Unable to provide info of this type!']);
    exit;
}

echo json_encode($data);
