<?php

header("Content-Type: application/json");
require_once '../db-config.php';

function validateEmployeeData($data)
{
    // Check if the required fields are set and not empty
    $requiredFields = ['email', 'password', 'cf', 'name', 'surname', 'birthday', 'hiringDate'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return false;
        }
    }
    return true;
}

// Handle the request based on the HTTP method:
// POST: Add a new employee
// PUT: Update an existing employee
// DELETE: Delete an employee
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Get and validate the employee data from the request body
        $data = json_decode(file_get_contents("php://input"), true);
        if (validateEmployeeData($data)) {
            // Add the new employee to the database
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
        // Update an existing employee
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['employeeId'])) {
            $employeeId = $data['employeeId'];
            unset($data['employeeId']); // Remove the employeeId from the data array, it cannot be updated

            $result = $dbh->updateEmployee($employeeId, $data); // Update the employee in the database and get the result
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
        // Delete an employee
        $employeeId = $_GET['employeeId'] ?? false;
        if (isset($employeeId)) {
            $result = $dbh->deleteEmployee($employeeId); // Delete the employee from the database and get the result or else return an error
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

