<?php

require_once '../db-config.php';

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

// Execute the appropriate query for the appropriate type
switch ($type) {
    case 'sales':
        $data = $dbh->getSalesInfo($startDate, $endDate);
        break;
    case 'costs':
        $data = $dbh->getStockCostsInfo($startDate, $endDate);
        break;
    case 'services':
        $data = $dbh->getServicesInfo($startDate, $endDate);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unable to provide info of this type!']);
        exit;
}

echo json_encode($data);
