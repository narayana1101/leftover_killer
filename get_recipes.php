<?php
include('db_config.php');
include('./classes/getRecipes.php');
$response = array();

$conn = new GetRecipes($servername, $username, $password, $dbname);

if (!$conn) {
	$result["success"] = false;
	die("Connection failed: " . mysqli_connect_error());
}



$response = $conn->process_query();



echo (json_encode($response));

$conn::$database->close();
?>
