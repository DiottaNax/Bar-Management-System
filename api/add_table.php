<?php

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => 0, 'error' => 'Method Not Allowed']); // Return a JSON response with a 405 status code if the method is not POST
    exit;
}

include_once "../db-config.php";

unset($result); // Unset the $result variable to clear any previous data

// Initialize the $result array with error and success values, result array is set as if the query failed
$result['error'] = "Something went wrong during query execution";
$result['success'] = 0;


if(isset($_POST['name'], $_POST['seats'])) {
    // Try to add a new table to the database
    $queryResult = $dbh->addNewTable($_POST['name'], $_POST['seats']);

    if(!$queryResult) {
        http_response_code(400);
    } else {
        $result['error'] = "";
        $result['success'] = 1;
        $result['message'] = "Table added successfully";
        http_response_code(200);
    }
}

echo json_encode($result); // Return the $result array as a JSON response
