<?php
require_once '../db-config.php';

// Assicuriamoci che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Leggiamo il corpo della richiesta JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Verifichiamo che i dati necessari siano presenti
if (!isset($data['orderItems']) || !isset($data['storekeeperId'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required data']);
    exit;
}

if (!empty($data['orderItems'])) {
    /*echo "\n\n Dumping orderItems:\n";
    var_dump($data);
    echo "\n\n\n";*/
    $storekeeperId = $data['storekeeperId'];
    $estimatedCost = $data['estimatedCost'];
    $orderItems = $data['orderItems'];

    try {
        $dbh->db->begin_transaction(); // Start transaction
        $orderId = $dbh->createNewStockOrder($storekeeperId, $estimatedCost); // Create customer order

        foreach ($orderItems as $item) {
            $prodId = $item['prodId'];
            $quantity = $item['quantity'];
            $supplierName = $item['supplierName'];

            $dbh->addItemToStockOrder($prodId, $orderId, $supplierName, $quantity);
        }

        $dbh->db->commit();
        echo json_encode(['success' => true, 'message' => 'Order created successfully']);
    } catch (Exception $error) {
        $dbh->db->rollback();
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => "Error while adding product the to stock order:\n\t" . $error->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing products']);
    exit;
}


