<?php
// references: https://stackoverflow.com/questions/17226762/mysqli-bind-param-for-array-of-strings
// get_recipe_details class

class GetRecipeDetails
{
    public static $database;
    

	public function __construct($servername, $username, $password, $dbname){
		self::$database = new mysqli($servername, $username, $password, $dbname);
	}
    // process recipe info
	public function processRecipeInfo($recipe_id){
        $sql_select_recipe = "SELECT * FROM recipe WHERE recipe_id = ?";
        $response= array();
        $response["success"] = true;
        


        

        // process recipe info
        
        $stmt = self::$database->stmt_init();
        $stmt = self::$database->prepare($sql_select_recipe);

        $stmt->bind_param("i", $recipe_id_pre);
        $recipe_id_pre = $recipe_id;
        $stmt->execute();

        $result = $stmt->get_result();
        // echo $result;
        
        while ($row = $result->fetch_assoc()) {
            // echo 'ID: ' . $row['recipe_id'] . " ";
            // echo 'Recipe Name: ' . $row['recipe_name'] . "\n";
            $response['recipe_id'] = $row['recipe_id'];
            $response['recipe_name'] = $row['recipe_name'];
            $response['imageURL'] = $row['imageURL'];
            $response['popularity'] = $row['popularity'];
            $response['instruction'] = $row['instruction'];
        }
        $stmt->free_result();

        return $response;
    }

    // process ingredient info
    public function processIngredientInfo($recipe_id, $response){
        $sql_find_ingredient_by_recipe_id = "SELECT * FROM ingredient WHERE ingredient_id IN (SELECT ingredient_id FROM recipe_ingredient WHERE recipe_id = ?)";
        $response["ingredients"] = array();
        $ingredient = array();
        $stmt = self::$database->stmt_init();
        $stmt = self::$database->prepare($sql_find_ingredient_by_recipe_id);

        $stmt->bind_param("i", $recipe_id_pre);
        $recipe_id_pre = $recipe_id;
        $stmt->execute();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {

            //echo 'Ingredient ID: ' . $row['ingredient_id'] . " ";
            //echo 'Ingredient name: ' . $row['ingredient_name'] . "\n";

            $ingredient['ingredient_id'] = $row['ingredient_id'];
            $ingredient['ingredient_name'] = $row['ingredient_name'];
            $ingredient['imageURL'] = $row['imageURL'];
            array_push($response["ingredients"], $ingredient);
            
        }
        $stmt->free_result();
        $stmt->close();
        return $response;
        
    }
	
}
?>