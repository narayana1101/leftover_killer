<?php
include 'db_credentials.php';
$response = array();

// reference 1: https://www.php.net/manual/en/mysqli-stmt.bind-param.php
// reference 2: https://stackoverflow.com/questions/18753262/example-of-how-to-use-bind-result-vs-get-result
// reference 3: https://www.php.net/manual/en/mysqli-stmt.get-result.php

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$recipe_id = $_POST["recipe_id"];

if (empty($recipe_id)) {
    $response["success"] = false;
    die("Missing recipe:");
} else {
    $response["success"] = true;
    $response["ingredients"] = array();

    // process recipe info
    $sql_select_recipe = "SELECT * FROM recipe WHERE recipe_id = ?";
    $stmt = $mysqli->stmt_init();
    $stmt = $mysqli->prepare($sql_select_recipe);
    $stmt->bind_param("i", $recipe_id_pre);
    $recipe_id_pre = $recipe_id;
    $stmt->execute();
    $result = $stmt->get_result();
    // echo $result;

    while ($row = $result->fetch_assoc()) {
        echo 'ID: ' . $row['recipe_id'] . " ";
        echo 'Recipe Name: ' . $row['recipe_name'] . "\n";
        $response['id'] = $row['recipe_id'];
        $response['name'] = $row['recipe_name'];
        $response['image'] = $row['imageURL'];
        $response['popularity'] = $row['popularity'];
        $response['instruction'] = $row['instruction'];
    }
    $stmt->free_result();

    // process ingredient info
    $sql_find_ingredient_by_recipe_id = "SELECT * FROM ingredient WHERE ingredient_id IN (SELECT ingredient_id FROM recipe_ingredient WHERE recipe_id = ?)";
    $stmt = $mysqli->stmt_init();
    $stmt = $mysqli->prepare($sql_find_ingredient_by_recipe_id);
    $stmt->bind_param("i", $recipe_id_pre);
    $recipe_id_pre = $recipe_id;
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {

        echo 'Ingredient ID: ' . $row['ingredient_id'] . " ";
        echo 'Ingredient name: ' . $row['ingredient_name'] . "\n";
        $ingredient = array();
        $ingredient['id'] = $row['ingredient_id'];
        $ingredient['name'] = $row['ingredient_name'];
        $ingredient['imageURL'] = $row['imageURL'];
        array_push($response["ingredients"], $ingredient);
    }
    $stmt->free_result();

}

echo (json_encode($response));

$stmt->close();
$mysqli->close();
