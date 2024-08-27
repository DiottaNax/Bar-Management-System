<?php

include_once "../db-config.php";

$result['error'] = "Something went wrong during query execution";
$result['success'] = 0;

if(isset($_POST['name'], $_POST['seats'])) {
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

echo json_encode($result);
