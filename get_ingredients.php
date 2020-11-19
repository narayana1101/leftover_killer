<?php
include('db_config.php'); // configures the database
include('./classes/getIngredients.php');
$response= array();
// Create connection
$conn = new GetIngredients($servername, $username, $password, $dbname);
// Check connection
if ($conn::$database->connect_errno) {
        $result["success"]=false;
        $result["error"] = "Connection to databse failed: " . $conn::$database->connect_error;
        echo (json_encode($result));
        $conn::$database->close();
}
// select all fields in ingredients



$response = $conn->process_query();

// encode into json
echo (json_encode($response));
// close connection
$conn::$database->close();

?>
