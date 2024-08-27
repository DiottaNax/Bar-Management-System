<?php

require_once '../db-config.php';

// Get the date from the query string
$date = $_GET['date'] ?? null;


try {
    // Validate the date format if provided
    if ($date !== null && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
        throw new Exception("Invalid date format. Use YYYY-MM-DD.");
    }

    $tables = $dbh->getTables($date);

    echo json_encode($tables);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

header("Content-Type: application/json");
