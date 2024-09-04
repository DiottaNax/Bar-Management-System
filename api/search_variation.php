<?php

include_once '../db-config.php';


if (isset($_GET['q'])) {
    // Search for the variation starting with the given string
    $variationName = $_GET['q'];
    $variations = $dbh->searchVariations($variationName);

    header('Content-Type: application/json');
    echo json_encode($variations);
} else {
    // Return an error if the parameter is missing
    http_response_code(400);
    echo json_encode(['error' => 'variation name is missing.']);
}
