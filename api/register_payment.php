<?php
require_once '../db-config.php';

// Ensure that the request is of type POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Check if all the required fields are set
if (
    isset(
    $_POST['total'],
    $_POST['paymentMethod'],
    $_POST['givenMoney'],
    $_POST['changeAmount'],
    $_POST['tableId'],
    $_POST['paidProducts']
) && !empty($_POST['paidProducts'])
) {
    // Unset the variables to avoid conflicts
    unset($total, $paymentMethod, $givenMoney, $changeAmount, $tableId, $paidProducts);

    // Set the variables
    $total = $_POST['total'];
    $paymentMethod = $_POST['paymentMethod'];
    $givenMoney = empty($_POST['givenMoney']) ? null : $_POST['givenMoney'];
    $changeAmount = empty($_POST['changeAmount']) ? null : $_POST['changeAmount'];
    $tableId = $_POST['tableId'];
    $paidProducts = $_POST['paidProducts'];

    // Start a transaction
    $dbh->db->begin_transaction();

    try {
        // Add a new receipt to the database and get the inserted id
        $receiptId = $dbh->addNewReceipt($total, $paymentMethod, $givenMoney, $changeAmount, $tableId);

        if ($receiptId) {
            // Add each paid product to the receipt
            foreach ($paidProducts as $paidProd) {
                $queryResult = $dbh->addNewPaidProduct($paidProd['orderedProdId'], $paidProd['menuProdId'], $tableId, $receiptId, $paidProd['quantity']);

                // If the query fails, break the loop and rollback the transaction
                if (!$queryResult)
                    break;
            }

            // Commit the transaction if the query is successful or rollback the transaction if it fails
            if($queryResult) {
                $dbh->db->commit();
                echo json_encode(['success' => true, 'error' => "Receipt compiled successfully"]);
            } else {
                $dbh->db->rollback();
                echo json_encode(['success' => false, 'error' => "Query to add paid product failed."]);
            }
            
        } else {
            $dbh->db->rollback();
            echo json_encode(['success' => false, 'error' => "Query to add receipt failed."]);
        }

    } catch (Exception $error) {
        $dbh->db->rollback();
        echo json_encode(['success' => false, 'error' => $error->getMessage()]);
    }
}
