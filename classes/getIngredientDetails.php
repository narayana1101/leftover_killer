<?php
class GetIngredientDetails
{
    public static $database;
    // Construct for database
	public function __construct($servername, $username, $password, $dbname){
		self::$database = new mysqli($servername, $username, $password, $dbname);
    }

    // Process top recipes using a certain ingredient, helper method.
    public function topRecipesWithIngredient($ingredient_id, $response){
        // only selecting the list of recipes asscociated with desired ingredient
        $sql_select_recipe_top = "SELECT recipe.recipe_id, recipe.popularity, recipe.imageURL,
        recipe.recipe_name, recipe_ingredient.ingredient_id
        FROM recipe INNER JOIN recipe_ingredient
        ON recipe.recipe_id = recipe_ingredient.recipe_id
        WHERE ingredient_id = (?)
        ORDER BY popularity DESC";
        
        $stmt = self::$database->prepare($sql_select_recipe_top);
        $stmt->bind_param("i", $id);
        $id = $ingredient_id;

        
        $stmt->execute();
        $result = $stmt->get_result();

        
        $counter = 0;
        if($result->num_rows > 0){
            $response["Top_recipes"] = array() ;
            while (($row = $result->fetch_assoc()) && $counter < 5) {
                $Recipe = array();

                $Recipe['recipe_id'] = $row['recipe_id'];
                $Recipe['recipe_name'] = $row['recipe_name'];
                $Recipe['img_url'] = $row['imageURL'];
                array_push($response["Top_recipes"], $Recipe);
                ++$counter;
            }
        }

        


        return $response;
    }

    // Process ingredient details, what gets called
	public function processIngredientDetails($ingredient_id, $response){
        // Check if we got the ingredient ID
        $sql_select_ingredient = "SELECT * FROM ingredient WHERE ingredient_id = $ingredient_id";
        
        $stmt = self::$database->prepare($sql_select_ingredient);
        $stmt->execute();
        $result = $stmt->get_result();


          
        if($result->num_rows > 0){
            $response["success"] = true; 
            while ($row = $result->fetch_assoc()) {
                $response['ingredient_name'] = $row['ingredient_name'];
                $response['image_URL'] = $row['imageURL'];
            }
        }
        else{
            $response["success"] = false;
            $response["error"] = "There is no this ingredient";  
        }
        

        return $response; // array with the top popular recipes asscociated with the ingredient
    }

}
?>