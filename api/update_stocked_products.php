<?php

header("Content-Type: application/json");
require_once '../db-config.php';

function validateProductData($data)
{
    $requiredFields = ['name', 'category', 'subcategory', 'availability'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || (empty($data[$field]) && $data[$field] != 0)) {
            return false;
        }
    }

    return true;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (validateProductData($data)) {
            $result = $dbh->addNewStockedProduct(
                $data['name'],
                $data['category'],
                $data['subcategory'],
                $data['availability'],
                $data['imgFile'] ?? null
            );
            if ($result) {
                http_response_code(201);
                echo json_encode(['success' => 1, 'message' => "Stocked Product added successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to add Stocked Product"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Invalid data provided"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['prodId'])) {
            $prodId = $data['prodId'];
            unset($data['prodId']);

            $result = $dbh->updateStockedProduct($prodId, array_filter($data));
            if ($result) {
                echo json_encode(['success' => 1, 'message' => "Stocked Product updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to update Stocked Product"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Stocked Product ID is required"]);
        }
        break;

    case 'DELETE':
        $prodId = $_GET['prodId'] ?? null;
        if ($prodId) {
            $result = $dbh->deleteStockedProduct($prodId);
            if ($result) {
                echo json_encode(['success' => 1, 'message' => "Stocked Product deleted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to delete Stocked Product"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Stocked Product ID is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => 0, 'message' => "Method not allowed"]);
        break;
}

