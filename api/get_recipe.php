<?php

include_once "../db-config.php";

$result['recipe'] = "";

if(isset($_GET['prodId'])) {
    $ingredients = $dbh->getRecipe($_GET['prodId']);

    $result['numIngredients'] = sizeof($ingredients);

    if(!empty($ingredients)) {
        foreach($ingredients as $ingredient) {
            if (!empty($result['recipe']))
                $result['recipe'] .= ", ";

            $result['recipe'] .= $ingredient['portionSize'] . " of " . $ingredient['ingredientName'];
        }
    }
}

echo json_encode($result);

header("Content-type: application/json");
