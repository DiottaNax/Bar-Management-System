<?php

header("Content-Type: application/json");
require_once '../db-config.php';

function validateEmployeeData($data)
{
    $requiredFields = ['email', 'password', 'cf', 'name', 'surname', 'birthday', 'hiringDate'];
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
        // Aggiunta di un nuovo dipendente
        $data = json_decode(file_get_contents("php://input"), true);
        if (validateEmployeeData($data)) {
            $result = $dbh->addNewEmployee(
                $data['email'],
                $data['password'],
                $data['cf'],
                $data['name'],
                $data['surname'],
                $data['birthday'],
                $data['hiringDate'],
                $data['isWaiter'] ?? 1,
                $data['isStorekeeper'] ?? 0,
                $data['isKitchenStaff'] ?? 0,
                $data['isAdmin'] ?? 0,
                $data['city'] ?? null,
                $data['zipCode'] ?? null,
                $data['streetName'] ?? null,
                $data['streetNumber'] ?? null
            );
            if ($result) {
                http_response_code(201);
                echo json_encode(['success' => 1, 'message' => "Employee added successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to add employee"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Invalid data provided"]);
        }
        break;

    case 'PUT':
        // Aggiornamento di un dipendente esistente
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['employeeId'])) {
            $employeeId = $data['employeeId'];
            unset($data['employeeId']);

            $result = $dbh->updateEmployee($employeeId, $data);
            if ($result) {
                echo json_encode(['success' => 1, 'message' => "Employee updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to update employee"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Employee ID is required"]);
        }
        break;

    case 'DELETE':
        // Eliminazione di un dipendente
        $employeeId = $_GET['employeeId'] ?? null;
        if ($employeeId) {
            $result = $dbh->deleteEmployee($employeeId);
            if ($result) {
                echo json_encode(['success' => 1, 'message' => "Employee deleted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => 0, 'message' => "Failed to delete employee"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => 0, 'message' => "Employee ID is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => 0, 'message' => "Method not allowed"]);
        break;
}

