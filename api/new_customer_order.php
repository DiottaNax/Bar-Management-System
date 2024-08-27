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
if (!isset($data['tableId']) || !isset($data['orderItems']) || !isset($data['waiterId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required data']);
    exit;
}

if (!empty($data['orderItems'])) {
    /*echo "\n\n Dumping orderItems:\n";
    var_dump($data);
    echo "\n\n\n";*/
    $waiterId = $data['waiterId'];
    $tableId = $data['tableId'];
    $orderItems = $data['orderItems'];

    try {
        foreach ($orderItems as $item) {
            $menuProdId = $item['prodId'];
            $quantity = $item['quantity'];
            $variations = $item['variations'];
            $placeholders = implode(',', array_fill(0, count($variations), '?'));

            $params = array_merge([$menuProdId, $tableId, 1, count($variations)], $variations, [count($variations)]);

            $result = $dbh->addProductToCustomerOrder($waiterId, $menuProdId, $tableId, $quantity, $variations);

            if (!$result['success']) {
                throw new Exception("Failed to add product to order: " . $result['message']);
            }
        }

        echo json_encode(['success' => true, 'message' => 'Order added successfully']);
    } catch (Exception $error) {
        http_response_code(500);
        echo json_encode(['error' => "Error while adding product to customer order:\n\t" . $error->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing products']);
    exit;
}


