<?php

header("Content-Type: application/json");
require_once '../db-config.php';

// Controls that the required fields are set and not empty
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

// Handles the HTTP requests based on the method type:
// POST: Adds a new menu product to the database
// PUT: Updates an existing menu product in the database
// DELETE: Deletes a menu product from the database 
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        // Controls that the required fields are correct
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
        // Controls that the required fields are correct
        if (isset($data['prodId'])) {
            $prodId = $data['prodId'];
            unset($data['prodId']); // Removes the prodId from the data array, prodId cannot be updated

            // Updates the menu product in the database
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
        $prodId = $_GET['prodId'] ?? false;
        if ($prodId) {
            // Deletes the menu product from the database if the prodId is set
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
        // Returns an error if the method is not allowed
        http_response_code(405);
        echo json_encode(['success' => 0, 'message' => "Method not allowed"]);
        break;
}

