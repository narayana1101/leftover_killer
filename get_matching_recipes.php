<?php

// references: https://stackoverflow.com/questions/18866571/receive-json-post-with-phpnclude 'db_credentials.php';
include 'db_config.php';
include './classes/recipe.php';
$response = array();
$post = json_decode(file_get_contents('php://input'), true);
$ingredient_list = $post['ingredients'];

// Create connection
$RecipeModel = new Recipe($servername, $username, $password, "leftover_killer");
// Check connection
if ($RecipeModel::$database->connect_errno) {
	$response["error"] = "Failed to connect to MySQL: " . $RecipeModel::$database->connect_error;
	echo json_encode($response);
	exit();
}

if (empty($ingredient_list)) {
	$response["success"] = false;
	$response["error"] = "Missing ingredient_list:";

} else if (!is_array($ingredient_list)) {
	$response["success"] = false;
	$response["error"] = "ingredients should be an array";

} else {
	$response["success"] = true;
	$ingredients_id = $RecipeModel->get_ingredient_id($ingredient_list);
	$recipe_list = $RecipeModel->get_recipe_id_with_ingredients($ingredients_id);
	$response['recipes'] = $recipe_list;
}

echo json_encode($response);
$RecipeModel::$database->close();
?>
