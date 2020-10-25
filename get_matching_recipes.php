<?php

// references: https://stackoverflow.com/questions/18866571/receive-json-post-with-php
// references: https://stackoverflow.com/questions/17226762/mysqli-bind-param-for-array-of-strings
include 'db_credentials.php';
$response = array();
$post = json_decode(file_get_contents('php://input'), true);
$ingredient_list = $post['ingredients'];

function process_query($mysqli, $sql, $parameter, $parameter_type)
{
    $ingredient_num = count($parameter);
    $stmt = $mysqli->stmt_init(); // clear the result
    $stmt = $mysqli->prepare($sql);
    $input_type = str_repeat($parameter_type, $ingredient_num);
    $stmt->bind_param($input_type, ...$parameter);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->free_result();
    return $result;
}

function get_ingredient_id($mysqli, $ingredient_list)
{
    $ingredients_id_array = array(); // return array

    $ingredient_num = count($ingredient_list);
    $sql_get_ingredient_id = "SELECT ingredient_id FROM ingredient WHERE ingredient_name in (";
    if ($ingredient_num > 1) {
        $sql_get_ingredient_id .= str_repeat("? ,", $ingredient_num - 1);
    }
    $sql_get_ingredient_id .= "?";
    $sql_get_ingredient_id .= "
    );";

    $result = process_query($mysqli, $sql_get_ingredient_id, $ingredient_list, "s");

    while ($row = $result->fetch_assoc()) {
        array_push($ingredients_id_array, $row['ingredient_id']);
    }

    return $ingredients_id_array;
};

function get_recipe_id_with_ingredients($mysqli, $ingredients_id)
{
    $recipes_array = array();
    $ingredient_num = count($ingredients_id);
    $sql_select_recipe_by_recipe_id = "SELECT * FROM recipe WHERE recipe_id NOT IN (
        SELECT DISTINCT recipe_id FROM recipe_ingredient WHERE ingredient_id NOT IN (";
    if ($ingredient_num > 1) {
        $sql_select_recipe_by_recipe_id .= str_repeat("? ,", $ingredient_num - 1);
    }
    $sql_select_recipe_by_recipe_id .= "?";
    $sql_select_recipe_by_recipe_id .= "))";

    $result = process_query($mysqli, $sql_select_recipe_by_recipe_id, $ingredients_id, "i");
    while ($row = $result->fetch_assoc()) {
        $recipe = array();
        $recipe['recipe_id'] = $row['recipe_id'];
        $recipe['recipe_name'] = $row['recipe_name'];
        $recipe['img_url'] = $row['imageURL'];
        array_push($recipes_array, $recipe);
    }
    return $recipes_array;
}

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

if (empty($ingredient_list)) {
    $response["success"] = false;
    die("Missing ingredient_list:");
} else if (!is_array($ingredient_list)) {
    $response["success"] = false;
    die("ingredients should be an array");
} else {
    $response["success"] = true;
    $ingredients_id = get_ingredient_id($mysqli, $ingredient_list);
    $recipe_list = get_recipe_id_with_ingredients($mysqli, $ingredients_id);
    $response['matching_recipes'] = $recipe_list;
}

echo (json_encode($response));
$mysqli->close();
