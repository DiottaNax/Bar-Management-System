<?php

// Ensure that the request method is a get
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

include_once "../db-config.php";

unset($result);
$result['success'] = 0;
$result['message'] = "Something went wrong during query execution";
$result['recipe'] = "";

if(isset($_GET['prodId'])) {
    $ingredients = $dbh->getRecipe($_GET['prodId']);

    // Set the ingredient's number to the appropriate size
    $result['numIngredients'] = sizeof($ingredients);

    // If the ingredients array is not empty, then add the ingredients to the result
    if(!empty($ingredients)) {
        foreach($ingredients as $ingredient) {
            if (!empty($result['recipe']))
                $result['recipe'] .= ", ";

            $result['recipe'] .= $ingredient['portionSize'] . " of " . $ingredient['ingredientName'];
        }
    }

    $result['success'] = 1;
    $result['message'] = "Recipe obtained successfully";
}

echo json_encode($result);

header("Content-type: application/json");
