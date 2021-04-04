<?php
include('db_config.php');
include('./classes/getRecipes.php');
$response = array();

$conn = new GetRecipes($servername, $username, $password, $dbname);

if (!$conn) {
	$result["success"] = false;
	$result["error"] = "Connection to databse failed: " . $conn::$database->connect_error;
    echo json_encode($result);
    exit();
}



$response = $conn->process_query();



echo json_encode($response);

$conn::$database->close();
?>
