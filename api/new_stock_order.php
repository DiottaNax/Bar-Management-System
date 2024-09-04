<?php
require_once '../db-config.php';

// Ensure that the request method is a post or else return an error
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Read the JSON body of the request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Ensure that the orderItems and storekeeperId are present
if (!isset($data['orderItems']) || !isset($data['storekeeperId'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required data']);
    exit;
}

// If the orderItems array is not empty, create the stock order
if (!empty($data['orderItems'])) {
    $storekeeperId = $data['storekeeperId'];
    $estimatedCost = $data['estimatedCost'];
    $orderItems = $data['orderItems'];

    try {
        $dbh->db->begin_transaction(); // Start transaction
        $orderId = $dbh->createNewStockOrder($storekeeperId, $estimatedCost); // Create customer order and get the inserted id

        // Add each product to the stock order inside the transaction
        foreach ($orderItems as $item) {
            $prodId = $item['prodId'];
            $quantity = $item['quantity'];
            $supplierName = $item['supplierName'];

            $dbh->addItemToStockOrder($prodId, $orderId, $supplierName, $quantity);
        }

        // Commit the transaction if everything is ok
        $dbh->db->commit();
        echo json_encode(['success' => true, 'message' => 'Order created successfully']);
    } catch (Exception $error) {
        // Rollback the transaction if an error occurs
        $dbh->db->rollback();
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => "Error while adding product the to stock order:\n\t" . $error->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing products']);
    exit;
}


