<?php
class GetIngredientDetails
{
    public static $database;
    // Construct for database
	public function __construct($servername, $username, $password, $dbname){
		self::$database = new mysqli($servername, $username, $password, $dbname);
    }

    // Process top recipes using a certain ingredient, helper method.
    public function topRecipesWithIngredient($ingredient_id) {
        // only selecting the list of recipes asscociated with desired ingredient
        $sql_select_recipe_top = "SELECT recipe.recipe_id, recipe.popularity, recipe.imageURL,
        reicpe.recipe_name, recipe_ingredient.ingredient_id
        FROM recipe INNER JOIN recipe_ingredient
        ON recipe.recipe_id = recipe_ingredient.recipe_id
        WHERE recipe_ingredient.ingredient_id = '$ingredient_id'
        ORDER BY popularity DESC";

        $response["Top_recipes"] = array();
        $Recipe = array();
        $counter = 0;
        while ($row = $result->fetch_assoc() && $counter < 5) {
            $Recipe['id'] = $row['recipe_id'];
            $Recipe['name'] = $row['recipe_name'];
            $Recipe['img_url'] = $row['imageURL'];
            array_push($response["Top_recipes"], $Recipe);
            ++$counter;
        }
        return $response;
    }

    // Process ingredient details, what gets called
	public function processIngredientDetails($ingredient_id){
        $sql_select_ingredient = "SELECT * FROM ingredient WHERE ingredient_id = '$ingredient_id'";
        $response = array();
        $response["success"] = true;   
        
        while ($row = $result->fetch_assoc()) {
            $response['ingredient_name'] = $row['ingredient_name'];
            $response['image_URL'] = $row['imageURL'];
        }
        array_push($response["Top_recipes"], topRecipesWithIngredient($ingredient_id));

        return $response; // array with the top popular recipes asscociated with the ingredient
    }

}
?>