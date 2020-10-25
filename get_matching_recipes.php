<?php
// include 'db_credentials.php';


// references: https://stackoverflow.com/questions/18866571/receive-json-post-with-php
// references: https://stackoverflow.com/questions/17226762/mysqli-bind-param-for-array-of-strings

$response = array();

$post = json_decode(file_get_contents('php://input'), true);


$ingredient_list = $post['ingredients'];


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

    // echo $sql_get_ingredient_id;
    $stmt = $mysqli->stmt_init(); // clear the result
    $stmt = $mysqli->prepare($sql_get_ingredient_id);
    $input_type = str_repeat("s", $ingredient_num);
    $stmt->bind_param($input_type, ...$ingredient_list);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        array_push($ingredients_id_array, $row['ingredient_id']);
    }
    $stmt->free_result();
    $stmt->close();
    return $ingredients_id_array;
};

function get_recipe_ingredient_info($mysqli, $ingredients_id_array)
{
    $recipes_ingredient_array = array(); // return array

    $ingredient_num = count($ingredients_id_array);
    $sql_get_recipes_contain_ingredient = "SELECT * FROM recipe_ingredient WHERE ingredient_id in (";
    if ($ingredient_num > 1) {
        $sql_get_recipes_contain_ingredient .= str_repeat("? ,", $ingredient_num - 1);
    }
    $sql_get_recipes_contain_ingredient .= "?";
    $sql_get_recipes_contain_ingredient .= "
    );";

    $stmt = $mysqli->stmt_init(); // clear the result
    $stmt = $mysqli->prepare($sql_get_recipes_contain_ingredient);
    $input_type = str_repeat("i", $ingredient_num);
    $stmt->bind_param($input_type, ...$ingredients_id_array);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        array_push($recipes_ingredient_array, $row['recipe_id']);
    }
    $stmt->free_result();
    return $recipes_ingredient_array;
};

function get_recipe_id_with_ingredients($mysqli, $recipes_ingredient, $ingredients_id)
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

    $stmt = $mysqli->stmt_init(); // clear the result
    $stmt = $mysqli->prepare($sql_select_recipe_by_recipe_id);
    $input_type = str_repeat("i", $ingredient_num);
    $stmt->bind_param($input_type, ...$ingredients_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recipe = array();
        $recipe['recipe_id'] = $row['recipe_id'];
        $recipe['recipe_name'] = $row['recipe_name'];
        $recipe['img_url'] = $row['imageURL'];

        array_push($recipes_array, $recipe);

    }
    $stmt->free_result();
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
    // print_r($ingredients_id);

    $recipe_contain_input_ingredient = get_recipe_ingredient_info($mysqli, $ingredients_id);
    // print_r($recipe_contain_input_ingredient);

    $recipe_list = get_recipe_id_with_ingredients($mysqli, $recipe_contain_input_ingredient, $ingredients_id);
    $response['matching_recipes'] = $recipe_list;
}

echo (json_encode($response));

// $stmt->close();
$mysqli->close();
