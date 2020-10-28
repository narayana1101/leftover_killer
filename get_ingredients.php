<?php
include('db_config.php'); // configures the database

$response= array();
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
        $result["success"]=false;
        die("Connection to databse failed: " . mysqli_connect_error());
}
// select all fields in ingredients
$sql = "SELECT * FROM ingredient";

$result = mysqli_query($conn, $sql);
if ($result > 0) {	
	$response["ingredients"] = array();
	while($row = $result->fetch_assoc()) {
		$Ingredient = array();
		$Ingredient["Id"] = $row["ingredient_id"];
		$Ingredient["Name"] = $row["ingredient_name"];
		$Ingredient["image_url"] = $row["imageURL"];
		array_push($response["ingredients"], $Ingredient);
	}
} else{
	$response["success"]=false;	
}
// encode into json
echo (json_encode($response));
// close connection
$conn->close();

?>