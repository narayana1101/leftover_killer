<?php
include('db_config.php');

$result = array();
$ingredients = array();

// Create connection
$conn = mysqli_connect($servername, $username, $password, "leftover_killer");
// Check connection
if (!$conn) {
	$result["success"]=false;
	die("Connection failed: " . mysqli_connect_error());
}
$post = json_decode(file_get_contents('php://input'), true);




$recipe_name = $post["recipe_name"];
$recipe_image_URL = $post["recipe_image_URL"];
$instruction = $post["instruction"];

$ingredient = $post["ingredient"];

$image_URL = $post["image_URL"];




if(empty($recipe_name) || empty($ingredient)){
	$result["success"]=false;
	die("Missing recipe_name or ingredient: "); 
}



$sql_select_recipe = "SELECT * from recipe where recipe_name = '{$recipe_name}'";



if(mysqli_num_rows(mysqli_query($conn, $sql_select_recipe)) == 0){



	if(!empty($recipe_image_URL) && !empty($instruction)){
		$sql = "INSERT INTO recipe(recipe_name, imageURL, instruction) VALUES ('{$recipe_name}', '{$recipe_image_URL}', '{$instruction}')";
	}
	else if(!empty($recipe_image_URL) && empty($instruction)){
		echo "\n"."hi";
		$sql = "INSERT INTO recipe (recipe_name, imageURL) VALUES ('{$recipe_name}', '{$recipe_image_URL}')";
	}
	else if(empty($recipe_image_URL) && !empty($instruction)){
		$sql = "INSERT INTO recipe (recipe_name, instruction) VALUES ('{$recipe_name}', '{$instruction}')";
	}
	else{
		$sql = "INSERT INTO recipe (recipe_name) VALUES ('{$recipe_name}')";
	}

	if (mysqli_query($conn, $sql) === TRUE) {
		$result["success"]=true; 
	} else {
		$result["success"]=false; 
		die("insert recipe failed");
	}


	//ingredient insert
	foreach($ingredient as $index => $ingred){
		$sql_select_ingredient = "SELECT * from ingredient where ingredient_name = '{$ingred}'";
		if(mysqli_num_rows(mysqli_query($conn, $sql_select_ingredient)) == 0){
			if(empty($image_URL)){
				$sql = "INSERT INTO ingredient (ingredient_name) VALUES ('{$ingred}')";
			}
			else{
				$sql = "INSERT INTO ingredient (ingredient_name, imageURL) VALUES ('{$ingred}', '{$image_URL[$index]}')";
			}

			if (mysqli_query($conn, $sql) === TRUE && $result["success"]) {
				$result["success"]=true; 
			} else {
				$result["success"]=false; 
				die("insert ingredient failed");
			}
		}
	}


	//recipe_ingredient insert
	$sql_select_recipe_id = "SELECT recipe_id from recipe where recipe_name = '{$recipe_name}'";
	$result_recipe_id = mysqli_query($conn, $sql_select_recipe_id);
	while($row = $result_recipe_id->fetch_assoc()) {
		$recipe_id = $row["recipe_id"];
	}	


	foreach($ingredient as $ingred){
		$sql_select_ingredient_id = "SELECT ingredient_id from ingredient where ingredient_name = '{$ingred}'";
		$result_ingredient_id = mysqli_query($conn, $sql_select_ingredient_id);

		$row = $result_ingredient_id->fetch_assoc();
		$ingredient_id = $row["ingredient_id"];


		$sql = "INSERT INTO recipe_ingredient(recipe_id, ingredient_id) VALUES ('{$recipe_id}', '{$ingredient_id}')";

		if (mysqli_query($conn, $sql) === TRUE && $result["success"]) {
			$result["success"]=true; 
		} else {
			$result["success"]=false;
			die("insert recipe_ingredient failed");
		}

	}

	



}
else{
	$result["success"]=false; 
	die("already have the recipe"); 
}





echo (json_encode($result));

$conn->close();

?>
