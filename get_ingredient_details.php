<?php
include('db_config.php');
include('./classes/getIngredientDetails.php');
$response = array();
// Create connection
$conn = new GetIngredientDetails($servername, $username, $password, $dbname);
// Check connection
if ($conn::$database->connect_errno) {
        $response["success"]=false;
        $response["error"] = "Connection to databse failed: " . $conn::$database->connect_error;
        echo json_encode($response);
        exit();
}

// Get the ingredient ID
$ingredient_id = $_POST["ingredient_id"];
// echo("" + $ingredient_id);

// Process ingredient details
$response = $conn -> processIngredientDetails($ingredient_id, $response);



// Get top recipes       
// only selecting the list of recipes asscociated with desired ingredient
$response = $conn -> topRecipesWithIngredient($ingredient_id, $response);

//array_push($response, $conn -> topRecipesWithIngredient($ingredient_id));


// encode into json
echo json_encode($response);
// close connection
$conn::$database->close();
?>