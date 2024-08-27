<?php

include_once '../db-config.php';


if (isset($_GET['q'])) {
    $variationName = $_GET['q'];

    $variations = $dbh->searchVariations($variationName);

    header('Content-Type: application/json');
    echo json_encode($variations);
} else {

    http_response_code(400);
    echo json_encode(['error' => 'variation name is missing.']);
}
