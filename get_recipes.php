<?php
include('db_config.php');

$response = array();

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
	$result["success"] = false;
	die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT recipe_id, recipe_name, imageURL from recipe ORDER BY popularity DESC";


$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

	$response["success"] = true;
	$response["recipes"] = array();
	while($row = $result->fetch_assoc()) {

		$recipe = array();
		$recipe["recipe_id"] = $row["recipe_id"];
		$recipe["recipe_name"] = $row["recipe_name"];
		$recipe["img_url"] = $row["imageURL"];

		array_push($response["recipes"], $recipe);
	}
} 


echo (json_encode($response));

$conn->close();
?>
