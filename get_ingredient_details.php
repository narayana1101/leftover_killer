<?php
include('db_config.php');
include('./classes/getIngredientDetails.php');
$response = array();
// Create connection
$conn = new GetIngredientDetails($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
        $result["success"]=false;
        die("Connection to databse failed: " . mysqli_connect_error());
}

// Get the ingredient ID
$ingredient_id = $_POST["ingredient_id"];

// Check if we got the ingredient ID
if (empty($ingreident_id)) {
	$response["success"] = false;
	die("Ingredient is missing!");
} else {
	// process Ingredient details
    $response = $conn->processIngredientDetails($ingredient_id);
}
// encode into json
echo (json_encode($response));
// close connection
$conn->close();
?>