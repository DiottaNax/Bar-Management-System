<?php
require_once '../db-config.php';

// Ensure that the request method is a POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Read the JSON body of the request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Ensure that the required data is present
if (!isset($data['tableId']) || !isset($data['orderItems']) || !isset($data['waiterId'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required data']);
    exit;
}

if (!empty($data['orderItems'])) {
    $waiterId = $data['waiterId'];
    $tableId = $data['tableId'];
    $orderItems = $data['orderItems'];

    try {
        $dbh->db->begin_transaction(); // Start transaction
        $orderNum = $dbh->createNewCustomerOrder($tableId, $waiterId); // Create customer order

        // Add each product to the customer order inside the transaction
        foreach ($orderItems as $item) {
            $menuProdId = $item['prodId'];
            $quantity = $item['quantity'];
            $variations = $item['variations'];
            $placeholders = implode(',', array_fill(0, count($variations), '?')); // Add variations as string 

            $dbh->addProductToCustomerOrder($menuProdId, $tableId, $orderNum, $quantity, $variations);
        }

        $dbh->db->commit(); // Commit the transaction if everything is ok
        echo json_encode(['success' => true, 'message' => 'Order added successfully']);
    } catch (Exception $error) {
        $dbh->db->rollback(); // Rollback the transaction if an error occurs
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => "Error while adding product to customer order:\n\t" . $error->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing products']);
    exit;
}


