<?php
include('db_config.php'); // configures the database
include('./classes/getIngredients.php');
$response= array();
// Create connection
$conn = new GetIngredients($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
        $response["success"]=false;
        $response["error"] = "Connection to databse failed: " . $conn::$database->connect_error;

}
// select all fields in ingredients
else{
        $response = $conn->process_query();
}
        




// encode into json
echo json_encode($response);
// close connection
$conn::$database->close();

?>
