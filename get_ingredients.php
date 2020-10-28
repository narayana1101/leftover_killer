<?php

include('db_config.php'); // Includes the configuration of database variables
// Connect to database.
$con = mysqli_connect($servername, $username, $password, $dbname);
$response = array();
// Check if connection succeded.
if($con) {
    $sql = "select * from ingredient";
    $result = mysqli_query($conn, $sql);
    // Now below fetch all the ingredient data.
    $response["ingredients"] = array();
    if($result) {
        // Loop through database table fetching ingrient id,name and image URL.
        while($row = $result->mysqli_fetch_assoc($result) ) {
            // Have an array with index 0,1,2 related to id, name, image respectively
            $Ingredient = array();
            $Ingredient['Id'] = $row['ingredient_id'];
            $Ingredient['Name'] = $row['ingredient_name'];
            $Ingredient['image_url'] = $row['imageURL'];
            // Push Ingredient array into list of ingredients List<Ingredient>.
            array_push($response["ingredients"], $Ingredient); 
        }
    }
} else {
    // If connection failed.
    $result["success"]=false;
    die ("Database Connection Failed : " . mysqli_connect_error());
}
// Encode in json to format the ingredients and their details.
echo (json_encode($response));
// close connection with database.
$conn->close();
?>