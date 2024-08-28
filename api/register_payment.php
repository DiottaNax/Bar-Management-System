<?php
require_once '../db-config.php';

// Assicuriamoci che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

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
    unset($total, $paymentMethod, $givenMoney, $changeAmount, $tableId, $paidProducts);
    $total = $_POST['total'];
    $paymentMethod = $_POST['paymentMethod'];
    $givenMoney = empty($_POST['givenMoney']) ? null : $_POST['givenMoney'];
    $changeAmount = empty($_POST['changeAmount']) ? null : $_POST['changeAmount'];
    $tableId = $_POST['tableId'];
    $paidProducts = $_POST['paidProducts'];

    $dbh->db->begin_transaction();

    try {
        $receiptId = $dbh->addNewReceipt($total, $paymentMethod, $givenMoney, $changeAmount, $tableId);

        if ($receiptId) {
            foreach ($paidProducts as $paidProd) {
                $queryResult = $dbh->addNewPaidProduct($paidProd['orderedProdId'], $paidProd['menuProdId'], $tableId, $receiptId, $paidProd['quantity']);

                if (!$queryResult)
                    break;
            }

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
