<?php

// Connect to database.
$con = mysqli_connect("localhost", "phpclient", "leftoverkillerphp", "leftover_killer");
$response = array();
// Check if connection succeded.
if($con) {
    $sql = "select * from ingredient";
    $result = mysqli_query($conn, $sql);
    // Now below fetch all the ingredient data.
    $response["ingredients"] = array();
    if($result) {
        $i = 0; // Loop counter
        // Loop through database table fetching ingrient id,name and image URL.
        while($row = $result->mysqli_fetch_assoc($result) ) {
            // Have an array with index 0,1,2 related to id, name, image respectively
            // into a class Ingredient.
            $Ingredient = array();
            $Ingredient[$i]['Id'] = $row['ingredient_id'];
            $Ingredient[$i]['Name'] = $row['ingredient_name'];
            $Ingredient[$i]['image_url'] = $row['imageURL'];
            // Push Ingredient array into list of ingredients List<Ingredient>.
            array_push($response["ingredients"], $Ingredient); 
            $i++;
        }
    }
} else {
    $result["success"]=false;
    die ("Database Connection Failed : " . mysqli_connect_error());
}
// Encode in json to format the ingredients and their details.
echo (json_encode($response));
// close connection with database.
$conn->close();
?>