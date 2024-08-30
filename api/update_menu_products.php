<?php

header("Content-Type: application/json");
require_once '../db-config.php';

function validateProductData($data)
{
    $requiredFields = ['name', 'category', 'subcategory', 'description', 'price'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
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
            $result = $dbh->addNewMenuProduct(
                $data['name'],
                $data['category'],
                $data['subcategory'],
                $data['description'],
                $data['price'],
                $data['imgFile'] ?? null
            );
            if ($result) {
                http_response_code(201);
                echo json_encode(['success' => 1, 'message' => "Menu Product added successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to add Menu Product"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Invalid data provided: " . implode(",  ", $_POST)]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['prodId'])) {
            $prodId = $data['prodId'];
            unset($data['prodId']);

            $result = $dbh->updateMenuProduct($prodId, array_filter($data));
            if ($result) {
                echo json_encode(['success' => 1, 'message' => "Menu Product updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to update Menu Product"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Menu Product ID is required"]);
        }
        break;

    case 'DELETE':
        $prodId = $_GET['prodId'] ?? null;
        if ($prodId) {
            $result = $dbh->deleteMenuProduct($prodId);
            if ($result) {
                echo json_encode(['success' => 1, 'message' => "Menu Product deleted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to delete Menu Product"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Menu Product ID is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => 0, 'message' => "Method not allowed"]);
        break;
}

