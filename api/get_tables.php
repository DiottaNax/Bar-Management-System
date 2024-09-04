<?php

require_once '../db-config.php';

// Get the date from the query string
$date = $_GET['date'] ?? null;

$isFormatCorrect = preg_match("/^\d{4}-\d{2}-\d{2}$/", $date);

// Validate the date format if provided, if the format is correct get the tables
if ($date !== null && !$isFormatCorrect) {
    $response = ["error" => "Invalid date format. Use YYYY-MM-DD."];
} else {
    $tables = $dbh->getTables($date);
    $response = $tables;
}

header("Content-Type: application/json");
echo json_encode($response);
