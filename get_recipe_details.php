<?php
include 'db_config.php';
include './classes/getRecipeDetails.php';
$response = array();

// reference 1: https://www.php.net/manual/en/mysqli-stmt.bind-param.php
// reference 2: https://stackoverflow.com/questions/18753262/example-of-how-to-use-bind-result-vs-get-result
// reference 3: https://www.php.net/manual/en/mysqli-stmt.get-result.php

// Create connection
$mysqli = new GetRecipeDetails($servername, $username, $password, $dbname);
// Check connection
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli::$database->connect_error;
	exit();
}

$recipe_id = $_POST["recipe_id"];

if (empty($recipe_id)) {
	$response["success"] = false;
	die("Missing recipe:");
} else {
	$sql_select_recipe = "SELECT * FROM recipe WHERE recipe_id = ?";
	// process recipe info
	$response = $mysqli->processRecipeInfo($sql_select_recipe, $recipe_id);


	$sql_find_ingredient_by_recipe_id = "SELECT * FROM ingredient WHERE ingredient_id IN (SELECT ingredient_id FROM recipe_ingredient WHERE recipe_id = ?)";
	// process ingredient info	
	array_push($response["ingredients"], $mysqli->processIngredientInfo($sql_find_ingredient_by_recipe_id, $recipe_id));
}

echo (json_encode($response));

$mysqli::$database->close();

?>